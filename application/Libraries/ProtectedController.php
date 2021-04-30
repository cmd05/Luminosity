<?php 

declare(strict_types = 1); 

abstract class ProtectedController extends Controller {
    abstract public function __grandchildConstruct();

    public function __childConstruct() {
        if(!Session::isLoggedIn()) Server::redirect('user/login');
        $this->__grandchildConstruct();
    }
    
    abstract public function index();
}