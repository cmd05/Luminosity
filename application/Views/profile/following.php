<?php View::header(true, "@{$data['profile']->username} - Following") ?>
<link rel="stylesheet" href="<?=URLROOT?>/css/profile.css">
<br><br>
<div class="container px-lg-5">
    <?php if(!Session::isLoggedIn()) require_once APPROOT."/Views/inc/login-modal.php" ?>

    <div class="px-lg-5 mx-lg-5">
        <div class="px-lg-1">
            <h5 >@<?=$data['profile']->username?> - Following</h5>
            <br>
            <div class="border p-2">
                <h5 class='pt-3 ps-2'>
                    <a href="<?=URLROOT?>/profile?u=<?=$data['profile']->username?>" class='pe-2'><i class="fas fa-arrow-left"></i></a>
                    Return to Profile
                </h5>

                <?=count($data['following']) === 0 ? '<br><h5 class="text-muted p-3">0 results</5>' : "<br>"?>
                <div class="list-container">
                    <?php foreach($data['following'] as $following): ?>
                        <div class="p-2 row mb-0 pb-1">
                            <div class="col-2 text-center">
                                <div class="follow-list-img" style='background-image: url(<?=PROFILE_IMG_DIR."/{$following->profile_img}"?>);'></div>
                            </div>
                            <div class="col-10">
                                <h6 class='d-inline-block'>
                                    <a href="<?=URLROOT?>/profile?u=<?=$following->username?>" class='text-dark text-decoration-none'><?=ht($following->display_name)?></a>
                                </h6>
                                <?php if($following->show_btn): ?>
                                    <button class="btn follow-btn mb-1 btn-outline-primary float-end rounded <?=$following->is_following?"active":""?>" data-uniq="<?=$following->uniq_id?>">
                                        <?=$following->is_following ? "Following":"Follow"?>
                                    </button>
                                <?php endif; ?>
                                <p class="text-muted">@<?=ht($following->username)?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="btn btn-primary d-block mt-4 mb-2 m-auto" id="show-more">Load More</button>
            </div>
        </div>
    </div>
</div>
<script src="<?=URLROOT?>/js/following.js" type="module"></script>
<?=View::formToken($data['last_id'], "last_following_id")?>
<?=View::formToken($data['profile']->uniq_id, "uniq_id")?>
<?php View::footer() ?>