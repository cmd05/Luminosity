<?php 

declare(strict_types = 1); 

class Utils {
    public static function prettyPrint($var): void {
        $backTrace = debug_backtrace();
        $file = $backTrace[0]['file'];
        $line = $backTrace[0]['line'];

        echo "<pre class='pretty-print'>Line $line in <br>$file: <br><br>";
        var_dump($var);
        echo "</pre>";
    }
    
    public static function typeOf($var): string {
        switch (gettype($var)) {
            case "array":
                return "array";
                break;
            case "integer":
                return "int";
                break;
            case "double":
                return "float";
                break;     
            case "NULL":
                return "null";
                break;
            case "boolean":
                return "bool";
                break;
            default:
                return "string";
                break;
        }
    }

    public static function randToken($bytes = 32): string {
        return bin2hex(random_bytes($bytes));
    }

    public static function unsetNullArray(array $arr) {
        foreach($arr as $key => $value) if(Str::isEmptyStr($value)) unset($arr[$key]); // Remove Empty values
        return $arr;
    }

    // Empty String and Array support 
    public static function isNull($var): bool {
        if(empty($var) || is_null($var)) return true;
        return false;
    } 

    public static function trimArrayStr(array $arr): array {
        foreach($arr as $key => $value) if(is_string($value)) $arr[$key] = trim($value, ' '); // Remove Empty array_values
        return $arr;
    }

    public static function diffArr(array $input, array $allowed): array {
        $res = [];
        foreach ($allowed as $value) $res[$value] = $input[$value];
        return $res;
    }
}