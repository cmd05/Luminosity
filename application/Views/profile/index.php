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
            <small class="text-muted pt-2 pb-3 d-block">Created <?=date("d M Y", strtotime($data['profile_info']->created_at))?></small>
            
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
                    <?php foreach($data['articles'] as $article): ?>
                        <div class="p-1">
                            <div class="card-body row">
                                <div class="col-12 ms-2">
                                    <?php if(!Str::isEmptyStr($article->preview_img)):?>
                                        <img src="<?=$article->preview_img?>" alt="" class="preview-img pb-3">
                                    <?php endif; ?>
                                    <br>
                                    <a class='mb-3 d-block h3 text-decoration-none text-dark' href="<?=URLROOT?>/article?a=<?=$article->article_id?>"><?=ht($article->title, 100)?></a>
                                    <small class="text-muted mb-4 d-block">Published <?=date("d M Y", strtotime($article->created_at))?></small>
                                    <p class="text-muted" style='font-size: 17px'><?=ht($article->tagline, 300)?></p>
                                    <p class='article-content'><?=ht($article->content, 1000)?></p>
                                    
                                    <?=$article->view_count?> <i class="fas fa-eye"></i>

                                    <button class="btn btn-dark float-end toggle-bookmark" data-article-id="<?=$article->article_id?>">
                                        <i class="<?=$article->is_bookmarked?"fas":"far"?> fa-bookmark" style='pointer-events: none;'></i>
                                    </button>
                                </div>
                                <hr class='my-4'>
                            </div>
                        </div>
                        <br>
                    <?php endforeach; ?>
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
