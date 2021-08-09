<?php 

declare(strict_types = 1); 

class Write extends ProtectedController {
    private $writeModel;

    use WriteTraits;
    
    public function __grandchildConstruct(){
        $this->writeModel = $this->model('WriteModel');
    }
    
    public function index(){}
    
    /**
     * Upload Images for article
     * 
     * @route true
     */
    public function uploadImage() {
        $data = [
            'status' => 500,
            'url' => ""
        ];

        // refer to write model for iamge validation
        $img = $_FILES['image'] ?? $_POST['src'] ?? false;

        if($img) {
            $url = $this->writeModel->uploadImage($img);

            if($url) {
                $data['status'] = 200;
                $data['url'] = $url;
            }
        }

        echo json_encode($data);
    }

    /**
     * Validate article contents
     * Select Preview Image
     * Save in database
     * 
     * @route true
     * @postParams [title, tagline, content, tags]
     */
    public function submitArticle() {
        $title = Str::stripNewLines($_POST['title']);
        $tagline = Str::stripNewLines($_POST['tagline']);
        $content = $_POST['content'];
        $tags = $this->parseTags($_POST['tags']);

        foreach($tags as $key => $tag) if(Str::isEmptyStr($tag)) unset($tags[$key]);

        $content = Html::purifyHTML($content);

        $data = $this->checkArticleErrors([], [
            "title" => $title,
            "tagline" => $tagline,
            "content" => $content,
            "tags" => $tags
        ]);
        
        if($this->baseModel->isRateLimited("articles", $this->maxUserArticlesPerHour, 60) 
           || $this->baseModel->exceedsMaxRowCount("articles", $this->maxUserArticles)) {
            $data['limit_err'] = "You are being rate limited";
        }

        if(Str::emptyStrings($data)) {
            // Upload Article
            $preview = Html::getTagResults($content, "img")[0] ? Html::getTagResults($content, "img")[0]->getAttribute("src") : "";
            $articleId = $this->writeModel->createArticle($title, $tagline, $content, $preview);
            
            if($articleId) {
                $this->writeModel->insertTags($articleId, $tags);
                $data['status'] = 200;
                $data['article_id'] = $articleId;
            } else {
                $data['creation_err'] = "Error Encountered";
            }
        } else {
            $data['status'] = 500;
        }

        echo json_encode($data);
    }

    /**
     * Delete article by article id
     * Verify username
     * Article Delete must delete related aliases [tags, reactions, comments etc] (ArticleModel)
     * 
     * @route true
     * @postParams [username, article_id]
     */
    public function deleteArticle() {
        $username = $_POST['username'];
        $articleId = $_POST['article_id'];

        $data = [];

        if($this->writeModel->isUserArticle($articleId)) {
            if($username !== $_SESSION['username']) $data['article_delete_err'] = "Incorrect Username";

            if(Str::emptyStrings($data)) {
                $data['status'] = 500;

                if($this->writeModel->deleteArticle($articleId)) {
                    Session::alert("alert_article_delete", "Article was deleted successfully");
                    $data['status'] = 200;
                } else {
                    $data['err'] = "Error Occurred";
                }
            }
        }

        echo json_encode($data);
    }

    /**
     * Return list of articles requested asynchronously
     * Format title, tagline, content
     * Format datetypes to correct format
     * Get all articles of id < lastId
     * Default last_id = 0
     * 
     * @route true
     * @param int $lastId - Last article id recieved by user
     */
    public function loadArticles(int $lastId) {
        $data = [];
        $data['articles'] = $this->writeModel->fetchArticles($this->articlesOnPage, $lastId);

        $data['status'] = 500;
        $data['last_id'] = 0;

        foreach($data['articles'] as $article) {
            $data['status'] = 200;
            $data['last_id'] = $article->id;
            $article->title = Str::stripNewLines($article->title);
            $article->tagline = Str::stripNewLines($article->tagline);
            $article->content = Html::getChars($article->content);
            $article->created_at = Str::formatEpoch(strtotime($article->created_at), "d/m H:i");
            $article->last_edited = Str::formatEpoch(strtotime($article->last_updated), "d/m H:i");
        }

        echo json_encode($data);
    }
    
    /**
     * Update article contents
     * Update preview_img based on new content
     * New set of tags, deleting old ones
     * 
     * @route true
     * @postParams [title, tagline, content, article_id, tags]
     */
    public function updateArticle() {
        $title = $_POST['title'];
        $tagline = $_POST['tagline'];
        $content = $_POST['content'];
        $tags = $this->parseTags($_POST['tags']);
        $articleId = $_POST['article_id'];

        $content = Html::purifyHTML($content);

        foreach($tags as $key => $tag) if(Str::isEmptyStr($tag)) unset($tags[$key]);

        $data = [];

        if($this->writeModel->isUserArticle($articleId)) {
            $data = $this->checkArticleErrors([], [
                "title" => $title,
                "tagline" => $tagline,
                "content" => $content,
                "tags" => $tags
            ]);

            if($this->baseModel->checkRateLimitByLastRecord("articles", $this->minArticleUpdateTime, "last_updated", "article_id", $articleId)) {
                $data['limit_err'] = "You are being rate limited";
            }
            
            if(Str::emptyStrings($data)) {
                $data['status'] = 500;

                $this->writeModel->deleteTags($articleId);
                $this->writeModel->insertTags($articleId, $tags);
                $preview = Html::getTagResults($content, "img")[0] ? Html::getTagResults($content, "img")[0]->getAttribute("src") : "";
                
                if($this->writeModel->updateArticle($articleId, $title, $tagline, $content, $preview)) {
                    $data['status'] = 200;
                } else {
                    $data['update_err'] = "Error Encountered";
                }
            }
        }

        echo json_encode($data);
    }

    /**
     * Parse Article Tags [comma(,) seperated]
     * Replace all whitespace
     * Unset empty values
     * 
     * @param string $tags 
     * @return array $tags List of valid tag values
     */
    private function parseTags(string $tags): array {
        $tags = Str::trimWhiteSpaces($tags);
        $tags = explode(",", $tags);
        
        foreach($tags as $key => $tag) if(Str::isEmptyStr($tag)) unset($tags[$key]);

        return $tags;
    }

    /**
     * Check title, tagline, content, iframes, images for errors
     * Content is html purified before call
     * 
     * @param array $errors
     * @param array $draft - Content Values
     * 
     * @return array $errors - List of errors
     */
    private function checkArticleErrors(array $errors, array $draft): array {
        // HTML Purified before calling function
        extract($draft);

        if(Str::trimWhiteSpaces($title) === "")
            $errors['title_err'] = "Please add title";
        else if(mb_strlen($title) > $this->articleLimits["title"]) 
            $errors['title_err'] = "Title must be less than {$this->articleLimits["title"]} characters";
        

        if(mb_strlen($tagline) > $this->articleLimits["tagline"]) 
            $errors['tagline_err'] = "Tagline must be less than {$this->articleLimits["tagline"]} characters";
        

        if(mb_strlen($content) > $this->articleLimits["content"]) 
            $errors['content_err'] = "Content length exceeds {$this->articleLimits["content"]} characters";
        

        if(Html::tagCount($content, "img") > $this->articleLimits["img"]) 
            $errors['content_err'] = "Please minimize the number of images";
        

        if(Html::tagCount($content, "iframe") > $this->articleLimits["iframe"]) 
            $errors['content_err'] = "Please minimize the number of iframes";
        
            
        if(count($tags) > $this->articleLimits["tags"])
            $errors['tags_err'] = "Max of 5 tags are allowed";
        

        foreach($tags as $tag) 
            if(!preg_match($this->tagRegex, $tag)) $errors['tags_err'] = "One or more tags is invalid";

        return $errors;
    }

    /**
     * Save Draft in database
     * Values: title, tagline, content, draft name
     * 
     * @route true
     * @postParams [title, tagline, content, draft_name]
     */
    public function saveDraft() {
        $data = [];

        $title = $_POST['title'];
        $tagline = $_POST['tagline'];
        $content = $_POST['content'];
        $draftName = $_POST['draft_name'];

        $content = Html::purifyHTML($content);
        $draftName = trim($draftName);

        if(mb_strlen($draftName) > $this->maxDraftName || mb_strlen($draftName) < $this->minDraftName || Str::isEmptyStr($draftName)) {
            $data['draft_name_err'] = "Draft Name must be 5-50 characters";
        }

        if($this->baseModel->isRateLimited("drafts", $this->draftsPerHour, 60)
           || $this->baseModel->exceedsMaxRowCount("articles", $this->maxUserDrafts)) {
            $data['limit_err'] = "You are being rate limited";
        }

        $data = $this->checkDraftErrors($data, [
            "title" => $title,
            "tagline" => $tagline,
            "content" => $content,
        ]);


        if(Str::emptyStrings($data)) {
            // Upload Draft
            $draftId = $this->writeModel->createDraft($draftName, $title, $tagline, $content);
            
            if($draftId) {
                $data['status'] = 200;
                $data['draft_id'] = $draftId;
            } else {
                $data['creation_err'] = "Error Encountered";
            }
        } else {
            $data['status'] = 500;
        }

        echo json_encode($data);
    }

    /**
     * Check draft contents for errors
     * title, tagline, content, image count, iframes
     * 
     * @param array $errors - List of errors
     * @param array $draft - Contents of draft
     * 
     * @return array $errors - list of errors in draft
     */
    private function checkDraftErrors(array $errors, array $draft): array {
        // HTML Purified before calling function
        extract($draft);

        if(mb_strlen($title) > $this->draftLimits["title"]) 
            $errors['title_err'] = "Title must be less than {$this->draftLimits["title"]} characters";
        

        if(mb_strlen($tagline) > $this->draftLimits["tagline"]) 
            $errors['tagline_err'] = "Tagline must be less than {$this->draftLimits["tagline"]} characters";
        

        if(mb_strlen($content) > $this->draftLimits["content"]) 
            $errors['content_err'] = "Content length exceeds {$this->draftLimits["content"]} characters";
        

        if(Html::tagCount($content, "img") > $this->draftLimits["img"]) 
            $errors['content_err'] = "Please minimize the number of images";
        

        if(Html::tagCount($content, "iframe") > $this->draftLimits["iframe"]) 
            $errors['content_err'] = "Please minimize the number of iframes";
        

        return $errors;
    }


    /**
     * Update article asynchronously
     * Contents: title, tagline, content
     * Identifier: draft_id
     * 
     * @route true
     * @postParams [title, tagline, content, draft_id]
     */
    public function updateDraft() {
        $title = $_POST['title'];
        $tagline = $_POST['tagline'];
        $content = $_POST['content'];
        $draftId = $_POST['draft_id'];

        $content = Html::purifyHTML($content);

        $data = [];
        $data['status'] = 500;

        if($this->writeModel->isUserDraft($draftId)) {
            $errors = $this->checkDraftErrors([], [
                "title" => $title,
                "tagline" => $tagline,
                "content" => $content,
            ]);

            $data = $errors;
            $data['status'] = 500;
            
            if(Str::emptyStrings($errors)) {
                if($this->baseModel->checkRateLimitByLastRecord("drafts", $this->minDraftUpdateTime, "last_updated", "draft_id", $draftId)) {
                    $data['limit_err'] = "You are being rate limited";
                } else {
                    if($this->writeModel->updateDraft($draftId, $title, $tagline, $content)) {
                        $data['status'] = 200;
                    } else {
                        $data['update_err'] = "Error Encountered";
                    }
                }
            }
        }

        echo json_encode($data);
    }
    
    /**
     * Live load drafts
     * Fetch drafts by model and format contents
     * Format datetime to d/m H:i format
     * 
     * @route true
     * @param int $lastId - Last draft id recieved by user
     */
    public function loadDrafts(int $lastId) {
        $drafts = $this->writeModel->fetchDrafts($this->draftsOnPage, $lastId);

        $data = [];
        $data['status'] = 500;
        $data['last_id'] = 0;

        foreach($drafts as $draft) {
            $data['status'] = 200;
            $data['last_id'] = $draft->id;
            $draft->title = Str::stripNewLines($draft->title);
            $draft->tagline = Str::stripNewLines($draft->tagline);
            $draft->content = Html::getChars($draft->content);
            $draft->created_at = Str::formatEpoch(strtotime($draft->created_at), "d/m H:i");
            $draft->last_edited = Str::formatEpoch(strtotime($draft->last_updated), "d/m H:i");
        }

        $data['drafts'] = $drafts;

        echo json_encode($data);
    }


    /**
     * Rename Draft to new name
     * Validate if name is correct
     * 
     * @route true
     * @postParams [new_name, draft_id]
     */
    public function renameDraft() {
        $newName = $_POST['new_name'];
        $draftId = $_POST['draft_id'];

        $data = [];
        $data['status'] = 500;

        if($this->writeModel->isUserDraft($draftId)) {
            $errors = [];

            if(!(mb_strlen($newName) > $this->minDraftName && mb_strlen($newName) < $this->maxDraftName && !Str::isEmptyStr($newName))) {
                $errors['draft_name_err'] = "Draft Name must be 5-50 characters";
            }

            $data = $errors;
            $data['status'] = 500;

            if(Str::emptyStrings($errors)) {
                if($this->writeModel->renameDraft($draftId, $newName)) {
                    $data['status'] = 200;
                } else {
                    $data['err'] = "Error Occurred";
                }
            }
        }

        echo json_encode($data);
    }

    /**
     * Delete draft of user
     * Check for confirmation username
     * 
     * @route true
     * @postParams [username, draft_id]
     */
    public function deleteDraft() {
        $username = $_POST['username'];
        $draftId = $_POST['draft_id'];

        $data = [];
        $data['status'] = 500;

        if($this->writeModel->isUserDraft($draftId)) {
            $errors = [];

            if($username !== $_SESSION['username']) {
                $errors['draft_delete_err'] = "Incorrect Username";
            }

            $data = $errors;
            $data['status'] = 500;

            if(Str::emptyStrings($errors)) {
                if($this->writeModel->deleteDraft($draftId)) {
                    Session::alert("alert_draft_delete", "Draft was deleted successfully");
                    $data['status'] = 200;
                } else {
                    $data['err'] = "Error Occurred";
                }
            }
        }

        echo json_encode($data);
    }
}