<?php 

declare(strict_types = 1); 

class Home extends ProtectedController {
	public function __grandchildConstruct() {
		$this->exploreModel = $this->model("ExploreModel");
	}

	use HomeTraits;

	/**
	 * Home Page 
	 * Fetch Articles
	 * @route
	 */
	public function index(): void {
		// Fetch articles
		$data = [];
		$data['articles'] = $this->exploreModel->homeArticles($_SESSION['user_id'], $this->maxArticles);

		$data['suggested'] = $this->exploreModel->getMostViewedArticles($this->maxSuggested);
		$data['last_id'] = end($data['articles'])->article_id ?? "0";
		
		ob_start();
		require_once APPROOT."/Views/home/render-articles.php";
		$data['article_renders'] = ob_get_clean();

		$this->view('home/index', $data);
	}
}