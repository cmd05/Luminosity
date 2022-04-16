<?php

declare(strict_types = 1);

class User extends GuestController {
	private $userModel;
	private $mailModel;

	public function __grandchildConstruct() {
		$this->userModel = $this->model('UserModel');
		$this->mailModel = $this->model('MailerModel');
	}

	/**
	 * @route
	 */
	public function index(): void {
		Server::redirect("user/login");
	}

	/**
	 * Login view for guest user
	 * verify email/username and password
	 * Check if account has been verified by email 
	 * Set login cookie for browser
	 * Set session and redirect to home
	 * 
	 * @route
	 */
	public function login(): void {
		$data = [
			'email_or_username_err' => '',
			'password_err' => '',
			'email_or_username' => '',
			'password' => ''
		];

		if(Server::checkPostReq(["email_or_username", "password"])) {
			if(!ALLOW_LOGIN) {
				$data['password_err'] = "Login has been disabled";
				$this->view('user/login', $data);
				return;
			}
			
			$data['email_or_username'] = $_POST['email_or_username'];
			$data['password'] = $_POST['password'];

			if(empty($data['email_or_username'])) {
				$data['email_or_username_err'] = "Please Fill Login Details";
			}   else if(!$this->userModel->ifUsernameExists($data['email_or_username']) &&
						!$this->userModel->ifEmailExists($data['email_or_username'])) {
				$data['email_or_username_err'] = "User not Found";
			}

			if(empty($data['password'])) $data['password_err'] = "Please Enter Password";

			if(Str::emptyStrings([$data['email_or_username_err'], $data['password_err']])) {

				if($this->userModel->isVerifiedUser($data['email_or_username'])) {
					$verify = $this->userModel->verifyUser($data['email_or_username'], $data['password']);

					if(!$verify) {
						$data['password_err'] = "Incorrect Password";
					}  else {
						// Correct Login
						// Set Session
						Session::sessionSet([
							"username" => $verify->username,
							"uniq_id" => $verify->uniq_id,
							"display_name" => $verify->display_name,
							"user_id" => $verify->id,
							"about" => $verify->about,
							"email" => $verify->email,
							"display_name" => $verify->display_name,
							'profile_img' => $verify->profile_img
						]);
						
						$loginToken = Utils::randToken();
						Cookie::createCookie('login_token', $loginToken, 30*24*60*60);
						
						$this->userModel->insertLoginToken($verify->id, $loginToken);
						Server::redirect("home/");
					}

				}   else {
					// Unverified user
					$append = filter_var($data['email_or_username'], FILTER_VALIDATE_EMAIL) 
							  ? '?email='.$data['email_or_username']  : '';
							  
					$data['email_or_username_err'] = 
					"User is not verified. 
					<a class='link-primary text-decoration-none'
					href='".URLROOT."/user/resend-verification$append'>Resend Email</a>"; 
				}
			}
		}

		$this->view('user/login', $data);
	}

	/**
	 * Sign up View for user
	 * 
	 * @route
	 */
	public function signUp(): void {
		$this->view('user/sign-up');
	}


	/**
	 * Resend verification for unverified emails
	 * Allow after 10 minutes of last request
	 * Add new token to database
	 * Resend email for verification
	 * 
	 * @route
	 */
	public function resendVerification(): void {
		$data['error'] = '';
		$data['default_mail'] = $_GET['email'] ?? '';

		if(Server::checkPostReq(['email'])) {
			$email = $_POST['email'];
			$lastIssued = strtotime($this->userModel->checkVerificationRequestTime($email));
			if(!$this->userModel->ifEmailExists($email)) {
				$data['error'] = "Email not found";
			}   else if($this->userModel->isVerifiedEmail($email)) {
				$data['error'] = "Email already verified";
			}   else if(time() - $lastIssued < 600) {
				$data['error'] = "Please wait 10 minutes to resend mail";
			}

			if(Str::emptyStrings([$data['error']])) {
				$newToken = Utils::randToken();
				$linkTag = "<a href='".URLROOT."/user/verify-email/$newToken"."'>Verify</a>";
				$body = "Your Verification Link is: $linkTag. Please ignore this mail if it wasnt you";
				$mailStatus = $this->mailModel->sendMail($email, 'New Verification Link', $body);

				if($this->userModel->insertNewEmailToken($email, $newToken) && $mailStatus) {
					$this->userModel->deleteOldEmailTokens($email, $newToken);
					Session::flash('email_token_sent', 'Check email and spam folder for new verification link');
					Server::redirect('user/login');
				}   else {
					$data['error'] = "Error Encountered";
				}
			}
		}

		$this->view('user/resend-verification', $data);
	}

	/**
	 * Verification page
	 * Check if verication was successful
	 * 
	 * @param string $token - Verification Token
	 * @route
	 */
	public function verifyEmail(string $token): void {
		$data['message'] = '';
		
		if($this->userModel->verifyByToken($token)) {
			Session::flash('email_verified', 'Email verified successfully. Sign in');
			Server::redirect('user/login');
		}   else {
			$data['message'] = "Invalid Request. Try Again";
		}

		$this->view('user/verify-email', $data);
	}

	/**
	 * Send request for forgotten password
	 * Allow after 10 minutes of resetting password
	 * Send mail with reset id 
	 * Add reset token to database
	 * 
	 * @route
	 */
	public function forgotPassword(): void {
		$data = [
			'error' => '',
			'email' => ""
		];

		if(Server::checkPostReq(['email'])) {
			$email = $_POST['email'];
			$data['email'] = $email;
			$lastIssued = strtotime($this->userModel->checkResetPasswordRequestTime($email));

			if(!$this->userModel->ifEmailExists($email)) {
				$data['error'] = "Email not found";
			}   else if(time() - $lastIssued < 600) {
				$data['error'] = "Please wait 10 minutes to reset password";
			}

			if(Str::emptyStrings([$data['error']])) {
				$newToken = Utils::randToken();
				$linkTag = "<a href='".URLROOT."/user/reset-password/$newToken"."'>Reset</a>";
				$body = "Reset Password Link is: $linkTag. Please ignore this mail if it wasnt you";
				$mailStatus = $this->mailModel->sendMail($email, 'Reset Password', $body);

				if($this->userModel->insertNewPasswordToken($email, $newToken) && $mailStatus) {
					$this->userModel->deleteOldPasswordTokens($email, $newToken);
					Session::flash('forgot_password', 'Check email and spam folder to reset password');
					Server::redirect('user/login');
				}   else {
					$data['error'] = "Error Encountered";
				}
			}
		}

		$this->view('user/forgot-password', $data);
	}

	/**
	 * Reset Password of user
	 * Check if token is still valid
	 * Reset password in database
	 * Send email specifying reset details for verification
	 * 
	 * @param string $token 
	 * @route
	 */
	public function resetPassword(string $token): void {
		$data = array(
			"password" => '',
			'confirm_password' => '',
			'password_err' => '',
			'confirm_password_err' => '',
			'token' => $token,
			'error' => ''
		);

		$email = $this->userModel->getEmailByPasswordToken($token);
		if(!$email) {
			$data['error'] = "Token Not Found. Try Again";
		} else if(!$this->userModel->isPasswordTokenValid($token)) {
			$data['error'] = "Token Expired. Try Again";
		}

		if(Server::checkPostReq(['password', 'confirm_password'])) {
			$password = $_POST['password'];
			$confirmPassword = $_POST['confirm_password'];
			$data['password'] = $password;
			$data['confirm_password'] = $confirmPassword;

			if(!Str::isValidPassword($password)) {
				$data['password_err'] = "Enter Valid Password";
			}

			if($password !== $confirmPassword) {
				$data['confirm_password_err'] = "Passwords do not match";
			}

			if(Str::emptyStrings([$data['password_err'], $data['confirm_password_err']])) {
				$password = password_hash($password, PASSWORD_DEFAULT);
				$data['confirm_password_err'] = "Error Encountered";

				if($this->userModel->updatePasswordByToken($token, $password)) {
					$ipDetails = "Unable to determine user information";
					$userDetails = $_SERVER['HTTP_USER_AGENT'] ?? "";

					$ip = Server::getIpAddress() ?? "";
					if(filter_var($ip, FILTER_VALIDATE_IP)) 
						$ipDetails = $this->userModel->ipDetails($ip);

					$body = "
						Password was recently reset for your Luminosity account using email address $email. 
						<br> 
						<h2>Activity Details</h2>
						<b>$userDetails</b>
						<pre>$ipDetails</pre>
						If you dont recognize this: Please 
						<a href='".URLROOT."/user/forgot-password'>Reset Password</a>
					";


					$mailStatus = $this->mailModel->sendMail($email, 'Password Reset for '.SITENAME, $body);
	
					if($mailStatus) {
						Session::flash('password_reset', 'Password successfully reset.');
						Server::redirect('user/login');
					}
				}
			}
		}

		$this->view('user/reset-password', $data);
	}
}
