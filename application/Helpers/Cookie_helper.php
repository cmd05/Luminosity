<?php 

declare(strict_types = 1); 

class Cookie {
    public static function createCookie(string $cookieName, $value, int $time, $domainWide = true): void {
        setcookie($cookieName, $value, time() + $time, $domainWide ? '/' : '');
    }

    public static function destroyCookie(string $cookieName): bool {
        if (isset($_COOKIE[$cookieName])) {
            unset($_COOKIE[$cookieName]);
            self::createCookie($cookieName, "", -1); 
            return true;
        }
        return false;
    }
}