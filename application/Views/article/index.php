<?php View::header(true, $data['article']->title) ?>

<link rel="stylesheet" href="<?=URLROOT?>/css/hljs-theme.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/10.5.0/highlight.min.js"></script>
<link rel="stylesheet" href="<?=URLROOT?>/css/article.css">

<?php if(!Session::isLoggedIn()) require_once APPROOT."/Views/inc/login-modal.php" ?>

<div class="container p-md-5 pb-md-0 mb-md-0">
   <div class="p-4 p-sm-0"></div>

   <div class="mx-md-5 main-article px-md-3">
      <a class='d-block text-center h2 text-decoration-none text-reset' href="<?=URLROOT?>/article?a=<?=$data['article']->article_id?>">
      	<?=ht($data['article']->title)?>
      </a> 
      
      <?php if(($_SESSION['user_id'] ?? "") === $data['article']->user_id): ?>
        <br><br>
        <a class="btn btn-light px-2 me-2" href="<?=URLROOT?>/write/edit-article/<?=$data['article']->article_id?>">Edit Article</a>
        <a class="btn btn-light px-2">Stats</a>
      <?php endif; ?>

      <br><br>
      <p class="text-muted"><?=$data['views'] === 1 ? $data['views']. " view": $data['views']. " views"?></p>
      <p class="text-muted mb-1">Created: <?=Str::formatEpoch(strtotime($data['article']->created_at), "d/m/y H:i")?></p>
      <p class="text-muted">Last Edit: <?=Str::formatEpoch(strtotime($data['article']->last_updated), "d/m/y H:i")?></p>
      <br>

      <div class="user-details" style="width: 250px; height: 80px; cursor: pointer" 
         onclick="window.location.href ='<?=URLROOT?>/profile?u=<?=$data['user']->username?>'">
         <div class="row">
            <div class="col-3">
               <div class="user-img" style="background-image: url(<?=PROFILE_IMG_DIR."/{$data['user']->profile_img}"?>);"></div>
            </div>
            <div class="col-9">
               <b><?=ht($data['user']->display_name)?></b>
               <p class="text-muted">@<?=ht($data['user']->username)?></p>
            </div>
         </div>
      </div>

      <?php if(!Str::isEmptyStr($data['article']->preview_img)): ?>
        <img src="<?=$data['article']->preview_img?>" alt="..."  class="preview-img">
        <br><br>
      <?php endif; ?>

      <div class="show-fixed-bar d-block">
         <div class="content-area">
            <?php if(!Str::isEmptyStr($data['article']->tagline)): ?>
            <i class='text-muted'>
               <?=ht($data['article']->tagline)?>
               <hr>
            </i>
            <?php endif; ?>
            <?=$data['article']->content?>
         </div>
      </div>

   </div>

   <br><br>
   
   <div class="actions-docked d-block actions ms-1 ms-md-5 ps-2 ps-md-2">
      <button type="button" class="btn reaction-btn me-3 react-written <?= isset($data['user_reactions']['well-written']) ? "reacted" : "" ?>" title="<?=$data['reactions_count'][0]?> user(s) reacted with well written">
        <img src="<?=URLROOT?>/assets/reaction-well-written.png" class='reaction-img react-written' title="<?=$data['reactions_count'][0]?> user(s) reacted with well written">
        <span class="ps-1 count-written react-written" title="<?=$data['reactions_count'][0]?> user(s) reacted with well written"><?=$data['reactions_count'][0]?></span>
      </button>
      
      <button class="reaction-btn me-3 <?= isset($data['user_reactions']['interesting']) ? "reacted" : "" ?> react-interesting" title='<?=$data['reactions_count'][1]?> user(s) reacted with interesting'>
        <img src="<?=URLROOT?>/assets/reaction-interesting.png" alt="" class='reaction-img react-interesting' title='<?=$data['reactions_count'][1]?> user(s) reacted with interesting'>
        <span class="ps-1 count-interesting react-interesting" title='<?=$data['reactions_count'][1]?> user(s) reacted with interesting'><?=$data['reactions_count'][1]?></span>
      </button>
      
      <button class="reaction-btn me-3 react-confused <?= isset($data['user_reactions']['confused']) ? "reacted" : "" ?>" title='<?=$data['reactions_count'][2]?> user(s) reacted with confused'>
        <img src="<?=URLROOT?>/assets/reaction-confused.png" alt="" class='reaction-img react-confused' title='<?=$data['reactions_count'][2]?> user(s) reacted with confused'>
        <span class="ps-1 count-confused react-confused" title='<?=$data['reactions_count'][2]?> user(s) reacted with confused'><?=$data['reactions_count'][2]?></span>
      </button>

      <button class="btn btn-dark py-1 ms-5 toggle-bookmark">
        <i class="<?=$data['is_bookmarked'] ? "fas" : "far" ?> fa-bookmark icon"></i>
      </button>
   </div>
   
   <div class="row">
      <div class="fixed-bottom fixed-action-bar p-1 m-auto bg-light border">
         <div class="container">
            <div class="" style='display: table;margin: auto; width: 100%;'>
               <button type="button" class="btn reaction-btn me-2 react-written <?= isset($data['user_reactions']['well-written']) ? "reacted" : "" ?>" title="<?=$data['reactions_count'][0]?> user(s) reacted with well written">
                    <img src="<?=URLROOT?>/assets/reaction-well-written.png" class='reaction-img react-written' title="<?=$data['reactions_count'][0]?> user(s) reacted with well written">
                    <span class="ps-1 count-written react-written" title="<?=$data['reactions_count'][0]?> user(s) reacted with well written"><?=$data['reactions_count'][0]?></span>
               </button>

               <button class="reaction-btn me-2 <?= isset($data['user_reactions']['interesting']) ? "reacted" : "" ?> react-interesting" title='<?=$data['reactions_count'][1]?> user(s) reacted with interesting'>
                    <img src="<?=URLROOT?>/assets/reaction-interesting.png" alt="" class='reaction-img react-interesting' title='<?=$data['reactions_count'][1]?> user(s) reacted with interesting'>
                    <span class="ps-1 count-interesting react-interesting" title='<?=$data['reactions_count'][1]?> user(s) reacted with interesting'><?=$data['reactions_count'][1]?></span>
               </button>

               <button class="reaction-btn me-2 react-confused <?= isset($data['user_reactions']['confused']) ? "reacted" : "" ?>" title='<?=$data['reactions_count'][2]?> user(s) reacted with confused'>
                    <img src="<?=URLROOT?>/assets/reaction-confused.png" alt="" class='reaction-img react-confused' title='<?=$data['reactions_count'][2]?> user(s) reacted with confused'>
                    <span class="ps-1 count count-confused react-confused" title='<?=$data['reactions_count'][2]?> user(s) reacted with confused'><?=$data['reactions_count'][2]?></span>
               </button>

               <button class="btn btn-dark py-1 ms-5 float-end toggle-bookmark">
                  <i class="<?=$data['is_bookmarked'] ? "fas" : "far" ?> fa-bookmark icon"></i>
               </button>
            </div>
         </div>
      </div>
   </div>

   <br>
   <h4><?=count($data['tags']) > 0 ? "Tags" : ""?></h4>
   <br>

   <?php foreach ($data['tags'] as $tag): ?>
    <a href="<?=URLROOT?>/explore/search?q=<?=$tag->tag?>&type=tagged_articles" class="btn btn-sm btn-primary text-white me-1 my-2"><?=ht($tag->tag)?></a>
   <?php endforeach; ?>
   <br><br>

   <h4>Comments</h4>
   <br>

   <?php require_once "comment-section.php"; ?>

   <div class="toast align-items-center text-white bg-dark border-0 center-toast m-auto fixed-bottom mb-4 text-center" role="alert" aria-live="assertive" aria-atomic="true" id='message-toast' style='width: 300px!important; padding: 2px; font-size: 15px;'>
      <div class="d-flex">
         <div class="toast-body container show-save text-center">
		 
         </div>
         <button type="button" class="btn-close btn-close-white me-2 m-auto toast-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
   </div>
</div>


<!-- Reaction toggles and counts -->
<div class="react-written-count token" data-count='<?=$data['reactions_count'][0]?>' data-toggle='<?= isset($data['user_reactions']['well-written']) ? "true" : "false" ?>'></div>
<div class="react-interesting-count token" data-count='<?=$data['reactions_count'][1]?>' data-toggle='<?= isset($data['user_reactions']['interesting']) ? "true" : "false" ?>'></div>
<div class="react-confused-count token" data-count='<?=$data['reactions_count'][2]?>' data-toggle='<?= isset($data['user_reactions']['confused']) ? "true" : "false" ?>'></div>
<?=View::formToken($data['article']->article_id, "article_id")?>

<script>
   if(document.querySelectorAll(".content-area img")[0]) document.querySelectorAll(".content-area img")[0].style.display = "none";
   function resizeBar() {document.querySelector(".fixed-action-bar").style.width = (document.querySelector(".content-area").getBoundingClientRect().width+10) + "px";}
   resizeBar();
   window.onresize = resizeBar;
</script>

<script src="<?=URLROOT?>/js/article.js" type="module"></script>
<?php View::footer() ?>