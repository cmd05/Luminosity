<?php 

declare(strict_types = 1); 

trait WriteTraits {
    protected $draftLimits = [
        'title' => 1000,
        'tagline' => 2500,
        'content' => 150000,
        'draft_name' => 40,
        "img" => 20,
        "iframe" => 8,
    ];

    protected $articleLimits = [
        'title' => 120,
        'tagline' => 600,
        'content' => 100000,
        "img" => 20,
        "iframe" => 5,
        "tags" => 5
    ];

    protected $draftsOnPage = 1;
    protected $articlesOnPage = 1;
    protected $maxImgUpload = 20; // mb
    protected $maxUserArticlesPerHour = 15;
    protected $maxUserArticles = 500;
    protected $minArticleUpdateTime = 10; //seconds
    protected $minDraftUpdateTime = 1; //seconds
    protected $minDraftName = 5;
    protected $maxDraftName = 50;
    protected $tagRegex = "/^[a-z_]{1,12}$/";
    protected $draftsPerHour = 100;
    protected $maxUserDrafts = 500;
}