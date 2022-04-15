<?php View::header(true, "Search") ?>
<link rel="stylesheet" href="<?=URLROOT?>/css/profile.css">

<?php if(!Session::isLoggedIn()) require_once APPROOT."/Views/inc/login-modal.php" ?>

<div class="mb-4 pb-1"></div>

<div class="mx-md-5 px-md-5">
	<div class="mx-lg-5">
		<div class="mx-lg-5 px-lg-5">
			<main class='m-auto d-block'>
				<br><br>
				<h3 class='ms-2'>Articles tagged (<pre class='d-inline'><?=ht($data['query'], 100)?></pre>)</h3>
				<br>
				<div class="ps-4">
				</div>
				
				<br>
				<div class="articles-container m-0 p-0">
					<?=$data['article_renders']?>
					<!-- Fetch Articles -->
				</div>
				<?php if(count($data['articles']) > 0): ?>
					<button class="btn btn-primary d-block m-auto" id="load-more-articles">Load More</button>
				<?php endif; ?>
			</main>
		</div>
	</div>
</div>

<script src="<?=URLROOT?>/js/search/search-tagged.js" type='module'></script>
<?=View::formToken($data['last_article_id'], "last_article_id")?>
<?=View::formToken($data['query'], "query")?>
<?php View::footer() ?>
