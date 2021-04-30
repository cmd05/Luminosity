<?php View::header(true, "Explore") ?>
<br><br>
<link rel="stylesheet" href="<?=URLROOT?>/css/drafts.css">
<div class="container-lg container-fluid">
   <?php Session::alert("alert_article_delete") ?>
   <div class="container-fluid container-lg">
      <?php if(count($data['articles']) > 0): ?>
        <h2>Explore - Articles</h2>
      <?php else: ?>
        <h1>0 results</h1>
      <?php endif; ?>
      <br>
      
      <div class="dropdown d-inline me-4">
        <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            Explore
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li><a class="dropdown-item" href="<?=URLROOT?>/explore/articles">Articles</a></li>
            <li><a class="dropdown-item" href="<?=URLROOT?>/explore/users">Users</a></li>
        </ul>
      </div>

      <div class="dropdown d-inline">
        <button class="btn btn-dark dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            Sort By
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li><a class="dropdown-item" href="<?=URLROOT?>/explore/articles">Most Viewed</a></li>
            <li><a class="dropdown-item" href="<?=URLROOT?>/explore/articles?sort_by=most_recent">Most Recent</a></li>
            <li><a class="dropdown-item" href="<?=URLROOT?>/explore/articles?sort_by=most_comments">Most Comments</a></li>
            <li><a class="dropdown-item" href="<?=URLROOT?>/explore/articles?sort_by=most_reactions">Most Reactions</a></li>
        </ul>
      </div>
      
      
      <br><br><br>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" id="articles-container">
         <?php foreach($data['articles'] as $article): ?>
            <?php 
                $view_link = URLROOT.'/article?a='.$article->article_id;
            ?>
            <div class="col mb-3">
                <div class="card shadow-sm">
                  <?php if(!Str::isEmptyStr($article->preview_img)): ?>
                    <img src="<?=$article->preview_img?>" class="card-img-top" alt="...">
                  <?php endif; ?>
                  <div class="card-body">
                      <h4 class='draft-name'><a href="<?=$view_link?>" class='text-dark text-decoration-none'><?=ht($article->title, 40)?></a></h4>
                      <small class="text-muted pt-1 pb-0 mb-0">Published: <?=date("d/m/y", strtotime($article->created_at))?></small>
                      <p class="card-text tagline"><?=Str::isEmptyStr($article->tagline)?"<i class='fs-6'>Tagline</i>":ht($article->tagline, 100)?></p>
                      <p class="card-text"><?=ht($article->content, 200)?></p>
                      <br>
                      <span>
                        <?=$article->view_count?> <i class="fas fa-eye"></i>
                      </span>
                      <span class="float-end">
                        <?=$article->comments_count?> <i class="fas fa-comment"></i>
                      </span>
                  </div>
                </div>
            </div>
         <?php endforeach ?>
      </div>

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

<?php View::footer() ?>