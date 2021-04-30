<?php 

declare(strict_types = 1); 

trait ArticleTraits {
    protected $maxReactionsPerHour = 200;
    protected $maxBookmarksPerHour = 50;
    protected $maxBookmarks = 500;
    protected $maxCommentsOnArticleUser = 80;
    protected $maxCommentsPerHour = 30;
    protected $maxRepliesOnParent = 80;
    protected $maxCommentsOnArticle = 500;

    protected $maxCommentLikesPerHour = 500;
    protected $maxCommentsOnPage = 10;
    protected $maxCommentContent = 1500;
}