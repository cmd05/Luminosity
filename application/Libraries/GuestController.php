<?php 

declare(strict_types = 1); 

abstract class GuestController extends Controller {
    abstract public function __grandchildConstruct();

    public function __childConstruct() {
        Session::redirectUser();
        $this->__grandchildConstruct();
    }
    
    abstract public function index();
}