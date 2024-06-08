<div class="reply-box mt-4 row">
    <div class="col-2 col-md-1"></div>
    <div class="col-md-8 col-10 mt-1 row">
        <div class="col-3 col-md-2">
        <div class="user-img" style="background-image: url(<?=PROFILE_IMG_DIR."/".$_SESSION['profile_img']?>); border: 1px solid #ebebeb"></div>
        </div>

        <div class="col-md-10 col-9 mt-1">
        <h6>
            <a href="<?=URLROOT?>/profile?u=<?=$_SESSION['username']?>" class='text-decoration-none text-dark d-inline-block me-2'><?=ht($_SESSION['display_name'])?></a>
            <small class='text-muted fw-normal' id='username-<?=$parse['id']?>'><?=Str::formatEpoch(time(), "d/m/y H:i")?></small>
        </h6>
        
        <p id='content-<?=$parse['id']?>'><?=ht($parse['content'])?></p>

        <button data-comment-id="<?=$parse['id']?>" class="reaction-btn me-4 react-heart" title='0 user(s) liked this reply'>
            <img src="<?=URLROOT?>/assets/reaction-heart.png" alt="" class='reaction-img' style='pointer-events: none;'>
            <span class="ps-1 count-heart-<?=$parse['id']?>" style='pointer-events: none;'>0</span>
        </button>

        <small class="p-2 pt-0 d-inline-block mt-1 text-muted reply-btn" data-type='reply' data-mention='<?=$_SESSION['username']?>' data-parent-id='<?=$parse['parent_id']?>'
        style='cursor: pointer; user-select: none;' >REPLY</small>

        <div class="dropup m-0 p-0 d-inline-block float-end">
            <button class="btn btn-white float-end d-inline-block p-0 m-0" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-ellipsis-v" style='font-size: 15px'></i>
            </button>
            
            <ul class="dropdown-menu m-0 float-start comment-dropdown" aria-labelledby="dropdownMenuButton1">
                <li class='delete-comment'><a class="dropdown-item" href="javascript:void(0)" data-comment-id="<?=$parse['id']?>">Delete</a></li>
                <li class='edit-comment'><a class="dropdown-item" href="javascript:void(0)" data-comment-id="<?=$parse['id']?>" data-type="reply" data-content="<?=ht($parse['content'])?>">Edit</a></li>
                <li><a class="dropdown-item" href="javascript:void(0)" data-comment-id="<?=$parse['id']?>">Report</a></li>
            </ul>
        </div>
        </div>
    </div>
</div>