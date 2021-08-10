<link rel="stylesheet" href="<?=URLROOT?>/css/navbar.css">
<link rel="stylesheet" href="<?=URLROOT?>/css/user-navbar.css">

<script src="<?=URLROOT?>/js/toggle-theme.js" type='module'></script>
<script src="<?=URLROOT?>/js/custom-navbar.js" type='module'></script>

<form style="display: none; visibility: hidden;" action="<?=URLROOT?>/user-actions/logout" method="post">
    <input name='logout' value='true' readonly>
    <?=View::formToken()?>
    <button type="submit" id="logout_btn"> </button>
</form>
<!-- Nav Super Container -->
<div class="nav-container-super fixed-top">
    <!-- Main Navbar -->
    <nav class="navbar navbar-expand navbar-dark bg-dark ">
        <div class="container-fluid px-lg-5">
            <!-- Mobile Toggler -->
            <button class="btn nav-toggler p-1 me-2 shadow-none" id='nav-toggle-mobile'>
            <i class="fas fa-bars" style='color: white; -webkit-text-stroke: 0px white;'></i>
            </button>
            <!-- Nav Brand -->
            <a class="navbar-brand pe-3" href="<?=URLROOT?>">
            <img src="<?=URLROOT?>/assets/logo.png" class='navbar-logo mb-1'>
            <span class='nav-brand-title'><?=SITENAME?></span>
            </a>
            <!-- Nav Items -->
            <div class="navbar-collapse">
                <!-- Nav Items 1 -->
                <ul class="navbar-nav me-auto mb-2 mb-md-0" id='nav-items-1'>
                    <?php foreach($customNavItems as $name => $url): ?>
                        <li class="nav-item mx-1">
                            <a class="nav-link <?=View::activeLink("$url")?>" href="<?=URLROOT."/$url"?>"><?=$name?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <!-- Nav items 2 -->
                <?php require_once 'user-dropdown.php' ?>
            </div>
        </div>
    </nav>
    <div style='margin-bottom: -20px;'></div>
    <!-- Mobile /  Medium Nav 2 -->
    <nav class="navbar navbar-light bg-light" aria-label="First navbar example" style='z-index: -1' id='main-nav-2'>
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-2" aria-controls="nav-2" aria-expanded="true" aria-label="Toggle navigation" id='toggle-nav-2'>
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="navbar-collapse collapse" id="nav-2" style='line-height: 25px;'>
                <br>
                <ul class="navbar-nav me-auto mb-2 mt-2">
                    <?php foreach($customNavItems as $name => $url): ?>
                        <li class="nav-item mx-1">
                            <a class="nav-link <?=View::activeLink("$url")?>" href="<?=URLROOT."/$url"?>"><?=$name?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>
</div>