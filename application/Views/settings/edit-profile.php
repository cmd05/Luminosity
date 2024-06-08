<?php
	View::header(false, "Settings");
	View::customNav([
		"Edit Profile" => "settings/edit-profile",
		"Account" => "settings/account",
		"Preferences" => "settings/preferences",
		"Privacy" => "info/privacy"
	]);
?>
<link rel="stylesheet" href="<?=URLROOT?>/css/edit-profile.css">
<br><br><br><br>
<div class="container mb-5 ps-lg-5 mt-2">
	<h2>Edit Profile</h2>
	<br>
	<div class="row">
		<div class="mb-3 d-inline-block col-12 col-sm-8 order-2 order-sm-1">
			<label for="displayName" class="form-label">Display Name</label>
			<input type="text" class="form-control w-100" id="displayName" aria-describedby="" value="<?=ht($_SESSION['display_name'])?>" name="display_name" data-error='display_name_err'>
			<small class="mt-2 d-block invalid-feedback" name='display_name_err'></small>

			<br>
			<label for="username" class="form-label">Username</label>
			<input type="text" class="form-control w-100" id="username" aria-describedby="" value="<?=ht($_SESSION['username'])?>" name="username" data-error='username_err'>
			<small class="mt-2 d-block invalid-feedback" name='username_err'></small>

			<br>

			<label for="about" class="form-label">About</label>
			<textarea name="about" id="about" rows="5" class='form-control' name='about' data-error='about_err'><?=ht($_SESSION['about'])?></textarea>
			<small class="mt-2 d-block invalid-feedback" name='about_err'></small>

			<?=View::formToken()?>
			<small class="mt-2 d-block invalid-feedback" name='total_err'></small>

			<button class="btn d-block btn-success mt-5" id='save'>Save Changes</button>
		</div>

		<div class="d-inline-block col-12 col-sm-4 order-1 order-sm-2">
			<div class="float-sm-end align-items-center px-auto">
				<div style="background-image: url(<?=Session::userProfilePath()?>)" class="img-thumbnail profile-img" alt="..." id='preview-img'></div>
				<input type="file" name="image" id="image" class='token'>
				<button class="btn btn-dark d-block m-auto mt-2" onclick="document.querySelector('#image').click()">Upload <i class="fas fa-upload"></i></button>
				<small class="mt-2 d-block invalid-feedback" name='profile_img_err'></small>
			</div>
		</div>
	
	</div>
</div>

<script src="<?=URLROOT?>/js/edit-profile.js" type='module'></script>
<?php View::footer() ?>