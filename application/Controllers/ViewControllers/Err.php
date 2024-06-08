<?php 

declare(strict_types = 1); 

class Err extends Controller {
    public function __childConstruct() {}
    public function index() {}

    /**
     * 404 Not Found error page
     * @route
     */
    public function _404(): void {
        $this->view("errors/404");
    }

    /**
     * 500 - internal server error
     * 
     * @route
     */
    public function _500(): void {
        $this->view("errors/500");
    }
}