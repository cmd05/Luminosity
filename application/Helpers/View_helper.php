<?php 

declare(strict_types = 1); 

class View {
    public static function header(bool $defaultNav = true, string $title = SITENAME): void {
        // Title used in header.php
        require_once APPROOT."/Views/inc/header.php";
        if($defaultNav) {
            require_once APPROOT.'/Views/inc/main-navbar.php';
        }
    }

    public static function footer(bool $defaultFooter = true): void {
        require_once APPROOT."/Views/inc/main-footer.php";
        if($defaultFooter) {            
            $dir = !Session::isLoggedIn() ? "guest" : "user";
            require_once APPROOT."/Views/inc/$dir/footer.php";
        }
    }

    public static function customNav(array $customNavItems = []): void {
        require_once APPROOT.'/Views/inc/user/custom-navbar.php';
    }

    public static function activeLink(string $link): string {
        $url = Server::getRequestUrl();
        $link = trim($link, '/');

        return $link === $url ? "active" : "";
    }
    
    public static function activeFooter(string $link): string {
        $url = Server::getRequestUrl();
        $link = trim($link, '/');

        return $link === $url ? "text-dark" : "";
    }

    public static function formToken($token = NULL, $name = ""): string {
        // Session::csrfToken in param gives constant error
        // Add csrf_token if token not given
        if(is_null($token)) {
            $name = "csrf_token";
            $token = Session::csrfToken();
        }

        return "<input type=\"hidden\" name=\"$name\" class='token' value=\"". $token . "\">";
    }
}