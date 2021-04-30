<?php View::header() ?>
<link rel="stylesheet" href="<?=URLROOT?>/css/info-home.css">
<div id="homeCarousel" class="carousel slide" data-bs-ride="carousel">
	<ol class="carousel-indicators">
		<li data-bs-target="#homeCarousel" data-bs-slide-to="0" class="active"></li>
		<li data-bs-target="#homeCarousel" data-bs-slide-to="1"></li>
		<li data-bs-target="#homeCarousel" data-bs-slide-to="2"></li>
	</ol>
	<div class="carousel-inner">
		<div class="carousel-item active">
			<img src="<?=URLROOT?>/assets/home-gif.gif" class="d-block w-100" alt="<?=URLROOT?>/assets/logo.png">
			<div class="carousel-caption">
				<p>Read About Topics that interest you.</p>
			</div>
		</div>
		<div class="carousel-item">
			<img src="<?=URLROOT?>/assets/slide-2.jpg" class="d-block w-100" alt="<?=URLROOT?>/assets/logo.png">
			<div class="carousel-caption">
				<p>Create Posts with the help of a Friendly Interface</p>
			</div>
		</div>
		<div class="carousel-item">
			<img src="<?=URLROOT?>/assets/slide-3.jpg" class="d-block w-100" alt="<?=URLROOT?>/assets/logo.png">
			<div class="carousel-caption">
				<p>Participate in a community of users from around the world.</p>
			</div>
			
		</div>


		<div class="carousel-caption carousel-static-box">
			<h3 class='mt-5 ps-5'><a href="<?=URLROOT?>/user/sign-up" class='text-decoration-none text-white'>Sign up Today</a></h3>
			<p class='mt-4 lead'><?=SITENAME?> - The Modern Blogging Platform for Developers, Experts, Readers, Authors and — Everyone.</p>
		</div>


	</div>
	<a class="carousel-control-prev" href="#homeCarousel" role="button" data-bs-slide="prev">
		<span class="carousel-control-prev-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Previous</span>
	</a>
	<a class="carousel-control-next" href="#homeCarousel" role="button" data-bs-slide="next">
		<span class="carousel-control-next-icon" aria-hidden="true"></span>
		<span class="visually-hidden">Next</span>
	</a>
</div>

<br>
<div class="container">
	
	<p class='pt-5 pb-0 mb-0' style='font-size: 18px;'><?=SITENAME?> is a modern blogging platform for users all over the world <br><br>
		 <ul>
		 <li>Read articles on diverse topics such as <b>tech, politics, science, environment and the modern world...</b> <br><br></li>
		 <li>Write articles with a friendly web interface and publish them in no time <br><br></li>
		 <li>Comment, like and react on articles <br><br></li>
		 <li>Save Articles for later by bookmarking them <br><br></li>
		 </ul>
		 <b>Go ahead create an account now</b>
	</p>
</div>

<?php View::footer() ?>