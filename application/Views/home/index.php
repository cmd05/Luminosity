<?php View::header(true, 'Home') ?>
<div style='padding-bottom: 24px;'></div>
<link rel="stylesheet" href="<?=URLROOT?>/css/home.css">
<link rel="stylesheet" href="<?=URLROOT?>/css/article-preview.css">

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-3 d-lg-inline d-none px-4 pt-1">
			<div class="card mx-2 mt-5">
				<div class="card-body">
					<div class="row">
						<div class="col-2 col-sm-3">
							<span class="user-img d-inline-block me-3" style='background-image: url(<?=PROFILE_IMG_DIR?>/<?=$_SESSION['profile_img']?>);'></span> 
						</div>
						<a class="col text-dark text-decoration-none" href="<?=URLROOT?>/profile">
							<b class='d-block'><?=ht($_SESSION['display_name'])?></b>
							<small class="text-muted">@<?=ht($_SESSION['username'])?></small>
						</a>
						<br><br><br>
						<a href="<?=URLROOT?>/write/articles" class="text-dark text-decoration-none d-block py-2"><i class="fas fa-arrow-right pe-2"></i> My Articles</a>
						<a href="<?=URLROOT?>/settings" class="text-dark text-decoration-none"><i class="fas fa-arrow-right pe-2"></i> Settings</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-md-8 col-12 p-0 m-0">
			<h5 class='mt-2 mb-0 pb-0 ms-2 ms-lg-0'> <i class="fas fa-home pe-1" style='font-size: 15x;'></i> Home</h5>
			<div class="articles-container m-0 p-0">
					<!-- Fetch Articles -->
					<?php if(count($data['articles']) < 5): ?>
						<p class='mt-4 mb-0 pb-0 ms-2 ms-lg-0'>Looks pretty empty in here. <a href="<?=URLROOT?>/explore" class='text-decoration-none'>Explore <i class="fas fa-angle-double-right" style='font-size: 15px'></i></a></p>
					<?php endif; ?>

					<?=$data['article_renders']?>
				</div>
				<?php if(count($data['articles']) > 0): ?>
						<button class="btn btn-primary d-block m-auto" id="load-more-articles">Load More</button>
					<?php endif; ?>
		</div>
		<div class="col-lg-3 col-md-4 d-md-inline-block d-none">
			<div class="card mx-3 mt-3">
				<h6 class='m-3 mb-0' style='font-size: 18px'>Recommended</h6>
				<hr style='color: grey'>
				<div class="card-body mt-0 pt-1">
					<?php foreach($data['suggested'] as $article): ?>
					<div class="article">
						<h6><a href="<?=URLROOT?>/article?a=<?=$article->article_id?>" class='text-dark text-decoration-none'><?=ht($article->title, 30)?></a></h6>
						<p class="text-muted"><?=ht($article->tagline, 30)?></p>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?=View::formToken($data['last_id'], "last_id")?>
<script src="<?=URLROOT?>/js/home.js" type="module"></script>
<?php View::footer() ?>
