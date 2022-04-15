<link rel="stylesheet" href="<?=URLROOT?>/css/article-preview.css">

<?php foreach($data['articles'] as $article): ?>
	<div class="p-1 px-1 mx-0 article-box">
		<div class="card-body row m-0 p-1 px-0 mx-0">
			<div class="col-12 ms-2 px-0 mx-0">
				<br>
				<a class='mb-4 pb-2 d-block h4 title-link text-dark' href="<?=URLROOT?>/article?a=<?=$article->article_id?>"><?=ht($article->title, 100)?></a>
				<div class="row mt-4 mb-3">
					<div class="col-2 col-sm-1">
						<span class="user-img d-inline-block me-3" style='background-image: url(<?=PROFILE_IMG_DIR?>/<?=$article->profile_img?>);'></span>                                        
					</div>
					<a class="col ps-4 text-dark text-decoration-none" href="<?=URLROOT?>/profile?u=<?=$article->username?>">
						<p class='py-0 my-0'><?=ht($article->display_name)?></p>
						<small class="text-muted">@<?=ht($article->username)?></small>
					</a>
				</div>
				<small class="text-muted mb-3 d-block">Published <?=date("d M Y", strtotime($article->created_at))?></small>
				<p class="text-muted pt-1 pb-2" style='font-size: 16px'><?=ht($article->tagline, 300)?></p>

				<div class="mb-4 pb-1 px-0 mx-0 article-content-preview" style='max-height: 500px;'
						onclick="javascript:window.location.href= '<?=URLROOT?>/article?a=<?=$article->article_id?>'">
					<?=trh($article->content, 500)?>
				</div>

				<div class="border rounded p-2 pb-3">
					<span class='pt-2 ps-2 d-inline-block'><?=$article->view_count?> view<?=$article->view_count > 1 ? "s" : ''?></span>

					<button class="btn float-end toggle-bookmark pt-2" data-article-id="<?=$article->article_id?>">
						<i class="<?=$article->is_bookmarked?"fas":"far"?> fa-bookmark" style='pointer-events: none; font-size: 22px'></i>
					</button>
				</div>
			</div>
			<br>
		</div>
	</div>
<?php endforeach; ?>