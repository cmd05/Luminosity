<?php 

declare(strict_types = 1); 

class DocBlock {
    public static function getTags(object $class, string $method): array {
        $class = new ReflectionClass($class);
        $reflectedMethod = $class->getMethod($method);
        
        // Retrieving documentation comments
        $docComments = $reflectedMethod->getDocComment(); 

        if(!$docComments) return [];
        
        return preg_match_all('/@(\w+)\s+(.*)\r?\n/m', $docComments, $matches) ? array_combine($matches[1], $matches[2]) : [];
    }

    public static function tagValueToArray(object $class, string $method, string $key): array {
        $tags = self::getTags($class, $method);
        $value = $tags[$key];
        $value = str_replace(str_split('[] '), "", $value);
        $value = Str::trimWhiteSpaces($value);

        return $value === "" ? [] : explode(",", $value);
    }
}