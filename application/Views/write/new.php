<?php
	View::header(false, "Write");
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
			<h4 class='d-block'>Write a new article</h4>
			<div class="pb-md-1 pb-3"></div>
			<div class="col-md-8 col-12 order-md-1 order-2 ps-md-3 pe-md-0 py-4 p-0">
				<div class="p-md-4 bg-white rounded p-2 py-4" style='border: 1px solid #d7d7d7'>
					<input type="text" class='form-control bg-white' placeholder="Title" id='title'>
					<br>
					<textarea type="text" class='form-control bg-white shadow-none' id="tagline" placeholder="Tagline" name="tagline"></textarea>
					<br>
					<div id="editor" class='bg-white'>
					</div>
					<div id="counter">characters</div>
					<br>
					<input type="text" class='form-control' placeholder="Add tags (comma seperated)" id="tags">
					<br>
					<button class="btn btn-success px-3 float-end d-block mt-1" id="submit-post">Post</button>
					<div style='margin-bottom: 40px'></div>
				</div>
			</div>
			<div class="col-md-4 col-12 order-md-2 order-1 p-md-4 ps-md-5 mt-0 sticky-md-top">
				<div class="px-4 bg-white rounded pt-4 mt-0 pb-3 sticky-md-top" style='border: 1px solid #d7d7d7; top: 100px!important'>
					<div class="input-group mb-3">
						<input type="text" id='draft_name' class="form-control" placeholder="Save as a Draft" aria-label="" aria-describedby="save-draft">
						<button class="btn btn-dark" type="button" id="save-draft">Save</button>
					</div>
					<p class="invalid-feedback is-invalid">Error</p>
					<b class='pt-3 pb-2 d-block'>Unsaved Changes</b>
					<p class='mb-3' style='line-height: 29px; font-size: 15px'>Your content will not be automatically saved. Creating a draft allows you to work on your piece as and when you wish. 
					</p>
					<a class="btn btn-primary w-100" href='#submit-post'>Post now <i class=" ms-2 fa-caret-down fas"></i></a>
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
	<div class="toast align-items-center text-white bg-dark border-0 center-toast" role="alert" aria-live="assertive" aria-atomic="true" id='draft-err-toast' style='max-width: 5000px!important; padding: 2px; font-size: 15px'>
		<div class="d-flex">
			<div class="toast-body container">
				Draft Error   <i class="fas fa-circle-notch fa-spin"></i>                        
			</div>
			<button type="button" class="btn-close btn-close-white me-2 m-auto toast-close" data-bs-dismiss="toast" aria-label="Close"></button>
		</div>
	</div>
	<br><br><br>
</main>
<?=View::formToken(IMG_VALIDATE_URL, "img_valid_url")?>
<script src="<?=URLROOT?>/js/submit-article.js" type="module"></script>
<script type="module">
import{URL,newTokenData,isJson}from"<?=URLROOT?>/js/script.js";document.querySelector("#save-draft").addEventListener("click",function(){const e=newTokenData({title:document.querySelector("#title").value,tagline:document.querySelector("#tagline").value,content:document.querySelector(".ql-editor").innerHTML,draft_name:document.querySelector("#draft_name").value});fetch(`${URL}/ajax/write/save-draft`,{method:"POST",body:e}).then(e=>e.text()).then(e=>{if(isJson(e)){let t=document.getElementById("draft-err-toast"),a=new bootstrap.Toast(t,{delay:6e3}),o=JSON.parse(e);200===o.status?location.replace(`${URL}/write/draft/${o.draft_id}`):(delete o.status,t.querySelector(".toast-body").innerHTML=`${o[Object.keys(o)[0]]}`,a.show())}})});
</script>
<?php View::footer(false) ?>