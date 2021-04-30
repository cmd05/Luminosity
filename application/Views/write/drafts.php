<?php View::header(true, "Drafts") ?>
<br><br>
<link rel="stylesheet" href="<?=URLROOT?>/css/drafts.css">
<div class="container-lg container-fluid">
    <?php Session::alert("alert_draft_delete") ?>
    <div class="container-fluid container-lg">
    <?php if($data['count'] > 0): ?>
        <h2>Saved Drafts</h2>
      <?php else: ?>
          <h1>0 results <a href="<?=URLROOT?>/write/new" class='h4'>Create Draft</a></h1>
      <?php endif; ?>
      <br><br>
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3" id="drafts-container">
        <?php foreach($data['drafts'] as $draft): ?>
        <?php $draft_link = URLROOT.'/write/draft/'.$draft->draft_id ?>
        <div class="col mb-3">
          <div class="card shadow-sm">
            <div class="card-body">
              <h4 class='draft-name'><a href="<?=$draft_link?>" class='text-dark text-decoration-none'><?=ht($draft->draft_name, 40)?></a></h4>
              <div class="p-2"></div>
              <h5>
                  <span style='font-weight: 400'>
                    <?=Str::isEmptyStr($draft->title)?"<i class='fs-6'>Title</i>":ht($draft->title, 40)?>
                  </span>
              </h5>
              <p class="card-text tagline"><?=Str::isEmptyStr($draft->tagline)?"<i class='fs-6'>Tagline</i>":ht($draft->tagline, 100)?></p>
              <p class="card-text"><?=ht($draft->content, 200)?></p>
              <div class="py-2">
                <small class="text-muted"><span style='font-weight: 500; font-size: 14px;'>Created: </span> <?=Str::formatEpoch(strtotime($draft->created_at), "d/m H:i")?></small>
              </div>
              <div class="d-flex justify-content-between align-items-center pt-2">
                <div class="btn-group">
                  <button type="button" class="btn btn-sm btn-outline-danger delete-draft" data-delete-id="<?=$draft->draft_id?>">&nbsp;Delete&nbsp;</button>
                  <button type="button" class="btn btn-sm btn-outline-primary copy-link" data-link="<?=$draft_link?>">Copy Link</button>
                </div>
                <small class="text-muted"><span style='font-weight: 500; font-size: 14px;'>Last Edit: </span> <?=Str::formatEpoch(strtotime($draft->last_updated), "d/m H:i")?></small>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach ?>

      </div>
      <?php if($data['count'] > 0): ?>
      <button class="btn btn-primary m-auto mt-5 mb-0 d-block" style='margin-bottom: -40px!important' id="drafts-more-btn">Load More </button>
      <?php endif; ?>
    </div>
    
</div>

<div class="toast align-items-center text-white bg-dark border-0 center-toast m-auto fixed-bottom mb-4 text-center" role="alert" aria-live="assertive" aria-atomic="true" id='drafts-toast' style='width: 300px!important; padding: 2px; font-size: 15px;'>
    <div class="d-flex">
        <div class="toast-body container show-save text-center">
            Toast Body   <i class="fas fa-circle-notch fa-spin"></i>                        
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto toast-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>
<?=View::formToken($data['last_draft_id'],"last_draft_id")?>
<script src="<?=URLROOT?>/js/drafts.js" type="module"></script>
<?php View::footer(true) ?>

