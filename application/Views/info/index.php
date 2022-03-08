<?php View::header() ?>
<link rel="stylesheet" href="<?= URLROOT ?>/css/info-home.css">
<link rel="stylesheet" href="<?= URLROOT ?>/css/particles.css">

<!-- Particles container -->
<div id="particles-js">
	<div class="container mt-5 pt-5 col-lg-8 col-md-10 col-sm-10 container justify-content-center">
		<div class="col mt-4 pt-3 text-center">
			<h3 class=''><a href="<?= URLROOT ?>/user/sign-up" class='text-decoration-none text-white'><?= SITENAME ?> - Join</a></h3>
			<p class='mt-3 lead text-light'>The Modern Blogging Platform for Developers, Experts, Readers, Authors and — Everyone.</p>
		</div>
	</div>
</div>

<!-- Content -->
<div class="container mt-3">
	<p class='pt-2 pb-0 mb-0' style='font-size: 18px;'><?= SITENAME ?> is an <a href="https://github.com/cmd05/Luminosity" target='_blank' class='text-dark'>open source</a> and modern blogging platform for users all over the world <br><br>
	<ul>
		<li>Read articles on many diverse topics<br><br></li>
		<li>Use a web-friendly interface to create posts and save drafts<br><br></li>
		<li>Follow other users and customize your profile<br><br></li>
		<li>Comment and react on articles<br><br></li>
		<li>Bookmark articles to save them for later<br><br></li>
	</ul>
	</p>

	<p align="center">
		<img src="https://user-images.githubusercontent.com/63466463/129299606-2a4d8560-e534-4a89-9184-7aa1facc0240.png" class='w-75' />
	</p>
	<p align="center" class=''><b>Create Drafts and Articles</b></p>
	<p><br></p>

	<p align="center">
		<img src="https://user-images.githubusercontent.com/63466463/129299632-5d4d2f56-4b1e-4d7c-8805-ea14d05b78a0.png" class='w-75' />
	</p>
	<p align="center"><b>Explore Articles</b></p>
	<p><br></p>

	<p align="center">
		<img src="https://user-images.githubusercontent.com/63466463/129299670-54689033-ccc6-4183-b925-2b68ab8992b4.png" class='w-75' />
	</p>
	<p align="center"><b>Customize your profile</b></p>
</div>

<script src="<?= URLROOT ?>/js/particles.js"></script>
<?php View::footer() ?>