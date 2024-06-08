<?php 

declare(strict_types = 1); 

class Bookmarks extends ProtectedController {
    private $articleModel;
    
    use BookmarksTraits;
    
    public function __grandchildConstruct() {
        $this->articleModel = $this->model("ArticleModel");
    }
    
    /**
     * Show first x bookmarks of user
     * Return last_article_id for ajax live loading
     * Return bookmarked articles as array
     * 
     * @route
     */
    public function index() {
        $bookmarks = $this->articleModel->getUserBookmarks($this->maxBookmarksOnPage);

        $data = [];
        $data['last_article_id'] = 0;

        foreach($bookmarks as $article) {
            $data['last_article_id'] = $article->id;
            $article->title = Str::stripNewLines($article->title);
            $article->tagline = Str::stripNewLines($article->tagline);
            $article->content = Html::getChars($article->content);
        }

        $data['count'] = count($bookmarks);
        $data['articles'] = $bookmarks;

        $this->view("bookmarks/index", $data);
    }
}