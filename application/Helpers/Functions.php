<?php 

declare(strict_types = 1); 

function dbgArr($var): void {
    $backTrace = debug_backtrace();
    $file = $backTrace[0]['file'];
    $line = $backTrace[0]['line'];

    ob_start(); 
    call_user_func_array('var_dump', $var);

    echo "<br><br><pre class='pretty-print' style='color: blue; margin-left: 20px; margin-top: 80px;'>
    Line $line in <br>$file: <br><br>".htmlentities(ob_get_clean())."</pre>";
}

function dbg($var): void {
    $backTrace = debug_backtrace();
    $file = $backTrace[0]['file'];
    $line = $backTrace[0]['line'];

    echo "<pre class='pretty-print' style='color: blue; margin-left: 20px; margin-top: 80px;'>Line $line in <br>$file: <br><br>";
    var_dump($var);
    echo "</pre>";
}

function ht($value, int $truncateLen = NULL) {
    $value = htmlspecialchars($value); 
    if(!is_null($truncateLen)) $value = Str::truncateString($value, $truncateLen);
    
    return $value;
}