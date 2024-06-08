<link rel="stylesheet" href="<?=URLROOT?>/css/footer.css">
<div class="container">
    <footer class="pt-4 my-md-5 pt-md-5 border-top" id='page-footer'>
        <div class="row">
            <div class="col-12 col-md">
                <a href="#">
                <img class="mb-3 ms-4" src="<?=URLROOT?>/assets/logo.png" alt="" width="45">
                </a>
                <small class="d-block mb-3 text-muted">© 2020-2021</small>
            </div>
            <div class="col-6 col-md">
                <h5>Resources</h5>
                <ul class="list-unstyled text-small">
                    <li><a class="link-secondary <?=View::activeFooter('')?>" href="<?=URLROOT?>">Home</a></li>
                    <li><a class="link-secondary <?=View::activeFooter("/explore")?>" href="<?=URLROOT?>/explore">Explore</a></li>
                    <li><a class="link-secondary <?=View::activeFooter("/info/privacy")?>" href="<?=URLROOT?>/info/privacy">Privacy Policy</a></li>
                </ul>
            </div>
            <div class="col-6 col-md">
                <h5>Developers</h5>
                <ul class="list-unstyled text-small">
                    <li><a class="link-secondary <?=View::activeFooter("/info/contribute")?>" href="<?=URLROOT?>/info/contribute">Contribute</a></li>
                    <li><a class="link-secondary <?=View::activeFooter("/info/api")?>" href="<?=URLROOT?>/info/api">API</a></li>
                </ul>
            </div>
            <div class="col-6 col-md">
                <h5>Users</h5>
                <ul class="list-unstyled text-small">
                    <li><a class="link-secondary <?=View::activeFooter("/user/sign-up")?>" href="<?=URLROOT?>/user/sign-up">Sign Up</a></li>
                    <li><a class="link-secondary <?=View::activeFooter("/user/login")?>" href="<?=URLROOT?>/user/login">Login</a></li>
                </ul>
            </div>
            <div class="col-6 col-md">
                <ul class="list-unstyled text-small">
                    <li><a class="link-primary" href="#">Back To Top <i class="fas fa-caret-up"></i></a></li>
                </ul>
            </div>
        </div>
    </footer>
</div>