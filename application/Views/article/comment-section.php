<link rel="stylesheet" href="<?=URLROOT?>/css/comments.css">

<div class="row" id="comment-add-box">
    <div class="col-2 col-md-1">
        <?php 
            $commentImage = DEFAULT_PROFILE_PATH;
            if(Session::isLoggedIn()) $commentImage = PROFILE_IMG_DIR."/{$_SESSION['profile_img']}";
         ?>
        <div class="user-img" style="background-image: url(<?=$commentImage?>); border: 1px solid #ebebeb"></div>
    </div>
    <input type="hidden" class='token' id="comment-info" data-parent-id='0' data-method="add" data-edit-id="0">
    <div class="col-md-8 col-10">
        <div class="comment-attr" style='display: none;'>
            <div class="reply-info mb-3 btn-group" style='display: none;'  id='reply-bar'>
                <button disabled="disabled" class='btn btn-primary p-2 py-1'>
                    <small class='reply-to'>Replying to</small>
                </button>
                <button class="btn btn-primary cancel-reply p-2 py-1">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="reply-info mb-3 btn-group" style='display: none;'  id='edit-bar'>
                <button disabled="disabled" class='btn btn-primary p-2 py-1'>
                    <small class='edit-info'>Editing <span class="edit-type"></span></small>
                </button>
                <button class="btn btn-primary cancel-edit p-2 py-1">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <textarea name="" id="comment-area" cols="10" rows="8" class="form-control"></textarea>
        <button class="btn btn-success float-end mt-4" id='comment-btn'>Add Comment</button>
    </div>
    <div class="col-0 col md-3"></div>
</div>
<br><br><br>
<div id="comments-box">
    <!-- Fetch Comments -->
    <?php foreach ($data['comments'] as $k => $comment): ?>
    <div class="comment row">
        <div class="col-2 col-md-1">
            <div class="user-img" style="background-image: url(<?=PROFILE_IMG_DIR."/".$comment->profile_img?>); border: 1px solid #ebebeb"></div>
        </div>
        <div class="col-md-8 col-10 mt-1">
            <h6>
                <a href="<?=URLROOT?>/profile?u=<?=$comment->username?>" class='text-decoration-none text-dark d-inline-block me-2'><?=ht($comment->display_name)?></a>
                <small class='text-muted fw-normal' id='username-<?=$comment->id?>'><?=Str::formatEpoch(strtotime($comment->created_at), "d/m/y H:i")?> <?= $comment->is_edited ? "&nbsp;(edited)" : "" ?></small>
            </h6>
            <p id='content-<?=$comment->id?>'><?=ht($comment->content)?></p>
            
            <button data-comment-id="<?=$comment->id?>" class="reaction-btn me-4 react-heart <?= $comment->is_liked ? "reacted" : "" ?>" title='<?=$comment->like_count?> user(s) liked this comment'>
                <img src="<?=URLROOT?>/assets/reaction-heart.png" alt="" class='reaction-img' style='pointer-events: none;'>
                <span class="ps-1 count-heart-<?=$comment->id?>" style='pointer-events: none;'><?=$comment->like_count?></span>
            </button>
            
            <small class="p-2 pt-0 d-inline-block mt-1 text-muted reply-btn" style='cursor: pointer' data-type='comment' data-mention='<?=$comment->username?>' data-parent-id='<?=$comment->id?>' style='cursor: pointer; user-select: none;'>REPLY</small>
            
            <div class="dropup m-0 p-0 d-inline-block float-end">
                <button class="btn btn-white float-end d-inline-block p-0 m-0" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v" style='font-size: 15px'></i>
                </button>
                <ul class="dropdown-menu m-0 float-start comment-dropdown" aria-labelledby="dropdownMenuButton1">
                    <?php if(($_SESSION['user_id'] ?? "") === $comment->user_id): ?>
                    <li class='delete-comment'><a class="dropdown-item" href="javascript:void(0)" data-comment-id="<?=$comment->id?>">Delete</a></li>
                    <li class='edit-comment'><a class="dropdown-item" href="javascript:void(0)" data-comment-id="<?=$comment->id?>" data-type="comment" data-content="<?=ht($comment->content)?>">Edit</a></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item" href="javascript:void(0)" data-comment-id="<?=$comment->id?>">Report</a></li>
                </ul>
            </div>
            
            <?php if($comment->reply_count > 0): ?>
            <button class="btn btn-outline-light text-primary mt-3 ms-0 border d-block expand-replies" data-comment-id="<?=$comment->id?>">
                <?=$comment->reply_count === 1 ? "1 reply" : "$comment->reply_count replies" ?>
                <i class="fas fa-angle-down ps-1 d-inline-block" style='vertical-align: middle;'></i>
            </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="reply-container <?=$comment->reply_count > 0 ? "none" : ""?>" id="reply-<?=$comment->id?>">
        <?php 
            if($comment->reply_count > 0): 
                $replies = $comment->replies;
            
                foreach($replies as $reply): 
        ?>
        <div class="reply-box mt-4 row">
            <div class="col-2 col-md-1"></div>
            <div class="col-md-8 col-10 mt-1 row">
                <div class="col-3 col-md-2">
                    <div class="user-img" style="background-image: url(<?=PROFILE_IMG_DIR."/".$reply->profile_img?>); border: 1px solid #ebebeb"></div>
                </div>
                <div class="col-md-10 col-9 mt-1">
                    <h6>
                        <a href="<?=URLROOT?>/profile?u=<?=$reply->username?>" class='text-decoration-none text-dark d-inline-block me-2'><?=ht($reply->display_name)?></a>
                        <small class='text-muted fw-normal' id='username-<?=$reply->id?>'><?=Str::formatEpoch(strtotime($reply->created_at), "d/m/y H:i")?> <?= $comment->is_edited ? "(edited)" : "" ?></small>
                    </h6>

                    <p id='content-<?=$reply->id?>'><?=ht($reply->content)?></p>

                    <button data-comment-id="<?=$reply->id?>" class="reaction-btn me-4 react-heart <?= $reply->is_liked ? "reacted" : "" ?>" title='<?=$reply->like_count?> user(s) liked this reply'>
                        <img src="<?=URLROOT?>/assets/reaction-heart.png" alt="" class='reaction-img' style='pointer-events: none;'>
                        <span class="ps-1 count-heart-<?=$reply->id?>" style='pointer-events: none;'><?=$reply->like_count?></span>
                    </button>

                    <small class="p-2 pt-0 d-inline-block mt-1 text-muted reply-btn" data-type='reply' data-mention='<?=$reply->username?>'
                    data-parent-id='<?=$comment->id?>' style='cursor: pointer; user-select: none;' >REPLY</small>

                    <div class="dropup m-0 p-0 d-inline-block float-end">
                        <button class="btn btn-white float-end d-inline-block p-0 m-0" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v" style='font-size: 15px'></i>
                        </button>
                        
                        <ul class="dropdown-menu m-0 float-start comment-dropdown" aria-labelledby="dropdownMenuButton1">
                            <?php if(($_SESSION['user_id'] ?? "") === $reply->user_id): ?>
                            <li class='delete-comment'><a class="dropdown-item" href="javascript:void(0)" data-comment-id="<?=$reply->id?>">Delete</a></li>
                            <li class='edit-comment'><a class="dropdown-item" href="javascript:void(0)" data-comment-id="<?=$reply->id?>" data-type="reply" data-content="<?=ht($reply->content)?>">Edit</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="javascript:void(0)" data-comment-id="<?=$reply->id?>">Report</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <br><br>
    <?php endforeach; ?>
</div>

<div class="container text-center m-0 pagination-container">
    <nav aria-label="..." class='d-inline-block mt-5 mb-0 pb-0'>
        <ul class="pagination">
            <li class="page-item <?=$data['current_page'] == 2 || $data['current_page'] == 1 ? "disabled" : "" ?>">
                <a class="page-link" href="<?=URLROOT?>/article/comments/<?="{$data['article']->article_id}/".($data['current_page'] - 1)?>" tabindex="-1" aria-disabled="true">Previous</a>
            </li>
            <?php for ($page = 2; $page <= $data['comment_page_count']; $page++): ?>
            <li class="page-item <?=$page === $data['current_page'] ? "active" : "" ?>">
                <a class="page-link" href="<?=URLROOT?>/article/comments/<?="{$data['article']->article_id}/".$page?>"><?=$page?></a>
            </li>
            <?php endfor; ?>
            <li class="page-item <?=$data['current_page'] == $data['comment_page_count'] ? "disabled" : "" ?>">
                <a class="page-link" href="<?=URLROOT?>/article/comments/<?="{$data['article']->article_id}/".($data['current_page'] + 1)?>" tabindex="-1" aria-disabled="true">Next</a>
            </li>
        </ul>
    </nav>
</div>

<div class="toast align-items-center text-white bg-dark border-0 center-toast m-auto fixed-bottom mb-4 text-center" role="alert" aria-live="assertive" aria-atomic="true" id='message-toast' style='width: 300px!important; padding: 2px; font-size: 15px;'>
    <div class="d-flex">
        <div class="toast-body container show-save text-center">

        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto toast-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>

<script src="<?=URLROOT?>/js/comment.js" type="module"></script>