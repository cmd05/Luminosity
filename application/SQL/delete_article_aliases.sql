DELIMITER //
CREATE PROCEDURE delete_article_aliases(IN articleID VARCHAR(32))
BEGIN
    DELETE FROM articles WHERE article_id = articleID;
    DELETE FROM article_bookmarks WHERE article_id = articleID;
    DELETE FROM article_comments WHERE article_id = articleID;
    DELETE FROM article_comment_likes WHERE article_id = articleID;
    DELETE FROM article_reactions WHERE article_id = articleID;
    DELETE FROM article_tags WHERE article_id = articleID;
    DELETE FROM article_views WHERE article_id = articleID;
END //
DELIMITER ;