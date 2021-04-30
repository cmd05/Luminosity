<?php View::header(true, "Search") ?>
<link rel="stylesheet" href="<?=URLROOT?>/css/profile.css">

<?php if(!Session::isLoggedIn()) require_once APPROOT."/Views/inc/login-modal.php" ?>

<div class="mb-4 pb-1"></div>

<div class="mx-md-5 px-md-5">
    <div class="mx-lg-5">
        <div class="mx-lg-5 px-lg-5">
            <main class='m-auto d-block'>
                <br><br>
                <h3 class='ms-2'>Articles similar to '<?=ht($data['query'], 100)?>'</h3>
                <br>
                <div class="ps-4">
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

                                    <span class='d-inline-block float-end me-2'>
                                        <?=$article->comments_count?> <i class="fas fa-comment"></i>    
                                    </span>
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

<script type="module">
import{URL,loginMdl,newTokenData,isJson,LiveLoader,ht}from"<?=URLROOT?>/js/script.js";const bodyLoader=new LiveLoader;function isBlank(e){return!e||/^\s*$/.test(e)}bodyLoader.addIdSelector("[name='last_article_id']"),bodyLoader.addBtn("#load-more-articles"),bodyLoader.addParams({query:document.querySelector("[name='query']").value}),bodyLoader.addEndPoint("ajax/explore/load-article-results"),bodyLoader.addListener(e=>{Object.entries(e.articles).forEach(([e,a])=>{const n=a,t=(n.article_id,isBlank(n.preview_img)?"":`<img src="${n.preview_img}" class="preview-img pb-3" alt="...">`),s=n.article_id;document.querySelector(".articles-container").innerHTML+=`\n          <div class="p-1">\n              <div class="card-body row">\n                  <div class="col-12 ms-2">\n                      ${t}\n                      <br>\n                      <a class='mb-3 d-block h3 text-decoration-none text-dark' href="${URL}/article?a=${s}">${ht(n.title,100)}</a>\n                      <small class="text-muted mb-4 d-block">Published ${n.created_at}</small>\n                      <p class="text-muted" style='font-size: 17px'>${ht(n.tagline,300)}</p>\n                      <p class='article-content'>${ht(n.content,1e3)}</p>\n                      \n                      ${n.view_count} <i class="fas fa-eye"></i>\n\n                      <span class='d-inline-block float-end me-2'>\n                        ${n.comments_count} <i class="fas fa-comment"></i>\n                      </span>\n                  </div>\n                  <hr class='my-4'>\n              </div>\n          </div>\n          <br>\n          `})});
</script>
<?=View::formToken($data['last_article_id'], "last_article_id")?>
<?=View::formToken($data['query'], "query")?>
<?php View::footer() ?>
