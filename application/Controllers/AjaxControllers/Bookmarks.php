<?php 

declare(strict_types = 1); 

class Bookmarks extends ProtectedController {
    private $articleModel;
    
    use BookmarksTraits;
    
    public function __grandchildConstruct(){
        $this->articleModel = $this->model("ArticleModel");
    }
    
    public function index() {}

    /**
     * Asynchronously load bookmarks of user
     * Format contents and date before returning
     * 
     * @param int $lastId - Last bookmark id recieved by user
     * @route true
     */
    public function loadBookmarks(int $lastId) {
        $data = [];
        
        $data['articles'] = $this->articleModel->getUserBookmarks($this->maxBookmarksOnPage, $lastId);
        $data['status'] = 500;
        $data['last_id'] = 0;

        foreach($data['articles'] as $article) {
            $data['status'] = 200;
            $data['last_id'] = $article->id;
            $article->title = Str::stripNewLines($article->title);
            $article->tagline = Str::stripNewLines($article->tagline);
            $article->content = Html::getChars($article->content);
        }

        echo json_encode($data);
    }
}