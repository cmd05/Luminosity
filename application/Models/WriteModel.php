<?php 

declare(strict_types = 1); 

class WriteModel extends Model {
    public function uploadImage($img)  {
        $content = $img['tmp_name'];
        $type = $img['type'];
        
        $cfile = new CURLFile($content, $type, (string) md5_file($content));
        
        $upload = array(
            "file" => $cfile,
            "upload_preset" => IMG_API_PRESET,
            "cloud_name" => IMG_CLOUD_NAME, 
            "api_key" => IMG_API_KEY, 
            "api_secret" => IMG_API_SECRET, 
            "secure" => true,
        );
    
        $ch = curl_init(IMG_UPLOAD_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $upload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = @curl_exec($ch);

        if (!curl_errno($ch)) {
            $resultStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($resultStatus == 200) {
                $array = json_decode($json, true);
                return $array['secure_url'] ?? false;
            }
        }

        return false;
    }

    public function createDraft(string $draftName, string $title, string $tagline, string $content) {
            $uniq = Utils::randToken(16);
            return $this->db->dbInsert('drafts', [
                "user_id" => $_SESSION['user_id'],
                "draft_id" => $uniq,
                "draft_name" => $draftName,
                "title" => $title,
                "tagline" => $tagline,
                "content" => $content
            ]) ? $uniq : false;
    }

    public function createArticle(string $title, string $tagline, string $content, string $preview) {
        $uniq = Utils::randToken(16);
        return $this->db->dbInsert('articles', [
            "user_id" => $_SESSION['user_id'],
            "article_id" => $uniq,
            "preview_img" => $preview,
            "title" => $title,
            "tagline" => $tagline,
            "content" => $content
        ]) ? $uniq : false;
    }

    public function insertTags(string $articleId, array $tags) {
        foreach ($tags as $tag) {
            $this->db->dbInsert("article_tags", [
                "tag" => $tag,
                "article_id" => $articleId,
                "user_id" => $_SESSION['user_id']
            ]);
        }
    }

    public function isUserArticle(string $articleId): bool {
        $this->db->query("SELECT id from articles WHERE article_id = :article_id AND user_id = :user_id");
        $this->db->bind(":article_id", $articleId);
        $this->db->bind(":user_id", $_SESSION['user_id']);
        $this->db->execute();

        $count = $this->db->rowCount();

        return $count === 1;
    }

    public function deleteTags(string $articleId) {
        $this->db->query("DELETE from article_tags WHERE article_id = :article_id AND user_id = :user_id");
        $this->db->bind(":article_id", $articleId);
        $this->db->bind(":user_id", $_SESSION['user_id']);

        $this->db->execute();
    }

    public function isUserDraft(string $draftId): bool {
        $this->db->query("SELECT id from drafts WHERE draft_id = :draft_id AND user_id = :user_id");
        $this->db->bind(":draft_id", $draftId);
        $this->db->bind(":user_id", $_SESSION['user_id']);
        $this->db->execute();

        $count = $this->db->rowCount();

        return $count === 1;
    }

    public function fetchDraft(string $draftId) {
            // Checked if correct login
            $this->db->query("SELECT * from drafts WHERE draft_id = :draft_id AND user_id = :user_id");
            $this->db->bind(":draft_id", $draftId);
            $this->db->bind(":user_id", $_SESSION['user_id']);

            $row = $this->db->fetchRow();
            if($row) return $row;
            return false;
    }

    public function getUserArticle(string $articleId) {
        // Checked if correct login
        $this->db->query("SELECT * from articles WHERE article_id = :article_id AND user_id = :user_id");
        $this->db->bind(":article_id", $articleId);
        $this->db->bind(":user_id", $_SESSION['user_id']);

        $row = $this->db->fetchRow();
        if($row) return $row;
        return false;
    }

    public function updateDraft(string $draftId, string $title, string $tagline, string $content) {
        $date = date(DB_TIMESTAMP_FMT);
        $this->db->query("UPDATE drafts SET 
                            title = :title,
                            tagline = :tagline,
                            content = :content,
                            last_updated = :last_updated
                          WHERE draft_id = :draft_id
                        ");

        $this->db->bindMultiple([
            "title" => $title,
            "tagline" => $tagline,
            "content" => $content,
            "draft_id" => $draftId,
            "last_updated" => $date
        ]);

        return $this->db->execute();
    }

    public function updateArticle(string $articleId, string $title, string $tagline, string $content, string $preview) {
        $this->db->query("UPDATE articles SET
                            title = :title,
                            tagline = :tagline,
                            content = :content, 
                            last_updated = :last_updated,
                            preview_img = :preview_img
                          WHERE article_id = :article_id
                        ");

        $this->db->bindMultiple([
            "title" => $title,
            "tagline" => $tagline,
            "content" => $content,
            "article_id" => $articleId,
            "last_updated" => date(DB_TIMESTAMP_FMT),
            "preview_img" => $preview
        ]);

        return $this->db->execute();
    }

    public function renameDraft(string $draftId, string $newName) {
        $date = date(DB_TIMESTAMP_FMT);
        $this->db->query("UPDATE drafts SET draft_name = :draft_name, last_updated = :last_updated WHERE draft_id = :draft_id");
        $this->db->bindMultiple([
            "draft_name" => $newName,
            "draft_id" => $draftId,
            "last_updated" => $date
        ]);

        return $this->db->execute();
    }

    public function deleteDraft(string $draftId) {
        $this->db->query("DELETE from drafts WHERE draft_id = :draft_id");
        $this->db->bind(":draft_id", $draftId);

        return $this->db->execute();
    }

    public function deleteArticle(string $articleId) {
        $this->db->query("CALL delete_article_aliases(:article_id)");
        $this->db->bind(":article_id", $articleId);

        return $this->db->execute();
    }

    public function fetchDrafts(int $limit, int $lastId = NULL) {
        $idConstraint = !is_null($lastId) ? " AND id < :id " : " ";
        $this->db->query("SELECT id, draft_id, draft_name, created_at,
                            last_updated, LEFT(title, 100) as title,
                            LEFT(tagline, 200) as tagline, 
                            LEFT(content, 2000) as content
                          FROM drafts WHERE user_id = :user_id $idConstraint
                          ORDER BY id DESC LIMIT $limit
                        ");

                    
                
        $this->db->bind(":user_id", $_SESSION['user_id']);
        if(!is_null($lastId)) $this->db->bind(":id", $lastId);

        $this->db->execute();

        $rows = $this->db->fetchRows();
        return $rows;
    }

    public function fetchArticles(int $limit, int $lastId = NULL) {
        $append = !is_null($lastId) ? " AND id < :id " : " ";
        $this->db->query("SELECT id, article_id, created_at, preview_img,
                          last_updated, LEFT(title, 100) as title,
                          LEFT(tagline, 200) as tagline, 
                          LEFT(content, 2000) as content
                          FROM articles WHERE user_id = :user_id $append
                          ORDER BY id DESC LIMIT $limit
                        ");
                
        $this->db->bind(":user_id", $_SESSION['user_id']);
        if(!is_null($lastId)) $this->db->bind(":id", $lastId);

        $this->db->execute();

        $rows = $this->db->fetchRows();
        return $rows;
    }
}