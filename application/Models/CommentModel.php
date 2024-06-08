<?php 

declare(strict_types = 1); 

/**
 * All Methods are for logged in users.
 */
class CommentModel extends Model {   
    /**
     * Get number of comments by user on an article
     * 
     * @param string $articleId
     * @return int $rowCount
     */ 
    public function getUserCommentCount(string $articleId): int {
        $this->db->query("SELECT id FROM article_comments WHERE article_id = :article_id AND user_id = :user_id");
        $this->db->bind(":article_id", $articleId);
        $this->db->bind(":user_id", $_SESSION['user_id']);

        $this->db->execute();

        return $this->db->rowCount();
    }

    /**
     * Get all parent comments inside limit
     * Check for author details and user reactions
     * Show user comments first
     */
    public function getParentComments(string $articleId, int $userId, int $limit, int $page = 1) {
        $page = ($page - 1) * $limit;

        $this->db->query("SELECT article_comments.id, article_comments.is_edited,
                            article_comments.content, article_comments.created_at,
                            users.display_name, users.username, users.profile_img,
                            users.id as user_id
                          FROM article_comments
                          INNER JOIN users
                          WHERE article_id = :article_id
                          AND parent_id = 0
                          AND article_comments.user_id = users.id
                          ORDER BY FIELD(user_id, :user_id) DESC, id DESC
                          LIMIT $page, $limit
                         ");

        $this->db->bind(":article_id", $articleId);
        $this->db->bind(":user_id", $userId);

        $this->db->execute();
        $rows = $this->db->fetchRows();
        
        foreach ($rows as $row) {
            $row->like_count = $this->getCommentLikeCount($articleId, $row->id);
            $row->replies = $this->getCommentReplies($articleId, $row->id, $userId);
            $row->reply_count = count($row->replies);
            $row->is_liked = $this->isCommentLiked($articleId, $row->id, $userId);
        }
        
        return $rows;
    }

    /**
     * Check if user has liked the comment or not
     */
    public function isCommentLiked(string $articleId, int $commentId, int $userId): bool {
        $this->db->query("SELECT id
                          FROM article_comment_likes
                          WHERE article_id = :article_id 
                          AND comment_id = :comment_id
                          AND user_id = :user_id
                        ");

        $this->db->bindMultiple([
            "article_id" => $articleId,
            "comment_id" => $commentId,
            "user_id" => $userId
        ]);
        $this->db->execute();

        return $this->db->rowCount() === 1;
    }

    /**
     * Get number of likes on a comment
     * 
     */
    public function getCommentLikeCount(string $articleId, int $commentId): int {
        $this->db->query("SELECT id 
                          FROM article_comment_likes 
                          WHERE article_id = :article_id 
                          AND comment_id = :comment_id
                        ");
        $this->db->bindMultiple([
            "article_id" => $articleId,
            "comment_id" => $commentId
        ]);
        $this->db->execute();

        return $this->db->rowCount();
    }

    /**
     * Get all replies to a comment
     */
    public function getCommentReplies(string $articleId, int $parentId, int $userId) {
        // checked if parent exists
        $this->db->query("SELECT article_comments.id, article_comments.is_edited, 
                          article_comments.content, article_comments.created_at,
                          users.display_name, users.username, 
                          users.profile_img, users.id as user_id
                          FROM article_comments 
                          INNER JOIN users
                          WHERE article_comments.user_id = users.id
                          AND article_id = :article_id
                          AND parent_id = :parent_id
                        ");
                          
        $this->db->bind(":article_id", $articleId);
        $this->db->bind(":parent_id", $parentId);

        $this->db->execute();

        $rows = $this->db->fetchRows();

        foreach ($rows as $row) {
            $row->like_count = $this->getCommentLikeCount($articleId, $row->id);
            $row->is_liked = $this->isCommentLiked($articleId, $row->id, $userId);
        }

        return $rows;
    }

    /**
     * Check if a parent comment exists by ID
     */
    public function parentCommentExists(string $articleId, int $parentId): bool {
        $this->db->query("SELECT id 
                          FROM article_comments
                          WHERE article_id = :article_id
                          AND id = :id
                          AND parent_id = 0
                        ");
        $this->db->bind(":article_id", $articleId);
        $this->db->bind(":id", $parentId);

        $this->db->execute();

        return $this->db->rowCount() ? true : false;
    }

    /**
     * Get total parent comment count on an article
     */
    public function totalParentCommentsCount(string $articleId): int {
        $this->db->query("SELECT id FROM article_comments WHERE article_id = :article_id AND parent_id = 0");
        $this->db->bind(":article_id", $articleId);

        $this->db->execute();

        return $this->db->rowCount();
    }

    /**
     * Get count of all comments and replies on an article
     */
    public function totalCommentsCount(string $articleId): int {
        $this->db->query("SELECT id FROM article_comments WHERE article_id = :article_id");
        $this->db->bind(":article_id", $articleId);

        $this->db->execute();

        return $this->db->rowCount();
    }

    /**
     * Add comment to database
     * Return id if successful
     * Else return false
     */
    public function addComment(string $content, string $articleId, int $parentId) {   
        $insert =  $this->db->dbInsert("article_comments", [
            'parent_id' => $parentId,
            "content" => $content,
            "article_id" => $articleId,
            "user_id" => $_SESSION['user_id']
        ]);

        if($insert) return $this->db->lastInsertId();
        return false;
    }

    /**
     * Check if comment belongs to user
     */
    public function isUserComment(int $commentId): bool {
        $this->db->query("SELECT id from article_comments WHERE id = :id AND user_id = :user_id");
        $this->db->bind(":id", $commentId);
        $this->db->bind(":user_id", $_SESSION['user_id']);
        $this->db->execute();

        return $this->db->rowCount() ? true : false;
    }

    /**
     * Check if comment exists in table
     */
    public function commentExists(int $commentId): bool {
        $this->db->query("SELECT id from article_comments WHERE id = :id");
        $this->db->bind(":id", $commentId);
        $this->db->execute();

        return $this->db->rowCount() ? true : false;
    }

    /**
     * Edit a comment
     */
    public function editComment(int $commentId, string $content) {
        $this->db->query("UPDATE article_comments 
                          SET content = :content,
                            is_edited = 1,
                            created_at = :created_at
                          WHERE id = :comment_id
                        ");

        $this->db->bindMultiple([
            "content" => $content,
            "created_at" => date(DB_TIMESTAMP_FMT),
            "comment_id" => $commentId
        ]);
        
        return $this->db->execute();
    }

    /**
     * Delete a comment
     */
    public function deleteComment(int $commentId): bool {
        $this->db->query("CALL delete_comment_aliases(:comment_id)");
        $this->db->bind(":comment_id", $commentId);
        
        return $this->db->execute();
    }

    /**
     * Insert comment like in table if not exists
     * Else delete the row
     */
    public function toggleCommentLike(int $commentId, string $articleId): bool {
        $this->db->query("SELECT id from article_comment_likes WHERE comment_id = :comment_id AND user_id = :user_id");
        $this->db->bind(":comment_id", $commentId);
        $this->db->bind(":user_id", $_SESSION['user_id']);
        $this->db->execute();

        $count = $this->db->rowCount();

        if($count === 0) {
            return $this->db->dbInsert("article_comment_likes", [
                "user_id" => $_SESSION['user_id'],
                "comment_id" => $commentId,
                "article_id" => $articleId
            ]);
        } else {
            $this->db->query("DELETE from article_comment_likes WHERE comment_id = :comment_id AND user_id = :user_id");
            $this->db->bind(":comment_id", $commentId);
            $this->db->bind(":user_id", $_SESSION['user_id']);

            return $this->db->execute();
        }
    }
}