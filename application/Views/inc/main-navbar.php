<?php

$dir = !Session::isLoggedIn() ? "guest" : "user";
require_once "$dir/navbar.php";

?>

<!-- Common Nav File -->
<script src="<?=URLROOT?>/js/navbar.js" type="module"></script>
<div style='margin-bottom: 60px;'></div>