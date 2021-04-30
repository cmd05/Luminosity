<?php

class Info extends Controller {
    public function __childConstruct() {}

    /**
     * Landing main page of website
     * Redirected user to home page instead of landing page
     * 
     * @route
     */
    public function index(): void {
        Session::redirectUser(); 
        $this->view('info/index');
    }
    
    /**
     * @route
     */
    public function contribute(): void {
        $this->view('info/contribute');
    }

    /**
     * @route
     */
    public function privacy(): void {
        $this->view('info/privacy');
    }

    /**
     * @route
     */
    public function api() {
        $this->view("info/api");
    }
}