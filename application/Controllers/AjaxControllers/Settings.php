<?php 

declare(strict_types = 1); 

class Settings extends ProtectedController {
    public function __grandchildConstruct() {$this->userModel = $this->model("UserModel");}
    public function index() {}

    /**
     * Update User Profile
     * Reset Session details
     * 
     * @route true
     * @postParams [display_name, username, about]
     */
    public function updateProfile() {
        $display_name = $_POST['display_name'];
        $username = $_POST['username'];
        $about = Str::stripNewLines($_POST['about']);
        
        $data = [];

        if(!Str::isValidUserName($username)) {
            $data['username_err'] = "Invalid Username";
        } else if($this->userModel->ifUsernameExists($username) && $username !== $_SESSION['username']) {
            $data['username_err'] = "Username already in use";
        }

        if(!Str::isValidDisplayName($display_name)) $data['display_name_err'] = "Invalid Display Name";

        if(strlen($about) > 300) $data['about_err'] = "About must be below 300 characters";

        $img = $_FILES['image'] ?? false;
        
        if($img) {
            if(!Image::isValidImg($img, 8)) $data['profile_img_err'] = 'Invalid Image';
        }

        if(Str::emptyStrings($data)) {
            $data['status'] = 500;
            // Default updated image set to current image
            $newImg = $_SESSION['profile_img'];

            if($img) {
                $newName = Utils::randToken().'__'.md5($username);
                $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
                $newPath =  UPLOAD_PATH . $newName .'.'.$ext;

                $unlinkPath = UPLOAD_PATH.$_SESSION['profile_img'];

                if(move_uploaded_file($img['tmp_name'], $newPath)) {
                    // Remove existing profile image (IF NOT DEFAULT)
                    if($_SESSION['profile_img'] !== DEFAULT_PROFILE_NAME) @unlink($unlinkPath);

                    $newImg = $newName.".".$ext;
                } else {
                    $data['profile_img_err'] = 'Error Uploading';
                }
            }

            if(!isset($data['profile_img_err'])) {
                // Update details
                $details = [
                    "display_name" => $display_name,
                    "username" => $username,
                    "about" => $about,
                    "profile_img" => $newImg
                ];

                if($this->userModel->updateProfile($details)) {
                    $data['status'] = 200;
                    Session::sessionSet([
                        "about" => $about,
                        "username" => $username,
                        "display_name" => $display_name,
                        "profile_img" => $newImg
                    ]);
                } else {
                    $data['total_err'] = "Error Occurred";
                }
            }
        } else {
            $data['status'] = 500;
        }

        echo json_encode($data);
    }
}