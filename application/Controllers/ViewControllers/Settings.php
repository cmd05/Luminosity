<?php 

declare(strict_types = 1); 

class Settings extends ProtectedController {
    public function __grandchildConstruct() {
        $this->userModel = $this->model("UserModel");
    }

    /**
     * @route
     */
    public function index() {
        Server::redirect("settings/edit-profile");
    }

    /**
     * Edit profile details
     * Load view
     * 
     * @route
     */
    public function editProfile() {
        $this->view("settings/edit-profile");
    }    
}