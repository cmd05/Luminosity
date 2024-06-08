<?php 

declare(strict_types = 1); 

class Explore extends Controller {  
    private $exploreModel;

    use ExploreTraits;

    public function __childConstruct() {
        $this->exploreModel = $this->model("ExploreModel");
    }

    /**
     * Explore page for articles
     * 
     * @route
     */
    public function index(): void {
        // Fetch articles
        Server::redirect("explore/articles");
    }

    /**
     * @route
     */
    public function articles() {
        $data = [];
        $sort = $_GET['sort_by'] ?? "";

        switch ($sort) {
            case 'most_recent':
                $data['articles'] = $this->exploreModel->getMostRecentArticles($this->maxExploreArticles);
                break;
            case 'most_comments':
                $data['articles'] = $this->exploreModel->getMostCommentedArticles($this->maxExploreArticles);
                break;
            case 'most_reactions':
                $data['articles'] = $this->exploreModel->getMostReactedArticles($this->maxExploreArticles);
                break;
            default:
                $data['articles'] = $this->exploreModel->getMostViewedArticles($this->maxExploreArticles);
                break;
        }

        foreach ($data['articles'] as $article) $article->content = Html::getChars($article->content);
        
        $this->view("explore/articles", $data);
    }

    /**
     * @route
     */
    public function users() {
        $data = [];

        $sort = $_GET['sort_by'] ?? "";
        $userId = $_SESSION['user_id'] ?? 0;

        switch ($sort) {
            case 'views':
                $data['sort'] = "Views";
                $data['users'] = $this->exploreModel->getUsersByViewCount($this->maxExploreUsers, $userId);
                break;
            default:
                $data['sort'] = "Followers";
                $data['users'] = $this->exploreModel->getUsersByFollowCount($this->maxExploreUsers, $userId);
                break;
        }

        $this->view("explore/users", $data);
    }

    /**
     * Search Results Page
     * Possible Parameters:
     * 
     * &q=search_query
     * &type=users|articles|tags
     * &tagged_with=some_tag
     * 
     * Default: Show 5 of all and add show more btn
     * 
     * @route
     */
    public function search() {
        $userId = $_SESSION['user_id'] ?? 0;

        if(isset($_GET['q']) && !Str::isEmptyStr($_GET['q'])) {
            $query = $_GET['q'];
            $type = $_GET['type'] ?? "";

            switch ($type) {
                case 'users':
                    $data['users'] = $this->exploreModel->getProfileResults($query, $userId, $this->maxResultsOnPage);
                    $data['last_result_id']  = end($data['users'])->uniq_id ?? "";
                    $data['query'] = $query;
                    
                    $this->view("explore/search-users", $data);
                    break;
                case "articles":
                    $data['articles'] = $this->exploreModel->getArticleResults($query, $this->maxResultsOnPage);
                    $data['tags'] = $this->exploreModel->getSimilarTags($query, $this->maxSimilarTags);
                    $data['last_article_id']  = end($data['articles'])->article_id ?? "";
                    $data['query'] = $query;

                    foreach ($data['articles'] as $article) {
                        $article->content = Html::getChars($article->content);
                    }

                    $this->view("explore/search-articles", $data);
                    break;
                case "tagged_articles":
                    $data['articles'] = $this->exploreModel->getTaggedArticles($query, $this->maxResultsOnPage);
                    $data['last_article_id']  = end($data['articles'])->article_id ?? "";
                    $data['query'] = $query;

                    foreach ($data['articles'] as $article) {
                        $article->content = Html::getChars($article->content);
                    }

                    $this->view("explore/search-tagged", $data);
                    break;
                default:
                    $data['users'] = $this->exploreModel->getProfileResults($query, $userId, $this->maxResultsIndex);
                    $data['articles'] = $this->exploreModel->getArticleResults($query, $this->maxResultsIndex);
                    $data['query'] = $query;

                    foreach ($data['articles'] as $article) {
                        $article->content = Html::getChars($article->content);
                    }

                    $this->view("explore/search-index", $data);
                    break;
            }
        } else {
            Server::die_404();
        }
    }
}