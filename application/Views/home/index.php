<?php View::header(true, 'Home') ?>
<div style='padding-bottom: 24px;'></div>
<link rel="stylesheet" href="<?=URLROOT?>/css/home.css">
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 d-lg-inline d-none bg-light px-4 pt-1">
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
                        <a href="<?=URLROOT?>/bookmarks" class="text-dark text-decoration-none"><i class="fas fa-arrow-right pe-2"></i> Bookmarks</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-8 col-12 bg-light">
            <h2 class='mt-2'>Home</h2>
            <br>
            <div class="articles-container m-0 p-0">
                    <!-- Fetch Articles -->
                    <?php if(count($data['articles']) < 5): ?>
                        <h6>Looks Pretty empty in here. <a href="<?=URLROOT?>/explore">Explore</a></h6>
                    <?php endif; ?>

                    <?php foreach($data['articles'] as $article): ?>
                        <div class="p-1">
                            <div class="card-body row">
                                <div class="col-12 ms-2">
                                    <?php if(!Str::isEmptyStr($article->preview_img)):?>
                                        <img src="<?=$article->preview_img?>" alt="" class="preview-img pb-3">
                                    <?php endif; ?>
                                    <br>
                                    <a class='mb-3 d-block h3 text-decoration-none text-dark' href="<?=URLROOT?>/article?a=<?=$article->article_id?>"><?=ht($article->title, 100)?></a>
                                    <div class="row mt-4 mb-3">
                                        <div class="col-2 col-sm-1">
                                            <span class="user-img d-inline-block me-3" style='background-image: url(<?=PROFILE_IMG_DIR?>/<?=$article->profile_img?>);'></span>                                        
                                        </div>
                                        <a class="col ps-4 text-dark text-decoration-none" href="<?=URLROOT?>/profile?u=<?=$article->username?>">
                                            <p class='py-0 my-0'><?=ht($article->display_name)?></p>
                                            <small class="text-muted">@<?=ht($article->username)?></small>
                                        </a>
                                    </div>
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
                    <?php endforeach; ?>
                </div>
                <?php if(count($data['articles']) > 0): ?>
                        <button class="btn btn-primary d-block m-auto" id="load-more-articles">Load More</button>
                    <?php endif; ?>
        </div>
        <div class="col-lg-3 col-md-4 d-md-inline-block d-none bg-light">
            <div class="card mx-2 mt-3">
                <h5 class='m-3'>Suggested Articles</h5>
                <div class="card-body">
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
