<?php View::header(true, "Explore") ?>
<br><br>
<link rel="stylesheet" href="<?=URLROOT?>/css/drafts.css">
<div class="container-lg container-fluid">
	<?php Session::alert("alert_article_delete") ?>
	<div class="container-fluid container-lg">
		<?php if(count($data['users']) > 0): ?>
		  <h2>Explore - Users</h2>
		<?php else: ?>
		  <h1>0 results</h1>
		<?php endif; ?>
		
		<p class='my-4'>Sorted by - <?=$data['sort']?></p>
		
		<div class="dropdown d-inline me-4">
		  <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
				Explore
		  </button>
		  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
				<li><a class="dropdown-item" href="<?=URLROOT?>/explore">Articles</a></li>
				<li><a class="dropdown-item" href="<?=URLROOT?>/explore/users">Users</a></li>
		  </ul>
		</div>

		<div class="dropdown d-inline">
		  <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
				Sort By
		  </button>
		  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
				<li><a class="dropdown-item" href="<?=URLROOT?>/explore/users?sort_by=views">Most Views</a></li>
				<li><a class="dropdown-item" href="<?=URLROOT?>/explore/users?sort_by=followers">Most Followers</a></li>
		  </ul>
		</div>
		
		
		<br><br><br>
		<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" id="articles-container">
			<?php foreach($data['users'] as $user): ?>
				<div class="col mb-3">
					 <div class="card shadow-sm">
						<div class="card-body">
							<div class="row">
								<div class="col-2 col-md-2 text-center">
									<div class="user-img" style="background-image: url(<?=PROFILE_IMG_DIR."/{$user->profile_img}"?>);"></div>
								</div>
								<div class="col ps-4">
									<a href="<?=URLROOT?>/profile?u=<?=$user->username?>" class='text-dark text-decoration-none'>
									<?=ht($user->display_name)?>                           
									</a>
									<?php if($user->show_btn): ?>
										<button class="btn btn-outline-primary float-end mt-0 follow-btn <?=$user->is_following?"active":""?>" data-uniq="<?=$user->uniq_id?>">
										<?=$user->is_following?"Following":"Follow"?>
										</button>
									<?php endif; ?>
									<small class="text-muted d-block">@<?=ht($user->username)?></small>
									<br>
									<?php if($data['sort'] === "Followers"): ?>
										<b class='pb-2 d-inline-block'><?=$user->followers_count?>  follower(s)</b>
									<?php endif; ?>
									<p class='mb-0' style='font-size: 15px;'><?=ht($user->about, 150)?></p>
								</div>
							</div>
						</div>
					 </div>
				</div>
			<?php endforeach ?>
		</div>

	</div>
</div>

<div class="toast align-items-center text-white bg-dark border-0 center-toast m-auto fixed-bottom mb-4 text-center" role="alert" aria-live="assertive" aria-atomic="true" id='articles-toast' style='width: 300px!important; padding: 2px; font-size: 15px;'>
	<div class="d-flex">
		<div class="toast-body container show-save text-center">
			Toast Body   <i class="fas fa-circle-notch fa-spin"></i>                        
		</div>
		<button type="button" class="btn-close btn-close-white me-2 m-auto toast-close" data-bs-dismiss="toast" aria-label="Close"></button>
	</div>
</div>

<?php if(!Session::isLoggedIn()) require_once APPROOT."/Views/inc/login-modal.php" ?>

<script type='module'>
import{newTokenData,URL,isJson,loginMdl}from"<?=URLROOT?>/js/script.js";document.addEventListener("click",function(t){const n=t.target;if(n.classList.contains("follow-btn")){const t=newTokenData({profile_uniq:n.getAttribute("data-uniq")});fetch(`${URL}/ajax/profile/toggle-follow`,{method:"POST",body:t}).then(t=>t.text()).then(t=>{if(isJson(t)){200===JSON.parse(t).status&&(n.classList.toggle("active"),"Follow"==n.innerHTML.trim()?n.innerHTML="Following":n.innerHTML="Follow")}else loginMdl()})}});
</script>
<?php View::footer() ?>