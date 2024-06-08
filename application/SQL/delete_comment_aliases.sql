DELIMITER //
CREATE PROCEDURE delete_comment_aliases(IN commentID INT)
BEGIN
    -- delete replies
    IF((SELECT parent_id FROM article_comments WHERE id = commentID) = 0) THEN 
        DELETE FROM article_comment_likes WHERE comment_id IN (SELECT id FROM article_comments WHERE parent_id = commentID);
        DELETE FROM article_comments WHERE parent_id = commentID;
    END IF;

    DELETE FROM article_comments WHERE id = commentID;
    DELETE FROM article_comment_likes WHERE comment_id = commentID;
END //
DELIMITER ;