<?php 

declare(strict_types = 1); 

class Home extends ProtectedController {
    public function __grandchildConstruct() {
        $this->exploreModel = $this->model("ExploreModel");
    }

    use HomeTraits;

    /**
     * Home Page 
     * Fetch Articles
     * @route
     */
    public function index(): void {
        // Fetch articles
        $data = [];
        $data['articles'] = $this->exploreModel->homeArticles($_SESSION['user_id'], $this->maxArticles);

        foreach ($data['articles'] as $article) {
            $article->content = Html::getChars($article->content);
        }

        $data['suggested'] = $this->exploreModel->getMostViewedArticles($this->maxSuggested);
        $data['last_id'] = end($data['articles'])->article_id ?? "0";
        
        $this->view('home/index', $data);
    }
}