<?php 

declare(strict_types = 1); 

class Article extends Controller {
    /* Maximum number of comments per page */
    use ArticleTraits;

    private $articleModel;
    private $userModel;
    private $commentModel;
    
    /**
     * Load models in controller
     */
    public function __childConstruct() {
        $this->articleModel = $this->model("ArticleModel");
        $this->commentModel = $this->model("CommentModel");
        $this->userModel = $this->model("UserModel");
    }

    /**
     * Load article view
     * Article ID passed as get parameter
     * 
     * Check if article exists
     * Add View if user is logged in
     * If logged in check whether article is bookmarked 
     * Get view count
     * Fetch Article tags
     * Fetch reaction counts
     * Fetch reactions added by user
     * Show reactions and likes of logged in user
     * 
     * @route
     */
    public function index() {
        if(!isset($_GET['a'])) Server::die_404();

        $articleId = $_GET['a'];
        $article = $this->articleModel->fetchArticle($articleId);
        
        if(!$article) Server::die_404();

        if(Session::isLoggedIn()) $this->articleModel->addView($articleId);
        
        $userId = $_SESSION['user_id'] ?? 0;

        $data = [];        
        $data['is_bookmarked'] = false;        
        if(Session::isLoggedIn()) $data['is_bookmarked'] = $this->articleModel->bookmarkExists($articleId, $userId);
        $data['reactions_count'] = $this->articleModel->fetchReactionsCount($articleId);
        if(Session::isLoggedIn()) $data['user_reactions'] = $this->articleModel->userReactions($articleId);
        
        $data['comments'] = $this->commentModel->getParentComments($articleId, $userId, $this->maxCommentsOnPage);
        $data['total_comments'] = $this->commentModel->totalParentCommentsCount($articleId);
        $data['comment_page_count'] = ceil($data['total_comments'] / $this->maxCommentsOnPage);
        $data['current_page'] = 1;
        
        $data['views'] = $this->articleModel->getViews($articleId);
        $data['article'] = $article;
        $data['user'] = $this->userModel->getInfoById($article->user_id);
        $data['tags'] = $this->articleModel->fetchTags($articleId);
        
        $this->view("article/index", $data);
    }

    /**
     * Show comments for article limited to max per page
     * Accessed by article id and page
     * Get article_id and page number
     * Exit if page === 1, page 1 is shown on index page
     * Exit if comment count is 0
     * Show pagination and view
     * 
     * @param string $articleId
     * @param int $page
     * @route
     */
    public function comments(string $articleId, int $page) {
        $article = $this->articleModel->fetchArticle($articleId);
        
        if(!$article || $page === 1) Server::die_404();
        
        $data['article'] = $article;
        $userId = $_SESSION['user_id'] ?? 0;
        $data['comments'] = $this->commentModel->getParentComments($articleId, $userId, $this->maxCommentsOnPage, $page);
        
        if(count($data['comments']) === 0) Server::die_404();
        
        $data['current_page'] = $page;
        $data['total_comments'] = $this->commentModel->totalParentCommentsCount($articleId);
        $data['comment_page_count'] = ceil($data['total_comments'] / $this->maxCommentsOnPage);

        $this->view("article/comments", $data);
    }
}