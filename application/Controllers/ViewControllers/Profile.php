<?php 

declare(strict_types = 1); 

class Profile extends Controller {
    private $userModel;
    private $profileModel;
    
    use ProfileTraits;

    public function __childConstruct() {
        $this->userModel = $this->model("UserModel");
        $this->articleModel = $this->model("ArticleModel");
        $this->profileModel = $this->model("ProfileModel");
    }

    /**
     * @route
     */
    public function index() {
        $username = "";

        if (isset($_GET['u'])) {
            $username = $_GET['u'];    
            if(!$this->userModel->ifUsernameExists($username)) Server::die_404();
        } else {
            if(Session::isLoggedIn()) {
                $username = $_SESSION['username'];
            } else {
                Server::die_404();
            }
        }

        $userId = $_SESSION['user_id'] ?? 0;

        $data = [];
        $data['profile_info'] = $this->userModel->getInfoByUsername($username);

        $profileId = $data['profile_info']->id;

        $data['is_following'] = $this->profileModel->isFollowing($userId, $profileId);
        $data['profile_info']->following_count = $this->profileModel->followingCount($profileId);
        $data['profile_info']->followers_count = $this->profileModel->followersCount($profileId);
        $data['articles'] = $this->articleModel->getProfileArticles($profileId, $userId, $this->articlesOnPage);
        $data['last_article_id'] = 0;

        foreach ($data['articles'] as $article) {
            $data['last_article_id'] = $article->article_id;
            $article->content = Html::getChars($article->content);
        }

        $this->view("profile/index", $data);
    }

    /**
     * @route
     */
    public function following(string $username) {
        if(is_null($username)) {
            if(Session::isLoggedIn()) {
                $username = $_SESSION['username'];
            } else {
                Server::die_404();
            }
        }

        $userId = $_SESSION['user_id'] ?? 0;

        if(!$this->userModel->ifUsernameExists($username)) Server::die_404();

        $info = $this->userModel->getInfoByUsername($username);
        $profileId = $info->id;
        
        $data = [];
        $data['profile'] = new stdClass();
        $data['profile']->username = $username;
        $data['profile']->display_name = $info->display_name;
        $data['profile']->uniq_id = $info->uniq_id;

        $data['following'] = $this->profileModel->getProfileFollowing($profileId, $userId, $this->maxFollowingOnPage);
        $data['last_id'] = end($data['following'])->id ?? 0;

        $this->view("profile/following", $data);
    }
    
    /**
     * @route
     */
    public function followers(string $username) {
        if(is_null($username)) {
            if(Session::isLoggedIn()) {
                $username = $_SESSION['username'];
            } else {
                Server::die_404();
            }
        }

        $userId = $_SESSION['user_id'] ?? 0;

        if(!$this->userModel->ifUsernameExists($username)) Server::die_404();

        $info = $this->userModel->getInfoByUsername($username);
        $profileId = $info->id;
        $data = [];
        
        $data['profile'] = new stdClass();
        $data['profile']->username = $username;
        $data['profile']->display_name = $info->display_name;
        $data['profile']->uniq_id = $info->uniq_id;

        $data['followers'] = $this->profileModel->getProfileFollowers($profileId, $userId, $this->maxFollowersOnPage);
        $data['last_id'] = end($data['followers'])->id ?? 0;

        $this->view("profile/followers", $data);
    }
}