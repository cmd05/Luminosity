<?php
    View::header(false, "Editing Draft");
    View::customNav([
        "Saved Drafts" => "write/drafts",
        "My Articles" => "explore"
    ]); 
    require_once APPROOT.'/Views/inc/user/write-includes.php';
?>

<main>
    <div style='margin-bottom: 100px'></div>
    <div class="container-lg px-lg-2">
        <div class="row">
            <span> 
                <span class='d-inline-block h4'>Editing Draft</span>
                <span class='ps-2 h5' id="draft_name" style='font-weight: normal;'><?=ht($data['draft_name'])?></span>
                <span class="ps-auto float-end pt-2" style="font-size: 14px!important">
                    <button type="button" class="btn btn-info text-white me-2 py-1" id="rename">Rename <i class="fas fa-pencil-alt"></i></button>
                    <button type="button" class="btn btn-danger me-2 py-1" id="delete">Delete <i class="fas fa-trash"></i></button>
                    <button type="button" class="btn btn-primary py-1" id='copy-link'>Copy Link <i class="fas fa-copy"></i></button>
                </span>
            </span> 
            <div class="pb-md-1 pb-3"></div>
            <div class="col-md-8 col-12 order-md-1 order-2 ps-md-3 pe-md-0 py-4 p-0">
                <div class="p-md-4 bg-white rounded p-2 py-4" style='border: 1px solid #d7d7d7'>
                    <input type="text" class='form-control bg-white' placeholder="Title" id='title' value="<?=ht($data['title'])?>">
                    <br>
                    <textarea type="text" class='form-control bg-white shadow-none' id="tagline" placeholder="Tagline" name="tagline"><?=ht($data['tagline'])?></textarea>
                    <br>
                    <div id="editor" class='bg-white'><?=($data['content'])?></div>
                    <div id="counter">characters</div>
                    <br>
                    <input type="text" class='form-control' placeholder="Add tags" id="tags">
                    <br>
                    <button class="btn btn-success px-3 float-end d-block mt-1" id="submit-post">Post</button>
                    <div style='margin-bottom: 40px'></div>
                </div>
            </div>
            <div class="col-md-4 col-12 order-md-2 order-1 p-md-4 ps-md-5 mt-0 sticky-md-top">
                <div class="px-4 bg-white rounded pt-4 mt-0 pb-3 sticky-md-top" style='border: 1px solid #d7d7d7; top: 100px!important'>
                    <button class="btn d-block btn-success text-white w-100" id='save-changes' style='background-image: none!important; font-size: 17px;'>Saved</button>
                    <p class="invalid-feedback is-invalid">Error</p>
                    <b class='pt-3 pb-2 d-block'>Writing a Great Post Title</b>
                    <p style='line-height: 29px; font-size: 15px'>Think of your post title as a super short (but compelling!) description — like an overview of the actual post in one short sentence.
                        <small class="text-muted">&nbsp;<a href="">Guide</a></small>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="toast align-items-center text-white bg-dark border-0 center-toast" role="alert" aria-live="assertive" aria-atomic="true" id='img-toast' data-bs-autohide="false">
        <div class="d-flex">
            <div class="toast-body container">
                Uploading Image   <i class="fas fa-circle-notch fa-spin"></i>                        
            </div>
        </div>
    </div>

    <div class="toast align-items-center text-white bg-dark border-0 center-toast" role="alert" aria-live="assertive" aria-atomic="true" id='draft-update-toast' style='padding: 2px; font-size: 15px'>
        <div class="d-flex">
            <div class="toast-body container show-save">
                Saving Changes   <i class="fas fa-circle-notch fa-spin"></i>                        
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto toast-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>

    <div class="toast align-items-center text-white bg-dark border-0 center-toast" role="alert" aria-live="assertive" aria-atomic="true" id='draft-err-toast' style='max-width: 5000px!important; padding: 2px; font-size: 15px'>
        <div class="d-flex">
            <div class="toast-body container">
                Draft Error   <i class="fas fa-circle-notch fa-spin"></i>                        
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto toast-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
        
    <br><br><br>
    <?=View::formToken($data['draft_id'], "draft_id")?>
    <?=View::formToken(IMG_VALIDATE_URL, "img_valid_url")?>

</main>

<script src="<?=URLROOT?>/js/submit-article.js" type="module"></script>
<script src="<?=URLROOT?>/js/draft.js" type="module"></script>
<?php View::footer(false) ?>