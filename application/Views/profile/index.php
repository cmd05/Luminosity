<?php View::header(true, "@{$data['profile_info']->username} - Profile") ?>
<link rel="stylesheet" href="<?=URLROOT?>/css/profile.css">

<?php if(!Session::isLoggedIn()) require_once APPROOT."/Views/inc/login-modal.php" ?>

<div class="mb-4 pb-1"></div>
<div class="profile-info border-bottom px-lg-5 p-3">
    <div class="mx-lg-5 px-lg-5 row mt-0 pt-0">
        
        <div class="col-md-2 col-12">
            <span class="profile-img ms-lg-5" style='background-image: url(<?=PROFILE_IMG_DIR."/{$data['profile_info']->profile_img}"?>)''></span>                
        </div>
        <div class="col-md-10 col-12 pt-2 pb-3">
            <h5 class='pb-1 mb-0 d-inline-block'><?=ht($data['profile_info']->display_name)?></h5>

            <?php if(($_SESSION['user_id'] ?? 0) !== $data['profile_info']->id): ?>
                <button class="btn btn-outline-primary float-end rounded <?=$data['is_following']?"active":""?>" id='follow-btn'>
                    <?=$data['is_following']?"Following":"Follow"?>
                </button>
            <?php else: ?>
                <a href="<?=URLROOT?>/settings" class="btn btn-primary float-end">Edit Profile</a>
            <?php endif; ?>
            <p class="text-muted my-0 pt-0">@<?=ht($data['profile_info']->username)?></p>
            <small class="text-muted pt-2 pb-3 d-block">Joined <?=date("d M Y", strtotime($data['profile_info']->created_at))?></small>
            
            <p class='pb-1'><?=ht($data['profile_info']->about)?></p>

            <a href="<?=URLROOT."/profile/following/{$data['profile_info']->username}"?>" class="text-decoration-none me-4" style='font-size: 15px'>
                <?=$data['profile_info']->following_count?> following
            </a>

            <a href="<?=URLROOT."/profile/followers/{$data['profile_info']->username}"?>" class="text-decoration-none" style='font-size: 15px'>
                <?=$data['profile_info']->followers_count?>
                <?=$data['profile_info']->followers_count === 1 ? "follower" : "followers" ?>
            </a>
            
            <div class="mb-3"></div>
        </div>
    </div>
</div>

<div class="mx-md-5 px-md-5">
    <div class="mx-lg-5">
        <div class="mx-lg-5 px-lg-5">
            <main class='m-auto d-block'>
                <br><br>
                <h3 class='ms-2'>Articles</h3>
                <br>

                <div class="articles-container m-0 p-0">
                    <!-- Fetch Articles -->
                    <?=$data['article_renders']?>
                </div>
                <?php if(count($data['articles']) > 0): ?>
                    <button class="btn btn-primary d-block m-auto" id="load-more-articles">Load More</button>
                <?php endif; ?>
            </main>
        </div>
    </div>
</div>

<script src="<?=URLROOT?>/js/profile.js" type="module"></script>
<?=View::formToken($data['last_article_id'], "last_article_id")?>
<?=View::formToken($data['profile_info']->uniq_id, "user_uniq_id")?>
<?php View::footer() ?>
