<?php View::header(true, "Search") ?>

<link rel="stylesheet" href="<?=URLROOT?>/css/profile.css">
<link rel="stylesheet" href="<?=URLROOT?>/css/article-preview.css">

<br><br>
<div class="container px-lg-5">
	<?php if(!Session::isLoggedIn()) require_once APPROOT."/Views/inc/login-modal.php" ?>
	<?php $url = URLROOT."/explore/search?q=".urlencode($data['query']) ?>

	<div class="px-lg-5 mx-lg-5">
		<div class="px-lg-1">
			<div class="p-2">
				<h5 class='pt-3 ps-2'>
					Search Results for <pre class='ms-1 d-inline'><?=ht($data['query'], 100)?></pre>
				</h5>
				<br>
				<h5 class='d-block ps-2 pt-2'>Users</h5>
				<?=count($data['users']) === 0 ? '<br><h5 class="text-muted p-3">0 results</5>' : "<br>"?>
				<div class="list-container">
					<?php foreach($data['users'] as $profile): ?>
						<div class="p-2 row mb-0 pb-1">
							<div class="col-2 text-center">
								<div class="follow-list-img" style='background-image: url(<?=PROFILE_IMG_DIR."/{$profile->profile_img}"?>);'></div>
							</div>
							<div class="col-10">
								<h6 class='d-inline-block'>
									<a href="<?=URLROOT?>/profile?u=<?=$profile->username?>" class='text-dark text-decoration-none'><?=ht($profile->display_name)?></a>
								</h6>
								<?php if($profile->show_btn): ?>
									<button class="btn follow-btn mb-1 btn-outline-primary float-end rounded <?=$profile->is_following?"active":""?>" data-uniq="<?=$profile->uniq_id?>">
										<?=$profile->is_following ? "Following":"Follow"?>
									</button>
								<?php endif; ?>
								<p class="text-muted mb-2">@<?=ht($profile->username)?></p>
								<p><?=ht($profile->about, 100)?></p>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<br>
				<?php if(count($data['users']) > 0): ?>
				<a href="<?=$url?>&type=users" class="btn btn-light border ms-5 mb-4">Show more users</a>
				<?php endif; ?>
				
				<br><br>

				<h5 class='d-block ps-2 pt-2'>Articles</h5>
				<br>
				<a href="<?=URLROOT?>/explore/search?q=<?=urlencode($data['query'])?>&type=tagged_articles" class="btn btn-light border ms-4">Show articles tagged (<?=ht($data['query'], 15)?>)</a>
				<br>
				<?=count($data['articles']) === 0 ? '<br><h5 class="text-muted p-3">0 results</5>' : "<br>"?>
				<div class="list-container">
					<?=$data['article_renders']?>
				</div>
				<br>
				<?php if(count($data['articles']) > 0): ?>
					<a href="<?=$url?>&type=articles" class="btn btn-light border ms-5 mb-4">Show more articles</a>
				<?php endif; ?>
				<br>
			</div>
		</div>
	</div>
</div>
<script type='module'>
import { isJson, URL, loginMdl, newTokenData } from "<?=URLROOT?>/js/script.js";
document.addEventListener("click", function (t) {
    const n = t.target;
    if (n.classList.contains("follow-btn")) {
        const t = newTokenData({ profile_uniq: n.getAttribute("data-uniq") });
        fetch(`${URL}/ajax/profile/toggle-follow`, { method: "POST", body: t })
            .then((t) => t.text())
            .then((t) => {
                if (isJson(t)) {
                    200 === JSON.parse(t).status && (n.classList.toggle("active"), "Follow" == n.innerHTML.trim() ? (n.innerHTML = "Following") : (n.innerHTML = "Follow"));
                } else loginMdl();
            });
    }
});
</script>
<?php View::footer() ?>