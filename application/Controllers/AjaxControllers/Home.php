<?php 

declare(strict_types = 1); 

class Home extends ProtectedController {
    private $exploreModel;
    private $articleModel;

    public function __grandchildConstruct() {
        $this->exploreModel = $this->model("ExploreModel");
        $this->articleModel = $this->model("ArticleModel");
    }
    public function index() {}
    
    use HomeTraits;

    /**
     * Home Page 
     * Fetch Articles
     * @route true
     */
    public function loadArticles(string $lastId): void {        
        $article = $this->articleModel->fetchArticle($lastId);
        $lastId = $article ? $article->id : 0;

        $data = [];
        $data['articles'] = $this->exploreModel->homeArticles($_SESSION['user_id'], $this->maxArticles, $lastId);
        $data['status'] = 500;

        foreach ($data['articles'] as $article) {
            $data['status'] = 200;
            $article->content = Html::getChars($article->content);
            $article->created_at = date("d M Y", strtotime($article->created_at));
        }

        $data['last_id'] = end($data['articles'])->article_id ?? "0";

        echo json_encode($data);
    }
}