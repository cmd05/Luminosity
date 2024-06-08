<?php 

declare(strict_types = 1); 

class Explore extends Controller {
    private $userModel;
    private $articleModel;
    private $exploreModel;

    public function __childConstruct() {
        $this->userModel = $this->model("UserModel");
        $this->articleModel = $this->model("ArticleModel");
        $this->exploreModel = $this->model("ExploreModel");
    }   
    public function index() {}
    
    use ExploreTraits;

    /**
     * @route true
     * @postParams [query]
     */
    public function loadUserResults(string $lastUniq) {
        $profileId = $this->userModel->getInfoByUniqId($lastUniq);
        
        if(!$profileId) Server::die_404();
        
        $profileId = $profileId->id;
        $userId = $_SESSION['user_id'] ?? 0;

        $data = [];
        $data['users'] = $this->exploreModel->getProfileResults($_POST['query'], $userId, $this->maxResultsOnPage, $profileId);
        $data['last_id']  = end($data['users'])->uniq_id ?? "";
        $data['status'] = count($data['users']) > 0 ? 200 : 500;        

        echo json_encode($data);
    }

    /**
     * @route true
     * @postParams [query]
     */
    public function loadArticleResults(string $lastUniq) {
        $articleId = $this->articleModel->fetchArticle($lastUniq);
        
        if(!$articleId) Server::die_404();
        
        $articleId = $articleId->id; 
        $data = [];
        $data['articles'] = $this->exploreModel->getArticleResults($_POST['query'], $this->maxResultsOnPage, $articleId);
        
        foreach ($data['articles'] as $article) $article->content = Html::getChars($article->content);

        $data['last_id']  = end($data['articles'])->article_id ?? 0;
        $data['status'] = count($data['articles']) > 0 ? 200 : 500;

        echo json_encode($data);
    }
    
    /**
     * @route true
     * @postParams [query]
     */
    public function loadTaggedArticles(string $lastUniq) {
        $articleId = $this->articleModel->fetchArticle($lastUniq);
        
        if(!$articleId) Server::die_404();
        
        $articleId = $articleId->id;
        $data = [];
        $data['articles'] = $this->exploreModel->getTaggedArticles($_POST['query'], $this->maxResultsOnPage, $articleId);
        
        foreach ($data['articles'] as $article) $article->content = Html::getChars($article->content);

        $data['last_id']  = end($data['articles'])->article_id ?? 0;
        $data['status'] = count($data['articles']) > 0 ? 200 : 500;

        echo json_encode($data);
    }

    /**
     * @route true
     */
    public function liveSearch(string $query) {
        $userId = $_SESSION['user_id'] ?? 0;

        $data = [];
        $data['users'] = $this->exploreModel->getProfileResults($query, $userId, $this->maxSearchResults);
        $data['articles'] = $this->exploreModel->getArticleResults($query, $this->maxSearchResults);

        echo json_encode($data);
    }
}