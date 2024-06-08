<?php 

declare(strict_types = 1); 

/**
 * All Methods are for logged in users.
 */
class Article extends ProtectedController {
    private $articleModel;
    private $commentModel;
    private $writeModel;

    use ArticleTraits;
    
    public function __grandchildConstruct() {
        $this->articleModel = $this->model("ArticleModel");
        $this->commentModel = $this->model("CommentModel");
        $this->writeModel = $this->model("WriteModel");
    }

    public function index() {}

    /**
     * Toggle reactions to articles
     * Reactions must be of type [well-written / confused / interesting]
     * 
     * @route true
     * @postParams [article_id, type]
     */
    public function toggleReaction() {
        $id = $_POST['article_id'];
        $type = $_POST['type'];

        $data = [];
        $validReactions = ['well-written', 'confused', 'interesting'];
        
        if(!in_array($type, $validReactions) || !$this->articleModel->fetchArticle($id)) {
            $data['err'] = "Error Occurred";
        }

        if($this->baseModel->isRateLimited("article_reactions", $this->maxReactionsPerHour, 60)) $data['err'] = "You are being rate limited";
        
        if(Str::emptyStrings($data)) {
            $data['status'] = $this->articleModel->toggleReaction($id, $type) ? 200 : 500;
        }  else {
            $data['status'] = 500;
        }

        echo json_encode($data);
    }

    /**
     * Toggle bookmarks for article
     * Delete Bookmark if already exists
     * Else insert new bookmark
     * 
     * @route true
     * @postParams [article_id]
     */
    public function toggleBookmark() {
        $id = $_POST['article_id'];

        $data = [];
        
        if(!$this->articleModel->fetchArticle($id)) $data['err'] = "Error Occurred";

        $exceedsLimit = $this->baseModel->exceedsMaxRowCount("article_bookmarks", $this->maxBookmarks) &&
                        !$this->articleModel->bookmarkExists($id, $_SESSION['user_id']);

        if($this->baseModel->isRateLimited("article_bookmarks", $this->maxBookmarksPerHour, 60) || $exceedsLimit)
            $data['err'] = "Please try again later";
            
        if(Str::emptyStrings($data)) {
            $data['status'] = $this->articleModel->toggleBookmark($id) ? 200 : 500;
        } else {
            $data['status'] = 500;
        }

        echo json_encode($data);
    }

    /**
     * Add comment for article
     * Content must not be empty
     * And less than 1500 chars
     * 
     * Check For User Spam
     * Check for limit of replies and comments on article
     * Check if parent exists if parent_id != 0 for reply
     * 
     * @param int $parentId
     * @route true
     * @postParams [article_id, content]
     */
    public function addComment(int $parentId) {
        $articleId = $_POST['article_id'];
        $content = Str::strip2lines($_POST['content']);

        $data = [];
        
        // check if content is valid
        if(mb_strlen($content) > $this->maxCommentContent) {
            $data['content_err'] = "Comments cannot be more than 1500 characters";
        } else if(Str::isEmptyStr($content)) {
            $data['content_err'] = "Comment cannot be empty";
        }

        // check details
        if(!$this->articleModel->fetchArticle($articleId)) { // article doesnt exist
            $data['article_err'] = "An error occurred";
        } else if($this->commentModel->getUserCommentCount($articleId) > $this->maxCommentsOnArticleUser ||
                  $this->baseModel->isRateLimited("article_comments", $this->maxCommentsPerHour, 60)) { // comment count of user is more than x or spamming
            $data['comment_err'] = "An error occurred";
        } else if($parentId !== 0 && !$this->commentModel->parentCommentExists($articleId, $parentId)) { // parent comment doesnt exist
            $data['comment_err'] = "An error occurred";
        } else if($parentId !== 0 && 
                  count($this->commentModel->getCommentReplies($articleId, $parentId, $_SESSION['user_id'])) >= $this->maxRepliesOnParent) { // parent exists and replies are full
            $data['comment_err'] = "Sorry, cannot add more replies";
        } else if($parentId === 0 && $this->commentModel->totalCommentsCount($articleId) >= $this->maxCommentsOnArticle) { // comments are full
            $data['comment_err'] = "Sorry, cannot add more comments";
        }

        if(Str::emptyStrings($data)) {
            $data['status'] = 500;
            $id = $this->commentModel->addComment($content, $articleId, $parentId);

            if($id) {
                $parse = [];
                $data['status'] = 200;

                if($parentId === 0) {
                    $parse['content'] = $content;
                    $parse['id'] = $id;

                    ob_start();
                    require_once APPROOT."/Views/article/parse-comment.php";
                    $data['comment'] = ob_get_clean();
                } else {
                    $parse['content'] = $content;
                    $parse['parent_id'] = $parentId;
                    $parse['id'] = $id;
                    
                    ob_start();
                    require_once APPROOT."/Views/article/parse-reply.php";
                    $data['comment'] = ob_get_clean();
                }
            } else {
                $data['comment_err'] = "An error occurred";
            }
        } else {
            $data['status'] = 500;
        }

        echo json_encode($data);
    }

    /**
     * Edit comment
     * Check for content errors
     * Check whether comment belongs to user
     * 
     * Return edited time to UI
     * 
     * @param int $commentId
     * @route true
     * @postParams [content]
     */
    public function editComment(int $commentId) {
        $content = Str::strip2lines($_POST['content']);
        $data = [];

        if(mb_strlen($content) > $this->maxCommentContent) {
            $data['content_err'] = "Comments cannot be more than 1500 characters";
        } else if(Str::isEmptyStr($content)) {
            $data['content_err'] = "Comment cannot be empty";
        } else if(!$this->commentModel->isUserComment($commentId)) {
            $data['err'] = "Error Occurred";
        }

        if(Str::emptyStrings($data)) {
            $data['status'] = $this->commentModel->editComment($commentId, $content) ? 200 : 500;
            $data['new_time'] = Str::formatEpoch(time(), "d/m/y H:i");            
        } else {
            $data['status'] = 500;
        }

        echo json_encode($data);
    }

    /**
     * Toggle likes on comment
     * Insert new like else remove previous like
     * 
     * Check if article and comment exist
     * 
     * @param int $commentId
     * @param string $articleId
     * @route true
     */
    public function toggleCommentLike(int $commentId, string $articleId) {
        $data = [];

        if($this->baseModel->isRateLimited("article_comment_likes", $this->maxCommentLikesPerHour, 60) ||
           !$this->commentModel->commentExists($commentId) ||
           !$this->articleModel->fetchArticle($articleId)) {
            $data['err'] = "Error Occurred";
        }
        
        if(empty($data)) {
            $this->commentModel->toggleCommentLike($commentId, $articleId);
            $data['status'] = 200;
        } else {
            $data['status'] = 500;
        }

        echo json_encode($data);
    }

    /**
     * Delete a comment
     * Check if comment belongs to user
     * 
     * @param int $commentId
     * @route true
     */
    public function deleteComment(int $commentId) {
        $data = [];

        if(!$this->commentModel->isUserComment($commentId)) {
            $data['err'] = "Error Occurred";
            $data['status'] = 500;
        } else {
            $data['status'] = $this->commentModel->deleteComment($commentId) ? 200 : 500;
        }

        echo json_encode($data);
    }
}