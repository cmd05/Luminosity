<?php View::header(true, "Articles") ?>
<br><br>
<link rel="stylesheet" href="<?=URLROOT?>/css/drafts.css">
<div class="container-lg container-fluid">
   <?php Session::alert("alert_article_delete") ?>
   <div class="container-fluid container-lg">
      <?php if($data['count'] > 0): ?>
        <h2>My Articles</h2>
      <?php else: ?>
        <h1>0 results <a href="<?=URLROOT?>/write/new" class='h4'>Create Draft</a></h1>
      <?php endif; ?>
      <br><br>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" id="articles-container">
         <?php foreach($data['articles'] as $article): ?>
            <?php 
                $edit_link = URLROOT.'/write/edit-article/'.$article->article_id;
                $view_link = URLROOT.'/article?a='.$article->article_id;
            ?>
            <div class="col mb-3">
                <div class="card shadow-sm">
                  <?php if(!Str::isEmptyStr($article->preview_img)): ?>
                    <img src="<?=$article->preview_img?>" class="card-img-top" alt="...">
                  <?php endif; ?>
                  <div class="card-body">
                      <h4 class='draft-name'><a href="<?=$view_link?>" class='text-dark text-decoration-none'><?=ht($article->title, 40)?></a></h4>
                      <div class="p-2"></div>
                      <p class="card-text tagline"><?=Str::isEmptyStr($article->tagline)?"<i class='fs-6'>Tagline</i>":ht($article->tagline, 100)?></p>
                      <p class="card-text"><?=ht($article->content, 200)?></p>
                      <div class="py-2">
                        <small class="text-muted"><span style='font-weight: 500; font-size: 14px;'>Created: </span> <?=Str::formatEpoch(strtotime($article->created_at), "d/m H:i")?></small>
                        <br>
                        <small class="text-muted"><span style='font-weight: 500; font-size: 14px;'>Last Edit: </span> <?=Str::formatEpoch(strtotime($article->last_updated), "d/m H:i")?></small>
                      </div>
                      <div class="d-flex justify-content-between align-items-center pt-2">
                        <a type="button" class="btn btn-sm btn-light border d-block w-100 mx-auto" href="<?=$edit_link?>">&nbsp;Edit&nbsp;</a>
                      </div>
                      <br>
                      <div class="btn-group mx-auto">
                        <button type="button" class="btn btn-sm btn-outline-danger delete-article" data-delete-id="<?=$article->article_id?>">&nbsp;Delete&nbsp;</button>
                        <button type="button" class="btn btn-sm btn-outline-primary copy-link" data-link="<?=$view_link?>">Copy Link</button>
                      </div>
                  </div>
                </div>
            </div>
         <?php endforeach ?>
      </div>

      <?php if($data['count'] > 0): ?>
        <button class="btn btn-primary m-auto mt-5 mb-0 d-block" style='margin-bottom: -40px!important' id="articles-more-btn">Load More </button>
      <?php endif; ?>
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

<?=View::formToken($data['last_article_id'],"last_article_id")?>
<script src="<?=URLROOT?>/js/articles.js" type="module"></script>
<?php View::footer() ?>