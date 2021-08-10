<?php 

declare(strict_types = 1); 

class Str {
    public static function emptyStrings(array $arr): bool {
        foreach ($arr as $value) 
            if(!empty($value)) return false;

        return true;
    }

    public static function stripSpaces(string $str): string {
        return preg_replace('/\s+/', '', $str);
    }

    public static function stripNewLines(string $str): string {
        return str_replace(array("\n", "\r"), '', $str);
    }

    public static function strip2lines(string $str): string {
        return preg_replace("/[\r\n]+/", "\n", $str);
    }

    public static function isEmptyStr(string $val): bool {
        $val = self::stripSpaces($val);
        if($val === "") return true;
        return false;
    }

    public static function truncateString(string $str, int $len, bool $ellipsis = true): string {
        $result = substr($str, 0, $len);
        if($ellipsis && strlen($str) > $len) $result .= "...";
        return $result;
    }
    
    public static function formatEpoch(int $epoch, string $format): string {
        $dt = new DateTime("@$epoch");
        return $dt->format($format);
    }

    public static function isValidEmail(string $email): bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) <= 300;
    }

    public static function isValidPassword(string $password): bool {
        return preg_match("/^(?=.*\d).{8,}$/", $password) && strlen($password) <= 100;
    }

    public static function isValidDisplayName(string $name): bool {
        $name = self::stripSpaces($name);
        return strlen($name) <= 30;
    }

    public static function isValidUserName(string $username): bool {
        // only a-z 0-9 _$
        return preg_match('/^[a-zA-Z0-9_$]*$/', $username) && strlen($username) <= 30;
    }

    public static function trimWhiteSpaces(string $str): string {
        return preg_replace('/\s+/', '', $str);
    }

    public static function replaceFirstOcc(string $needle, string $replace, string $haystack): string {
        $pos = strpos($haystack, $needle);
        return $pos !== false ? substr_replace($haystack, $replace, $pos, strlen($needle)) : $haystack;
    }
}