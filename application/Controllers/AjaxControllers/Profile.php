<?php 

declare(strict_types = 1); 

class Profile extends Controller {
    private $userModel;
    private $articleModel;
    private $profileModel;

    use ProfileTraits;

    public function __childConstruct() {
        $this->userModel = $this->model("UserModel");
        $this->articleModel = $this->model("ArticleModel");
        $this->profileModel = $this->model("ProfileModel");
    }
    public function index() {}

    /**
     * @route true
     * @postParams [profile_uniq]
     */
    public function loadProfileArticles(string $lastArticleId) {
        $article = $this->articleModel->fetchArticle($lastArticleId);
        
        $userId = $_SESSION['user_id'] ?? 0;
        $lastId = $article ? $article->id : 0;
        $profileId = $this->userModel->getInfoByUniqId($_POST['profile_uniq'])->id ?? 0;

        $data = [];
        $data['articles'] = $this->articleModel->getProfileArticles($profileId, $userId, $this->articlesOnPage, $lastId);
        $data['status'] = 500;

        foreach ($data['articles'] as $article) {
            $data['status'] = 200;
            $data['last_id'] = $article->article_id;
            $article->content = Html::getChars($article->content);
            $article->created_at = date("d M Y", strtotime($article->created_at));
        }

        echo json_encode($data);
    }

    /**
     * @route true
     * @postParams [profile_uniq]
     */
    public function toggleFollow() {
        if(!Session::isLoggedIn()) Server::die_404();

        $profileId = $this->userModel->getInfoByUniqId($_POST['profile_uniq'])->id;
        if(is_null($profileId)) Server::die_404();

        $data = [];
        $data['status'] = 500;

        $exceedsLimit = $this->profileModel->followingCount($_SESSION['user_id']) > $this->maxFollowing 
                        && !$this->profileModel->isFollowing($_SESSION['user_id'], $profileId);

        if($exceedsLimit || $this->baseModel->isRateLimited("followers", $this->maxFollowingPerHour, 60, "follower_id") 
           || $_SESSION['user_id'] === $profileId) {
            $data['err'] = "An Error Occurred";
        } else if($this->profileModel->toggleFollow($_SESSION['user_id'], $profileId)) {
            $data['status'] = 200;
        } else {
            $data['err'] = "An Error Occurred";
        }

        echo json_encode($data);
    }

    /**
     * @route true
     * @postParams [profile_uniq]
     */
    public function loadFollowing(int $lastId) {
        $uniq = $_POST['profile_uniq'];
        $profileId = $this->userModel->getInfoByUniqId($uniq);

        if(!$profileId) Server::die_404();
        
        $profileId = $profileId->id;
        $userId = $_SESSION['user_id'] ?? 0;

        $data = [];
        $data['following'] = $this->profileModel->getProfileFollowing($profileId, $userId, $this->maxFollowingOnPage, $lastId);
        $data['last_id'] = end($data['following'])->id ?? 0;
        $data['status'] = count($data['following']) > 0 ? 200 : 500;

        echo json_encode($data);
    }

    /**
     * @route true
     * @postParams [profile_uniq]
     */
    public function loadFollowers(int $lastId) {
        $profileId = $this->userModel->getInfoByUniqId($_POST['profile_uniq']);
        if(!$profileId) Server::die_404();
        
        $profileId = $profileId->id;
        $userId = $_SESSION['user_id'] ?? 0;

        $data = [];
        $data['followers'] = $this->profileModel->getProfileFollowers($profileId, $userId, $this->maxFollowersOnPage, $lastId);
        $data['last_id'] = end($data['followers'])->id ?? 0;
        $data['status'] = count($data['followers']) > 0 ? 200 : 500;

        echo json_encode($data);
    }
}