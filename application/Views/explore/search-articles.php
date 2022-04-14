<?php View::header(true, "Search") ?>
<link rel="stylesheet" href="<?=URLROOT?>/css/profile.css">
<link rel="stylesheet" href="<?=URLROOT?>/css/article-preview.css">

<?php if(!Session::isLoggedIn()) require_once APPROOT."/Views/inc/login-modal.php" ?>

<div class="mb-4 pb-1"></div>

<div class="mx-md-5 px-md-5">
    <div class="mx-lg-5">
        <div class="mx-lg-5 px-lg-5">
            <main class='m-auto d-block'>
                <br><br>
                <h4 class='pt-3 ps-2 ms-2'>
                    Search / Articles
                    <p class="my-3"></p>
                    <i class="fas fa-search me-2" style='font-size: 15px'></i> <pre class='ms-1 d-inline'><?=ht($data['query'], 100)?></pre>
                </h4>
                
                <br> 
                <div class="ps-3">
                    <?php if(count($data['tags']) > 0): ?>
                        <h6 class='d-inline pt-3'>Related Tags &nbsp;</h6>
                    <?php endif; ?>
                    
                    <?php foreach ($data['tags'] as $tag): ?>
                        <a href="<?=URLROOT?>/explore/search?q=<?=$tag->tag?>&type=tagged_articles" class="btn btn-sm btn-primary text-white me-1 my-2"><?=ht($tag->tag)?></a>
                    <?php endforeach; ?>
                </div>
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

<script src="<?=URLROOT?>/js/search/search-articles.js" type='module'></script>
<?=View::formToken($data['last_article_id'], "last_article_id")?>
<?=View::formToken($data['query'], "query")?>
<?php View::footer() ?>
