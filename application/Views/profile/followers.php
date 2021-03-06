<?php View::header(true, "@{$data['profile']->username} - Followers") ?>
<link rel="stylesheet" href="<?=URLROOT?>/css/profile.css">
<br><br>
<div class="container px-lg-5">
	<?php if(!Session::isLoggedIn()) require_once APPROOT."/Views/inc/login-modal.php" ?>

	<div class="px-lg-5 mx-lg-5">
		<div class="px-lg-1">
			<h5 >@<?=$data['profile']->username?> / Followers</h5>
			<br>
			<div class="border p-2">
				<h5 class='pt-3 ps-2'>
					<a href="<?=URLROOT?>/profile?u=<?=$data['profile']->username?>" class='pe-2'><i class="fas fa-arrow-left"></i></a>
					Profile
				</h5>
				
				<?=count($data['followers']) === 0 ? '<br><h5 class="text-muted p-3">0 results</5>' : "<br>"?>
				<div class="list-container">
					<?php foreach($data['followers'] as $follower): ?>
						<div class="p-2 row mb-0 pb-1">
							<div class="col-2 text-center">
								<div class="follow-list-img" style='background-image: url(<?=PROFILE_IMG_DIR."/{$follower->profile_img}"?>);'></div>
							</div>
							<div class="col-10">
								<h6 class='d-inline-block'>
									<a href="<?=URLROOT?>/profile?u=<?=$follower->username?>" class='text-dark text-decoration-none'><?=ht($follower->display_name)?></a>
								</h6>
								<?php if($follower->show_btn): ?>
									<button class="btn follow-btn mb-1 btn-outline-primary float-end rounded <?=$follower->is_following?"active":""?>" data-uniq="<?=$follower->uniq_id?>">
										<?=$follower->is_following ? "Following":"Follow"?>
									</button>
								<?php endif; ?>
								<p class="text-muted">@<?=ht($follower->username)?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<button class="btn btn-primary d-block mt-4 mb-2 m-auto" id="show-more">Load More</button>
			</div>
		</div>
	</div>
</div>
<script src="<?=URLROOT?>/js/followers.js" type="module"></script>
<?=View::formToken($data['last_id'], "last_follower_id")?>
<?=View::formToken($data['profile']->uniq_id, "uniq_id")?>
<?php View::footer() ?>