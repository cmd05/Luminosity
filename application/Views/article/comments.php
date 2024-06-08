<?php View::header(true, $data['article']->title." - comments") ?>

<br><br>

<?php if(!Session::isLoggedIn()) require_once APPROOT."/Views/inc/login-modal.php" ?>

<div class="container m-auto">
    <a href="<?=URLROOT?>/article?a=<?=$data['article']->article_id?>" class="btn btn-dark">
        <i class="fas fa-arrow-left pe-2"></i>
        Return to Article
    </a>

    <br><br>
    <h4>Comments</h4>
    <br><br>
    
    <?php require_once "comment-section.php"; ?>
</div>

<?=View::formToken($data['article']->article_id, "article_id")?>
<?php View::footer() ?>