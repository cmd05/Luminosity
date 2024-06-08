<?php View::header(true, "Search") ?>

<link rel="stylesheet" href="<?=URLROOT?>/css/profile.css">
<br><br>
<div class="container px-lg-5">
    <?php if(!Session::isLoggedIn()) require_once APPROOT."/Views/inc/login-modal.php" ?>

    <div class="px-lg-5 mx-lg-5">
        <div class="px-lg-1">
            <div class="border p-2">
                <h5 class='pt-3 ps-2'>
                    Users Results for '<?=ht($data['query'], 100)?>'
                </h5>

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
                <?php if(count($data['users']) > 0): ?>
                <button class="btn btn-primary d-block mt-4 mb-2 m-auto" id="show-more">Load More</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="<?=URLROOT?>/js/search/search-users.js" type="module"></script>

<?=View::formToken($data['last_result_id'], "last_id")?>
<?=View::formToken($data['query'], "search_query")?>

<?php View::footer() ?>