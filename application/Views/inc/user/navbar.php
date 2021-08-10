<link rel="stylesheet" href="<?=URLROOT?>/css/navbar.css">
<link rel="stylesheet" href="<?=URLROOT?>/css/user-navbar.css">
<script src="<?=URLROOT?>/js/toggle-theme.js" type='module'></script>
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
                    <li class="nav-item mx-1">
                        <a class="nav-link <?=View::activeLink('/bookmarks')?>" href="<?=URLROOT?>/bookmarks">Bookmarks</a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link <?=View::activeLink('/explore')?>" href="<?=URLROOT?>/explore" tabindex="-1">Explore</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a href="<?=URLROOT?>/write" class="btn text-dark nav-link btn-light py-1 ms-2 border-0 article-btn" style='margin-top: 5px; font-size: 15px;'>
                        <i class="fas fa-plus new-article-icon"></i>
                        New Article
                        </a>
                    </li>
                </ul>
                <!-- Search Form -->
                <form class="d-flex" id='search-box' method="get" action="<?=URLROOT?>/explore/search">
                    <span class="search-combo">Ctrl + /</span>
                    <input class="form-control rounded-0 rounded-start shadow-none" type="text" placeholder="            Search..." aria-label="Search" id='nav-search-input' name='q' autocomplete="OFF">
                    <div class="search-results-1 search-live border">
                        <div class="row p-2">
                            <div class="col-12 pb-3">
                                <div class="d-block">
                                    <a href="" class='results-header d-inline-block results-users-link'>Users</a>
                                    <i class="fas fa-arrow-right ps-1" style='font-size: 14px;'></i>
                                </div>
                            </div>

                            <div class="user-results-container"></div>
                            
                            <div class="col-12 pb-3">
                                <div class="d-block">
                                    <a href="" class='results-header d-inline-block results-articles-link'>Articles</a>
                                    <i class="fas fa-arrow-right ps-1" style='font-size: 14px;'></i>
                                </div>
                            </div>

                            <div class="articles-results-container"></div>
                        </div>
                    </div>
                    <button class="btn btn-success py-0 rounded-0 rounded-end ms-0 border-0" type="submit" id='nav-search-btn'>
                    <i class="fas fa-search"></i>
                    </button>
                </form>
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
                <form class='m-auto container' id='mobile-search-form' method='get' action='<?=URLROOT?>/explore/search'>
                    <input class="form-control d-inline me-0 rounded-0 rounded-start m-0 float-start mobile-search-input" type="text" placeholder="Search" aria-label="Search" name='q' autocomplete="off">
                    <div class="search-results-2 search-live border">
                        <div class="row p-2">
                            <div class="col-12 pb-3">
                                <div class="d-block">
                                    <a href="" class='results-header d-inline-block results-users-link'>Users</a>
                                    <i class="fas fa-arrow-right ps-1" style='font-size: 14px;'></i>
                                </div>
                            </div>

                            <div class="user-results-container"></div>
                            
                            <div class="col-12 pb-3">
                                <div class="d-block">
                                    <a href="" class='results-header d-inline-block results-articles-link'>Articles</a>
                                    <i class="fas fa-arrow-right ps-1" style='font-size: 14px;'></i>
                                </div>
                            </div>

                            <div class="articles-results-container"></div>
                        </div>
                    </div>
                    <button class="btn btn-success rounded-0 rounded-end ms-0 border-0 m-0 mb-1" type="submit" style="height: 38px"><i class="fas fa-search"></i></button>
                </form>
                <ul class="navbar-nav me-auto mb-2 mt-2">
                    <li class="nav-item mx-1">
                        <a class="nav-link <?=View::activeLink('/bookmarks')?>" href="<?=URLROOT?>/bookmarks">Bookmarks</a>
                    </li>
                    <li class="nav-item mx-1">
                        <a class="nav-link <?=View::activeLink('/explore')?>" href="<?=URLROOT?>/explore" tabindex="-1">Explore</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a href="<?=URLROOT?>/write" class="btn text-dark nav-link border border-light py-1 ms-2 article-btn mobile-article-btn" style='margin-top: 5px; font-size: 15px;'>
                        <i class="fas fa-plus new-article-icon"></i>
                        New Article
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>