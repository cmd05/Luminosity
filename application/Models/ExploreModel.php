<?php 

declare(strict_types = 1); 

class ExploreModel extends Model {
	public function __construct() {
		require_once "CommentModel.php";
		require_once "ArticleModel.php";
		require_once "UserModel.php";
		require_once "ProfileModel.php";

		$this->db = new Database;
		$this->_profileModel = new ProfileModel;
		$this->_articleModel = new ArticleModel;
		$this->_userModel = new UserModel;
		$this->commentModel = new CommentModel;
	}

	public function getProfileResults(string $query, int $userId, int $limit, int $lastId = NULL) {
		$idConstraint = !is_null($lastId) ? " AND id < :id " : " ";
		
		$this->db->query("SELECT users.username, users.uniq_id,
							users.display_name, users.profile_img, users.about
						  FROM users
						  WHERE (username LIKE :query_u OR display_name LIKE :query_d) 
						  $idConstraint
						  ORDER BY id DESC 
						  LIMIT $limit
						 ");

		$this->db->bind(":query_u", "%$query%");
		$this->db->bind(":query_d", "%$query%");

		if(!is_null($lastId)) $this->db->bind(":id", $lastId);

		$this->db->execute();
		
		$rows = $this->db->fetchRows();
		
		foreach($rows as $row) {
			$followedProfileId = $this->_userModel->getInfoByUniqId($row->uniq_id)->id;
			$row->show_btn = $userId !== $followedProfileId;
			$row->is_following = $this->_profileModel->isFollowing($userId, $followedProfileId);
		}

		return $rows;
	}

	public function getArticleResults(string $query, int $limit, int $lastId = NULL) {
		$idConstraint = !is_null($lastId) ? " AND articles.id < :id " : " ";
		
		$this->db->query("SELECT DISTINCT articles.article_id, articles.title, articles.tagline,
							articles.content, articles.created_at, articles.preview_img
						  FROM articles
						  WHERE (articles.title LIKE :query_1
						  OR articles.tagline LIKE :query_2
						  OR LEFT(articles.content, 5000) LIKE :query_3)
						  $idConstraint
						  ORDER BY articles.id DESC LIMIT $limit
						 ");

		$this->db->bind(":query_1", "%$query%");
		$this->db->bind(":query_2", "%$query%");
		$this->db->bind(":query_3", "%$query%");
		// $this->db->bind(":query_4", "$query");

		if(!is_null($lastId)) $this->db->bind(":id", $lastId);

		$this->db->execute();
		
		$rows = $this->db->fetchRows();
		
		foreach($rows as $row) {
			$row->view_count = $this->_articleModel->getViews($row->article_id);
			$row->comments_count = $this->commentModel->totalCommentsCount($row->article_id);
		}

		usort($rows, fn($a, $b) => $a->view_count < $b->view_count);

		return $rows;
	}

	public function getSimilarTags(string $query, int $limit) {
		$this->db->query("SELECT DISTINCT tag
						  FROM article_tags
						  WHERE tag LIKE :query
						  GROUP BY tag
						  ORDER BY COUNT(*) DESC
						  LIMIT $limit
						");

		$this->db->bind(":query", "%$query%");
		$this->db->execute();

		return $this->db->fetchRows();
	}

	public function getTaggedArticles(string $query, int $limit, int $lastId = NULL) {
		$idConstraint = !is_null($lastId) ? " AND articles.id < :id " : " ";
		
		$this->db->query("SELECT DISTINCT articles.article_id, articles.title, articles.tagline,
							articles.content, articles.created_at, articles.preview_img
						  FROM articles
						  INNER JOIN article_tags
						  WHERE article_tags.tag = :query
						  AND article_tags.article_id = articles.article_id
						  $idConstraint
						  ORDER BY articles.id DESC LIMIT $limit
						 ");

		$this->db->bind(":query", "$query");
		if(!is_null($lastId)) $this->db->bind(":id", $lastId);

		$this->db->execute();
		
		$rows = $this->db->fetchRows();
		
		foreach($rows as $row) {
			$row->view_count = $this->_articleModel->getViews($row->article_id);
			$row->comments_count = $this->commentModel->totalCommentsCount($row->article_id);
		}

		// sort by view count
		usort($rows, fn($a, $b) => $a->view_count < $b->view_count);

		return $rows;
	}

	public function getMostViewedArticles(int $limit) {
		$this->db->query("SELECT articles.title, articles.tagline, articles.preview_img,
						  articles.content, articles.created_at, articles.article_id
						  FROM articles
						  INNER JOIN article_views
						  WHERE articles.article_id = article_views.article_id
						  GROUP BY article_views.article_id
						  ORDER BY COUNT(*) DESC
						  LIMIT $limit
						");
		$this->db->execute();

		$rows = $this->db->fetchRows();
		
		foreach ($rows as $row) {
			$row->view_count = $this->_articleModel->getViews($row->article_id);
			$row->comments_count = $this->commentModel->totalCommentsCount($row->article_id);
		}

		return $rows;
	}

	public function getMostRecentArticles(int $limit) {
		$this->db->query("SELECT articles.title, articles.tagline, articles.preview_img,
						  articles.content, articles.created_at, articles.article_id
						  FROM articles
						  ORDER BY id DESC
						  LIMIT $limit
						");
		$this->db->execute();

		$rows = $this->db->fetchRows();
		
		foreach ($rows as $row) {
			$row->view_count = $this->_articleModel->getViews($row->article_id);
			$row->comments_count = $this->commentModel->totalCommentsCount($row->article_id);
		}

		return $rows;
	}

	public function getMostCommentedArticles(int $limit) {
		$this->db->query("SELECT articles.title, articles.tagline, articles.preview_img,
							articles.content, articles.created_at, articles.article_id
						  FROM articles
						  INNER JOIN article_comments
						  WHERE articles.article_id = article_comments.article_id
						  GROUP BY article_comments.article_id
						  ORDER BY COUNT(*) DESC
						  LIMIT $limit
						");
		$this->db->execute();

		$rows = $this->db->fetchRows();
		
		foreach ($rows as $row) {
			$row->view_count = $this->_articleModel->getViews($row->article_id);
			$row->comments_count = $this->commentModel->totalCommentsCount($row->article_id);
		}

		return $rows;
	}

	public function getMostReactedArticles(int $limit) {
		$this->db->query("SELECT articles.title, articles.tagline, articles.preview_img,
							articles.content, articles.created_at, articles.article_id
						  FROM articles
						  INNER JOIN article_reactions
						  WHERE articles.article_id = article_reactions.article_id
						  GROUP BY article_reactions.article_id
						  ORDER BY COUNT(*) DESC
						  LIMIT $limit
						");
		$this->db->execute();

		$rows = $this->db->fetchRows();
		
		foreach ($rows as $row) {
			$row->view_count = $this->_articleModel->getViews($row->article_id);
			$row->comments_count = $this->commentModel->totalCommentsCount($row->article_id);
		}

		return $rows;
	}

	public function getUsersByViewCount(int $limit, int $userId) {
		$this->db->query("SELECT DISTINCT users.username, users.display_name, users.about,
							users.profile_img, users.uniq_id
						  FROM users
						  INNER JOIN article_views
						  WHERE users.id = (SELECT articles.user_id FROM articles WHERE articles.article_id = article_views.article_id)
						  GROUP BY article_views.article_id
						  ORDER BY COUNT(*) DESC
						  LIMIT $limit
						");
		$this->db->execute();

		$rows = $this->db->fetchRows();

		foreach($rows as $row) {
			$followedProfileId = $this->_userModel->getInfoByUniqId($row->uniq_id)->id;
			$row->show_btn = $userId !== $followedProfileId;
			$row->is_following = $this->_profileModel->isFollowing($userId, $followedProfileId);
		}

		return $rows;        
	}

	public function getUsersByFollowCount(int $limit, int $userId) {
		$this->db->query("SELECT DISTINCT users.username, users.display_name, users.about,
							  users.profile_img, users.uniq_id
						  FROM users
						  INNER JOIN followers
						  WHERE users.id = followers.profile_id
						  GROUP BY followers.profile_id
						  ORDER BY COUNT(*) DESC
						  LIMIT $limit
						");
		$this->db->execute();

		$rows = $this->db->fetchRows();

		foreach($rows as $row) {
			$followedProfileId = $this->_userModel->getInfoByUniqId($row->uniq_id)->id;
			$row->show_btn = $userId !== $followedProfileId;
			$row->is_following = $this->_profileModel->isFollowing($userId, $followedProfileId);
			$row->followers_count = $this->_profileModel->followersCount($followedProfileId);
		}

		return $rows;        
	}

	public function getMostRecentUsers(int $limit, int $userId) {
		$this->db->query("SELECT DISTINCT users.username, users.display_name, users.about,
						  users.profile_img, users.uniq_id
						  FROM users
						  ORDER BY id DESC
						  LIMIT $limit
						");
		$this->db->execute();

		$rows = $this->db->fetchRows();

		foreach($rows as $row) {
			$followedProfileId = $this->_userModel->getInfoByUniqId($row->uniq_id)->id;
			$row->show_btn = $userId !== $followedProfileId;
			$row->is_following = $this->_profileModel->isFollowing($userId, $followedProfileId);
			$row->followers_count = $this->_profileModel->followersCount($followedProfileId);
		}

		return $rows;
	}

	public function homeArticles(int $userId, int $limit, int $lastId = NULL) {
		$idConstraint = !is_null($lastId) ? " AND articles.id < :id " : " ";
		
		$this->db->query("SELECT DISTINCT articles.preview_img, articles.title, articles.tagline,
						  articles.content,
						  articles.article_id, articles.created_at, articles.user_id
						  FROM articles 
						  INNER JOIN followers
						  WHERE articles.user_id IN (SELECT profile_id FROM followers WHERE follower_id = :user_id)
						  $idConstraint
						  ORDER BY articles.id DESC LIMIT $limit
						");

		$this->db->bind(":user_id", $userId);
		if(!is_null($lastId)) $this->db->bind(":id", $lastId);

		$this->db->execute();
		
		$rows = $this->db->fetchRows();

		foreach($rows as $row) {
			$rowId = $row->user_id;
			$info = $this->_userModel->getInfoById($rowId);
			unset($row->user_id);
			
			$row->username = $info->username;
			$row->display_name = $info->display_name;
			$row->profile_img = $info->profile_img;
			
			$row->view_count = $this->_articleModel->getViews($row->article_id);
			$row->is_bookmarked = $this->_articleModel->bookmarkExists($row->article_id, $_SESSION['user_id']);
		}

		return $rows;
	}
}