<link rel="stylesheet" href="<?=URLROOT?>/css/article-preview.css">

<?php foreach($data['articles'] as $article): ?>
    <div class="p-1 px-1 mx-0 article-box">
        <div class="card-body row m-0 p-1 px-0 mx-0">
            <div class="col-12 ms-2 px-0 mx-0">
                <br>
                <a class='mb-4 pb-2 d-block h4 title-link text-dark' href="<?=URLROOT?>/article?a=<?=$article->article_id?>"><?=ht($article->title, 100)?></a>
                <div class="row mt-4 mb-3">

                </div>
                <small class="text-muted mb-3 d-block">Published <?=date("d M Y", strtotime($article->created_at))?></small>
                <p class="text-muted pt-1 pb-2" style='font-size: 16px'><?=ht($article->tagline, 300)?></p>

                <div class="mb-4 pb-1 px-0 mx-0 article-content-preview" style='max-height: 500px;'
                    onclick="javascript:window.location.href= '<?=URLROOT?>/article?a=<?=$article->article_id?>'">
                    <?=trh($article->content, 500)?>
                </div>

                <div class="border rounded p-2 pb-3">
                    <span class='pt-2 ps-2 d-inline-block'><?=$article->view_count?> view<?=$article->view_count > 1 ? "s" : ''?></span>

                    <button class="btn float-end pt-2 border-none">
                        <?=$article->comments_count?> <i class="fas fa-comment"></i>
                    </button>
                </div>
            </div>
            <br>
        </div>
    </div>
<?php endforeach; ?>