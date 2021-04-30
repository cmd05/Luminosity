<?php 

declare(strict_types = 1); 

class Session {
    // Flash Message Helper
    public static function flash(string $name = '', string $msg = '', string $class = 'alert alert-success mb-3'): void {
        if(!empty($name)) {
            $sessionClass = $name.'_class';
            if(!empty($msg) && empty($_SESSION[$name])) {
                if(!empty($_SESSION[$sessionClass])) unset($_SESSION[$sessionClass]);
    
                $_SESSION[$name] = $msg;
                $_SESSION[$sessionClass] = $class;
            }   else if(empty($msg) && !empty($_SESSION[$name])) {
                $class = !empty($_SESSION[$sessionClass]) ? $_SESSION[$sessionClass] : '';
                echo "<div class='$class' id='msg-flash'>".$_SESSION[$name]."</div>";
                unset($_SESSION[$name], $_SESSION[$sessionClass]);
            }
        }
    }

    public static function alert(string $name = '', string $msg = '', string $class = 'alert alert-success mb-3'): void {
        if(!empty($name)) {
            $sessionClass = $name.'_class';
            if(!empty($msg) && empty($_SESSION[$name])) {
                if(!empty($_SESSION[$sessionClass])) unset($_SESSION[$sessionClass]);
    
                $_SESSION[$name] = $msg;
                $_SESSION[$sessionClass] = $class;
            }   else if(empty($msg) && !empty($_SESSION[$name])) {
                $class = !empty($_SESSION[$sessionClass]) ? $_SESSION[$sessionClass] : '';
                echo '<div class="'.$class.'" role="alert">
                        '.$_SESSION[$name].'
                        <button type="button" class="btn-close float-end" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
                unset($_SESSION[$name], $_SESSION[$sessionClass]);
            }
        }
    }

    public static function sessionSet(array $arr): void {
        foreach ($arr as $key => $value) $_SESSION[$key] = $value;
    }

    public static function sessionUnset(array $arr): void {
        foreach ($arr as $key => $value) unset($_SESSION[$key]);
    }

    public static function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }
    
    public static function csrfToken(): string {
        return $_SESSION['csrf_token'];
    }

    public static function redirectUser(): void {
        if(self::isLoggedIn()) Server::redirect("home");
    }

    public static function userProfilePath(): string {
        return URLROOT.'/uploads/'.($_SESSION['profile_img'] ?? 'alt');
    }
}
