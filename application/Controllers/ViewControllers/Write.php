<?php 

declare(strict_types = 1); 

class Write extends ProtectedController {
    private $writeModel;
    private $articleModel;

    use WriteTraits;

    public function __grandchildConstruct() {
        $this->writeModel = $this->model('WriteModel');
        $this->articleModel = $this->model("ArticleModel");
    }

    /**
     * Redirect to new article page
     * 
     * @route
     */
    public function index() {Server::redirect('write/new');}

    /**
     * Show view to create new article / draft
     * 
     * @route
     */
    public function new() {
        $this->view('write/new');
    }

    /**
     * Edit Draft Page
     * Check if draft exists or not
     * Load title, draft name, tagline, content to draft
     * 
     * @param string $draftId
     * @route
     */
    public function draft(string $draftId) {
        $draft = $this->writeModel->fetchDraft($draftId);
        $data = [];

        if($draft) {
            $data['draft_id'] = $draftId;
            $data['title'] = $draft->title;
            $data['draft_name'] = $draft->draft_name;
            $data['tagline'] = $draft->tagline;
            $data['content'] = $draft->content;
            $data['last_updated'] = $draft->last_updated;
            $data['created_at'] = $draft->created_at;
        } else {
            Server::die_404();
        }

        $this->view('write/draft', $data);
    }

    /**
     * @route
     */
    public function editArticle(string $articleId) {
        $article = $this->writeModel->getUserArticle($articleId);
        $data = [];

        if($article) {
            $data['article_id'] = $articleId;
            $data['title'] = $article->title;
            $data['tagline'] = $article->tagline;
            $data['content'] = $article->content;
            $data['last_updated'] = $article->last_updated;
            $data['created_at'] = $article->created_at;
        } else {
            Server::die_404();
        }

        $data['tags'] = $this->articleModel->fetchTags($articleId);
        
        $this->view('write/edit-article', $data);
    }

    /**
     * @route
     */
    public function drafts() {
        $drafts = $this->writeModel->fetchDrafts($this->draftsOnPage);

        $data = [];
        $data['last_draft_id'] = 0;

        foreach($drafts as $draft) {
            $data['last_draft_id'] = $draft->id;
            $draft->tagline = Str::stripNewLines($draft->tagline);
            $draft->content = Html::getChars($draft->content);
        }

        $data['count'] = count($drafts);
        $data['drafts'] = $drafts;

        $this->view("write/drafts", $data);
    }

    /**
     * @route
     */
    public function articles() {
        $articles = $this->writeModel->fetchArticles($this->articlesOnPage);

        $data = [];
        $data['last_article_id'] = 0;

        foreach($articles as $article) {
            $data['last_article_id'] = $article->id;
            $article->tagline = Str::stripNewLines($article->tagline);
            $article->content = Html::getChars($article->content);
        }

        $data['count'] = count($articles);
        $data['articles'] = $articles;
        $this->view("write/articles", $data);
    }
}