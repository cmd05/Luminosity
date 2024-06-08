<!-- Button trigger modal -->
<button type="button" class="token" data-bs-toggle="modal" data-bs-target="#loginModal" id="bs-login-mdl-btn"></button>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="loginModalLabel">Whoops...</h5>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	  </div>
	  <div class="modal-body">
		&nbsp;You must be logged in to perform this action
		<div class="mb-1"></div>
	  </div>
	  <form action="<?=URLROOT?>/user/login" method="post" class='container w-75 m-auto'>
		<!-- <br> -->
		<h6 class='pb-1'>Email or Username</h6>
		<input type="text" class="form-control" name='email_or_username' placeholder="Email or Username">
		<br>
		<h6 class='pb-1'>Password</h6>
		<input type="password" id='pwd-input' class="form-control" name='password' placeholder="Password">
		<small class='d-block mt-4 mb-2'>
		  <a href="<?=URLROOT?>/user/sign-up" class='text-decoration-none'>Sign up</a>
		</small>
		
		<input type="hidden" name="csrf_token" class='token' value="<?=Session::csrfToken()?>">
		<div class="modal-footer">
		  <button type="submit" class="btn btn-primary">Login</a>
		</div>
	  </form>
	</div>
  </div>
</div>