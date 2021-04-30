<form style="display: none; visibility: hidden;" action="<?=URLROOT?>/user-actions/logout" method="post">
    <input name='logout' value='true' readonly>
    <?=View::formToken()?>
    <button type="submit" id="logout_btn"> </button>
</form>
<!-- Nav items 2 -->
<ul class="navbar-nav ms-auto mb-2 mb-md-0" id='nav-items-2'>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle img-dropdown" href="#" id="user-options-lg" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <div class='nav-img' style='background-image: url("<?=Session::userProfilePath()?>")'></div>
        </a>
        <ul class="dropdown-menu dropdown-menu user-dropdown-menu" aria-labelledby="user-options-lg" style='left: -95px; width: 205px'>
            <li>
                <a class="dropdown-item mb-2 hover-light" tabindex="-1" aria-disabled="true" href='<?=URLROOT?>/profile?u=<?=ht($_SESSION['username'])?>'>
                <span class='text-dark pb-1 d-block'><?=ht($_SESSION['display_name'])?></span>
                <span class='me-auto d-block text-secondary' style='font-size: 14px; margin-top: -2px;'>@<?=ht($_SESSION['username'])?></span>
                </a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item hover-primary" href="<?=URLROOT?>/write"><i class="fas fa-plus pe-2 py-2"></i> New Article</a></li>
            <li><a class="dropdown-item hover-primary" href="<?=URLROOT?>/write/drafts"><i class="fas fa-scroll pe-2 py-2"></i> Saved Drafts</a></li>
            <li><a class="dropdown-item hover-primary" href="<?=URLROOT?>/write/articles"><i class="far fa-newspaper pe-2 py-2"></i> My Articles</a></li>
            <li><a class="dropdown-item hover-primary" href="<?=URLROOT?>/bookmarks"><i class="fas fa-bookmark pe-2 py-2"></i> Bookmarks</a></li>
            <li><a class="dropdown-item hover-primary" href="#"><i class="fas fa-chart-line pe-2 py-2"></i> Stats</a></li>
            <li>
                <a class="dropdown-item py-2 theme-toggle-link py-2" href="#">
                    <i class="fas fa-cloud-moon pe-2"></i> Night Sky
                    <div class="form-check form-switch d-inline float-end pe-0 me-0 toggle-theme" style='transform: scale(1.2);'>
                        <input class="form-check-input" type="checkbox">
                    </div>
                </a>
            </li>
            <li><a class="dropdown-item hover-primary" href="<?=URLROOT?>/settings"><i class="fas fa-cog pe-2 py-2"></i> Settings</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <label class="dropdown-item" for='logout_btn' style='cursor: pointer'>
                Sign Out
                </label>
            </li>
        </ul>
    </li>
</ul>
<!-- Nav items 3 - only  -->
<ul class="navbar-nav ms-auto mb-md-0" id='nav-items-3'>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle img-dropdown" href="#" id="user-options-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <div class='nav-img' style='background-image: url("<?=Session::userProfilePath()?>")'></div>
        </a>
        <ul class="dropdown-menu dropdown-menu user-dropdown-menu" aria-labelledby="user-options-sm" style='left: -100px; width: 190px'>
            <li>
                <a class="dropdown-item mb-2 hover-light" tabindex="-1" aria-disabled="true" href='<?=URLROOT?>/profile?u=<?=ht($_SESSION['username'])?>'>
                <span class='text-dark pb-1 d-block'><?=ht($_SESSION['display_name'])?></span>
                <span class='me-auto d-block text-secondary' style='font-size: 14px; margin-top: -2px;'>@<?=ht($_SESSION['username'])?></span>
                </a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item hover-primary" href="<?=URLROOT?>/write"><i class="fas fa-plus pe-2 py-2"></i> New Article</a></li>
            <li><a class="dropdown-item hover-primary" href="<?=URLROOT?>/write/drafts"><i class="fas fa-scroll pe-2 py-2"></i> Saved Drafts</a></li>
            <li><a class="dropdown-item hover-primary" href="<?=URLROOT?>/write/articles"><i class="far fa-newspaper pe-2 py-2"></i> My Articles</a></li>
            <li><a class="dropdown-item hover-primary" href="<?=URLROOT?>/bookmarks"><i class="fas fa-bookmark pe-2 py-2"></i> Bookmarks</a></li>
            <li><a class="dropdown-item hover-primary" href="#"><i class="fas fa-chart-line pe-2 py-2"></i> Stats</a></li>
            <li>
                <a class="dropdown-item py-2 theme-toggle-link py-2" href="#">
                    <i class="fas fa-cloud-moon pe-2"></i> Night Sky
                    <div class="form-check form-switch d-inline float-end pe-0 me-0 toggle-theme" style='transform: scale(1.2);'>
                        <input class="form-check-input" type="checkbox">
                    </div>
                </a>
            </li>
            <li><a class="dropdown-item hover-primary" href="<?=URLROOT?>/settings"><i class="fas fa-cog pe-2 py-2"></i> Settings</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <label class="dropdown-item" for='logout_btn' style='cursor: pointer'>
                Sign Out
                </label>
            </li>
        </ul>
    </li>
</ul>