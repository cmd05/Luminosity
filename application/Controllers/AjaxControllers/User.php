<?php 

declare(strict_types = 1); 

class User extends GuestController {
	private $userModel;
	private $mailModel;

	public function __grandchildConstruct() {
		$this->userModel = $this->model('UserModel');
		$this->mailModel = $this->model('MailerModel');
	}
	public function index() {}

	
	/**
	 * First Step of Sign Up Validation
	 * 
	 * @route true
	 * @postParams [email, gender, password, confirm_password]
	 */
	public function signUpCheck1(): void {
		$data = [
			'gender_err' => '',
			'password_err' => '',
			'confirm_password_err' => '',
			'email_err' => '',
			'total_err' => '',
		];
		
		$data = $this->signUpValidate_1($data, $_POST['email'], $_POST['gender'], $_POST['password'], $_POST['confirm_password']);
		$data['status'] = Str::emptyStrings($data) ? 200 : 500;

		echo json_encode($data);
	}

	/**
	 * Second Stage Ajax 
	 * Verify All Credentials (including first step)
	 * Register user and send verification email
	 * 
	 * @route true
	 * @postParams [display_name, username, about, email, gender, password, confirm_password]
	 */
	public function completeSignUp(): void {
		$display_name = $_POST['display_name'];
		$username = $_POST['username'];
		$about = Str::stripNewLines($_POST['about']);
		$email = $_POST['email'];
		$gender = $_POST['gender'];
		$password = $_POST['password'];
		$confirm_password = $_POST['confirm_password'];

		$img = $_FILES['profile_img'] ?? false;

		$data = [];
		$data = $this->signUpValidate_1($data, $email, $gender, $password, $confirm_password);
		$data['display_name_err'] = '';
		$data['username_err'] = '';
		$data['about_err'] = '';
		$data['complete_err'] = '';
		$data['profile_img_err'] = '';

		if(empty($display_name)) 
			$data['display_name_err'] = "Enter display name";
		else if(!Str::isValidDisplayName($display_name))
			$data['display_name_err'] = "Name must be less than 30 Characters";

		if(empty($username))
			$data['username_err'] = "Enter Username";
		else if(!Str::isValidUserName($username))
			$data['username_err'] = "Invalid username";
		else if($this->userModel->ifUsernameExists($username))
			$data['username_err'] = "Username Exists";

		if(strlen($about) > 300)
			$data['about_err'] = "Description Must Be Below 300 characters";

		if($img) {
			if(!Image::isValidImg($img, 8)) $data['profile_img_err'] = 'Invalid Image';
		}

		if(Str::emptyStrings($data)) {
			// Default errors set before success
			$data['status'] = 500;
			$data['complete_err'] = 'Something Went Wrong';

			$fileName = DEFAULT_PROFILE_NAME;
			$data['img_upload_err'] = '';

			if($img) {
				$tmpName = Utils::randToken().'__'.md5($username);
				$ext = pathinfo($img['name'], PATHINFO_EXTENSION);
				$tmpPath =  UPLOAD_PATH . $tmpName .'.'.$ext;
				if(move_uploaded_file($img['tmp_name'], $tmpPath))
					$fileName = $tmpName.'.'.$ext;
				else
					$data['img_upload_err'] = 'Something Went Wrong';
			}

			$user = array(
				'email' => $email,
				'display_name' => $display_name,
				'password' => password_hash($password, PASSWORD_DEFAULT),
				'about' => $about,
				'profile_img' => $fileName,
				'username' => $username,
				"uniq_id" => Utils::randToken(16)
			);

			if(empty($data['img_upload_err']) && $this->userModel->addUser($user)) {
				// Send verification email
				$newToken = Utils::randToken();
				$linkTag = "<a href='".URLROOT."/user/verify-email/$newToken"."'>Verify</a>";
				$body = "Your Verification Link is: $linkTag. Please ignore if this wasn't you.";
				$mailStatus = $this->mailModel->sendMail($email, 'Account Verification Link', $body);

				if($this->userModel->insertNewEmailToken($email, $newToken) && $mailStatus) {
					Session::flash('register_success', 'Success! Check your email and spam folder to verify your account');
					$this->userModel->deleteOldEmailTokens($email, $newToken);
					$data['status'] = 200;
				}
			}
		}   else {
			$data['status'] = 500;
		}

		echo json_encode($data);
	}

	/**
	 * First Step sign up validation
	 * 
	 * Check email, gender, password, confirmPassword
	 * 
	 * @param array $data
	 * @param string $email
	 * @param string $gender
	 * @param string $password
	 * @param string $confirmPassword
	 * 
	 * @return array $data [list of errors]
	 */
	private function signUpValidate_1(array $data, string $email, string $gender,
									  string $password, string $confirmPassword): array {
		if(empty($email)) {
			$data['email_err'] = 'Enter Email';
		} else if(!Str::isValidEmail($email)) {
			$data['email_err'] = 'Enter Valid Email Address';
		} else if($this->userModel->ifEmailExists($email)) {
			$data['email_err'] = 'Email is already in use';
		} else if(!$this->userModel->isSecureMail($email)) {
			$data['email_err'] = 'Could Not Verify Email';
		}

		if(empty($gender) || !in_array($gender, ['male', 'female', 'other'])) {
			$data["gender_err"] = "Select Gender";
		}

		if(!Str::isValidPassword($password)) {
			$data['password_err'] = "Enter Valid Password";
		}

		if($password !== $confirmPassword) {
			$data['confirm_password_err'] = "Passwords do not match";
		}

		return $data;
	}
}