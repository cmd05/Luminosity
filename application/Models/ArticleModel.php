<?php 

declare(strict_types = 1); 

class ArticleModel extends Model {
    public function fetchArticle(string $articleId) {
        $this->db->query("SELECT * from articles WHERE article_id = :article_id");
        $this->db->bind(":article_id", $articleId);
        $this->db->execute();

        $row = $this->db->fetchRow();

        if($row) return $row;
        return false;
    }

    public function bookmarkExists(string $articleId, int $userId): bool {
        $this->db->query("SELECT id from article_bookmarks WHERE article_id = :article_id AND user_id = :user_id");
		$this->db->bind(":article_id", $articleId);
		$this->db->bind(":user_id", $userId);
		$this->db->execute();

		return $this->db->rowCount() === 1;
    }

    public function getUserBookmarks(int $limit, int $lastId = NULL) {
        $idConstraint = !is_null($lastId) ? " AND article_bookmarks.id < :id " : " ";
        
        $this->db->query("  SELECT articles.article_id, articles.preview_img,
                            article_bookmarks.id as id,
                            LEFT(articles.title, 100) as title,
                            LEFT(articles.tagline, 200) as tagline, 
                            LEFT(articles.content, 2000) as content
                            FROM articles
                            INNER JOIN article_bookmarks
                            WHERE articles.article_id = article_bookmarks.article_id
                            AND article_bookmarks.user_id = :user_id
                            $idConstraint
                            ORDER BY article_bookmarks.id DESC LIMIT $limit
                        ");
                
        $this->db->bind(":user_id", $_SESSION['user_id']);
        if(!is_null($lastId)) $this->db->bind(":id", $lastId);

        $this->db->execute();

        $rows = $this->db->fetchRows();
        return $rows;
    }

	public function getViews(string $articleId): int {
		$this->db->query("SELECT id from article_views WHERE article_id = :article_id");
		$this->db->bind(":article_id", $articleId);
		$this->db->execute();

		return $this->db->rowCount();
	}

    public function addView(string $articleId) {
        // Try Catch on unique constraint b/w article_id and user_id of table
		try {
			$this->db->dbInsert("article_views", [
				"user_id" => $_SESSION['user_id'],
				"article_id" => $articleId
			]);
		} catch (Exception $e) {}		
    }


    /**
     * After validating article exists
     */
    public function fetchTags(string $articleId) {
        $this->db->query("SELECT * from article_tags WHERE article_id = :article_id");
        $this->db->bind(":article_id", $articleId);
        $this->db->execute();

        $rows = $this->db->fetchRows();
        return $rows;
    }

    /**
     * Get reaction counts of article
     */
    public function fetchReactionsCount(string $articleId): array {
        $this->db->query("SELECT type from article_reactions WHERE article_id = :article_id");
        $this->db->bind(":article_id", $articleId);
        $this->db->execute();

        $rows = $this->db->fetchRows();

        $indexes = [
            "well-written" => 0,
            "interesting" => 1,
            "confused" => 2
        ];

        $counts = [0, 0, 0];

        foreach($rows as $row) $counts[$indexes[$row->type]]++;

        return $counts;
    }

    public function userReactions(string $articleId): array {
        $this->db->query("SELECT type from article_reactions WHERE article_id = :article_id AND user_id = :user_id");
        $this->db->bind(":article_id", $articleId);
        $this->db->bind(":user_id", $_SESSION['user_id']);

        $this->db->execute();
        $rows = $this->db->fetchRows();

        $reactions = [];

        foreach($rows as $row) $reactions[$row->type] = true;

        return $reactions;       
    }

    public function toggleReaction(string $articleId, string $type): bool {
        if(!in_array($type, array_keys($this->userReactions($articleId)))) {
            return $this->db->dbInsert("article_reactions", [
                "user_id" => $_SESSION['user_id'],
                "type" => $type,
                "article_id" => $articleId
            ]);
        } else {
            $this->db->query("DELETE
                              FROM article_reactions
                              WHERE article_id = :article_id
                              AND user_id = :user_id
                              AND type = :type
                            ");
            $this->db->bind(":article_id", $articleId);
            $this->db->bind(":user_id", $_SESSION['user_id']);
            $this->db->bind(":type", $type);

            return $this->db->execute();
        }
    }


    public function toggleBookmark(string $articleId) {
        if(!$this->bookmarkExists($articleId, $_SESSION['user_id'])) {
            return $this->db->dbInsert("article_bookmarks", [
                "user_id" => $_SESSION['user_id'],
                "article_id" => $articleId
            ]);
        } else {
            $this->db->query("DELETE from article_bookmarks WHERE article_id = :article_id AND user_id = :user_id");
            $this->db->bind(":article_id", $articleId);
            $this->db->bind(":user_id", $_SESSION['user_id']);

            return $this->db->execute();
        }
    }

    public function getProfileArticles(int $profileId, int $userId, int $limit, int $lastId = NULL) {
        $idConstraint = !is_null($lastId) ? " AND id < :id " : " ";
        
        $this->db->query("SELECT preview_img, title, tagline, article_id,
                          created_at,
                          content
                          FROM articles WHERE
                          user_id = :profile_id
                          $idConstraint
                          ORDER BY id DESC LIMIT $limit
                         ");

        $this->db->bind(":profile_id", $profileId);
        if(!is_null($lastId)) $this->db->bind(":id", $lastId);

        $this->db->execute();
        
        $rows = $this->db->fetchRows();

        foreach($rows as $row) {
            $row->view_count = $this->getViews($row->article_id);
            $row->is_bookmarked = $this->bookmarkExists($row->article_id, $userId);
        }

        return $rows;
    }
}