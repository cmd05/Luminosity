<?php 

declare(strict_types = 1); 

class UserActions extends ProtectedController {
    public function __grandchildConstruct() {}
    public function index() {}

    /**
     * Logout request routes here
     * Destroy session and unset variables
     * Destroy login cookie on browser
     * 
     * Redirect to login page
     * 
     * @route
     */
    public function logout(): void {
        if(Server::checkPostReq(['logout'], true)) {
            session_unset();
            session_destroy();
            Cookie::destroyCookie("login_token");
            Server::redirect('user/login/');
        }   else {
            Server::die_404();
        }
    }
}