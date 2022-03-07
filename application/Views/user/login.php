<?php View::header(false, "Sign in") ?>
<?php require_once(APPROOT.'/Views/inc/guest/login-navbar.php') ?>
<link rel="stylesheet" href="<?=URLROOT?>/css/single-form-pages.css">
<br><br>
<form action="<?= URLROOT ?>/user/login" method='POST' id='page-form' class='mt-3'>
   <h3 class='text-center mb-3'>Sign in</h3>
   <p class="text-center mb-4" style="font-size: 17px; color: dimgrey;">continue to <?=SITENAME?></p>
   <?php Session::flash('register_success') ?>
   <?php Session::flash('email_verified') ?>
   <?php Session::flash('email_token_sent') ?>
   <?php Session::flash('forgot_password') ?>
   <?php Session::flash('password_reset') ?>
   <div class="form-group mb-3">
      <input type="text" class="form-control <?= !empty($data['email_or_username_err']) ?  'is-invalid' : ''?>" name='email_or_username' placeholder="Email or Username" value="<?=ht($data['email_or_username'])?>">
      <p class="invalid-feedback pt-1">
         <?=$data['email_or_username_err']?>
      </p>
   </div>
   <div class="form-group mb-4">
      <div class="input-group">
         <input type="password" id='pwd-input' class="form-control <?= !empty($data['password_err']) ?  'is-invalid' : ''?>" name='password' placeholder="Password" value="<?=ht($data['password'])?>">
         <span class="input-group-btn">
            <button class="btn btn-light border" id='pwd-toggle' type='button'>
                <i class="fas fa-eye"></i>
            </button>
         </span>
         <p class="invalid-feedback pt-1 mb-0">
            <?=$data['password_err']?>
         </p>
      </div>
   </div>
   <input type="hidden" name="csrf_token" class='token' value="<?=Session::csrfToken()?>">
   <input type="submit" value="Login" class='btn btn-success w-100'>
   <br>
   <div class="container-fluid w-100 p-0 mt-4 mb-0" style='height: 50px'>
      <a href="<?=URLROOT?>/user/sign-up" class="btn text-primary float-start">Register</a>
      <a href="<?=URLROOT?>/user/forgot-password" class="btn text-black float-end text-dark">Forgot Password?</a>
   </div>
</form>
<script src="<?=URLROOT?>/js/password-toggle.js"></script>
<?php View::footer(false) ?>