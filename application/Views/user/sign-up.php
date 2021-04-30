<?php View::header(false, 'Sign Up') ?>
<?php require_once(APPROOT.'/Views/inc/guest/sign-up-navbar.php') ?>
<link rel="stylesheet" href="<?=URLROOT?>/css/sign-up.css">
<main class="sign-up">
    <section id="section_1">
        <h3 class="text-center p-0 m-0 mb-4">Create An Account</h3>
        <div class="form-group mb-3">
            <label for="email" class='mb-2'>Enter Email Address</label>
            <input type="text" class="form-control form-input" name='email' placeholder="Email" data-error='email_err'>
            <small class="mt-2 d-block invalid-feedback" name='email_err'>
            </small>
        </div>
        <div class="form-group mb-3">
            <label for="gender" class='mb-2'>Gender</label>
            <select class="form-select form-input" aria-label="Default select example" name='gender' data-error='gender_err'>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
            <small class="mt-2 d-block invalid-feedback" name='gender_err'>
            </small>
        </div>
        <div class="form-group mb-3">
            <label for="email" class='mb-2'>Password</label>
            <div class="input-group">
                <input type="password" class="form-control form-input" placeholder="Password" name='password' id='pwd-input' data-error='password_err'>
                <span class="input-group-btn">
                <button class="btn btn-light border" id='pwd-toggle'>
                <i class="fas fa-eye"></i>
                </button>
                </span>
            </div>
            <small class="mt-2 d-block text-muted">
            Password contain atleast 8 characters and a number
            </small>
            <small class="mt-2 d-block invalid-feedback" name='password_err'></small>
        </div>
        <div class="form-group mb-4">
            <label for="email" class='mb-2'>Confirm Password</label>
            <input type="password" class="form-control form-input" placeholder="Confirm Password" name='confirm_password' data-error='confirm_password_err'>
            <small class="mt-2 d-block invalid-feedback" name='confirm_password_err'>
            </small>
        </div>
        <small class="mt-2 d-block invalid-feedback" name='total_err'>
        </small>
        <button class="w-100 btn btn-primary arrow-btn mt-1" type="button" id='next_1'>
        <span>Setup Profile</span>    
        </button>
    </section>
    <section id="section_2">
        <h4 class="text-center p-0 m-0 mb-5">Setup Profile</h4>
        <div class="preview-pfp-wrapper">
            <div class="pfp-upload-preview preview-pfp" style="background-image: url(<?=URLROOT?>/assets/default-profile.png);"></div>
            <input type="file" name="profile_img" id="pfp-upload-inp" accept=".jpg, .jpeg, .png" onchange="readURL(this);" data-error='profile_img_err'>
            <button id="btn-upload-pfp" type="button">
            <i class="fa fa-plus"></i>
            </button>
        </div>
        <small class="text-muted text-center d-block mt-1 mb-4" style='font-size: 14px'>Upload a Profile Picture <br> (JPG, PNG, JPEG) <br> Max 8 MB</small>
        <small class="text-danger mt-1 mb-2" name='profile_img_err' style='margin-left: 145px;'>
        </small>
        <div class="form-group mb-4">
            <label for="email" class='mb-2'>Display Name</label>
            <input type="text" class="form-control" name='display_name' placeholder="Display Name"  data-error='display_name_err'>
            <small class="mt-2 d-block invalid-feedback" name='display_name_err'>
            </small>
        </div>
        <div class="form-group mb-3">
            <label for="email" class='mb-2'>Username</label>
            <input type="text" class="form-control" name='username' placeholder="Username"  data-error='username_err'>
            <small class="text-muted mt-2 d-block">
            Username be less than 30 characters and cannot contain spaces
            </small>
            <small class="mt-2 d-block invalid-feedback" name='username_err'>
            </small>
        </div>
        <p>Description (Upto 250 Characters)</p>
        <div class="form-floating">
            <textarea name="about" class="form-control shadow-none" placeholder="Leave a comment here" id="floatingTextarea" style='height: 220px'  data-error='about_err'></textarea>
            <label for="floatingTextarea">About You</label>
        </div>
        <small class="mt-2 invalid-feedback d-block" name='about_err'>        
        </small>
        <small class="mt-2 d-block invalid-feedback" name='complete_err'>
        </small>
        <div class='mt-4'>
            <div class="btn me-auto btn-info text-white float-start d-inline-block px-4 back-btn" id='back_1'>
                <span>Back </span>
            </div>
            <div class="btn ms-auto d-inline-block float-end btn-primary arrow-btn px-4 next-btn" id='complete_btn'>
                <span>Complete </span>
            </div>
        </div>
    </section>
    <br><br>
</main>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
    
            reader.onload = function(e) {
                document.querySelectorAll('.pfp-upload-preview')[0].style.backgroundImage = "url('" + e.target.result + "')";
            };
    
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<script src="<?=URLROOT?>/js/sign-up.js" type="module"></script>
<?php View::footer(false) ?>