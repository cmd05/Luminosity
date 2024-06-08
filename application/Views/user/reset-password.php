<?php View::header(false, "Reset Password") ?>
<?php require_once(APPROOT.'/Views/inc/guest/sign-up-navbar.php') ?>
<link rel="stylesheet" href="<?=URLROOT?>/css/single-form-pages.css">
<br><br>
<?php if(!empty($data['error'])): ?>
<div class="container">
    <p class="display-6 text-center pt-5"><?=$data['error']?></p>
</div>
<?php else: ?>
<form action="<?= URLROOT ?>/user/reset-password/<?=$data['token']?>" method='POST' id='page-form' class='mt-3'>
    <h3 class='text-center mb-5'>Reset Password</h3>
    <div class="form-group mb-3">
        <label for="password" class='d-block pb-2'>Enter New Password </label>
        <div class="input-group">
            <input type="password" id='pwd-input' class="form-control <?= !empty($data['password_err']) ?  'is-invalid' : ''?>" name='password' placeholder="New Password" value="<?=ht($data['password'])?>">
            <span class="input-group-btn">
            <button class="btn btn-light border" id='pwd-toggle' type='button'>
                <i class="fas fa-eye"></i>
            </button>
            </span>
            <p class="invalid-feedback pt-1 mb-0">
                <?=$data['password_err']?>
            </p>
        </div>
        <small class="mt-2 d-block text-muted">
        Password contain atleast 8 characters and a number
        </small>
    </div>

    <div class="form-group mb-4">
        <label for="confirm_password" class='d-block pb-2'>Confirm Password </label>
        <input type="password" class="form-control <?= !empty($data['confirm_password_err']) ?  'is-invalid' : ''?>" name='confirm_password' placeholder="Confirm Password" value="<?=ht($data['confirm_password'])?>">
        <p class="invalid-feedback pt-1 mb-0">
            <?=$data['confirm_password_err']?>
        </p>
    </div>
    
    <input type="hidden" name="csrf_token" class='token' value="<?=Session::csrfToken()?>">
    <input type="submit" value="Reset Password" class='btn btn-success w-100'>
    <br>
    
    <div class="container-fluid w-100 p-0 mt-4 mb-0" style='height: 50px'>
        <a href="<?=URLROOT?>/user/login" class="btn text-primary float-start"><i class="fas fa-arrow-left"></i> &nbsp; Back to Login</a>
    </div>
</form>
<?php endif; ?>

<script src="<?=URLROOT?>/js/password-toggle.js"></script>
<?php View::footer(false) ?>