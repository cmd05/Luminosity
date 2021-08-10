    <input type='hidden' class='token' id="ajax_csrf" value='<?=$_SESSION['csrf_token']?>'>
    <?=View::formToken(URLROOT, "app_url")?>
</body>
</html>
<?php ob_end_flush(); ?>