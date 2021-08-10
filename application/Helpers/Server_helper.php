<?php 

declare(strict_types = 1); 

class Server {
    /**
     * Include helper functions for server methods
     * POST, GET, REQUEST etc
     */
    public static function postParamsExist(array $postArray, array $params, bool $checkCSRF = true): bool {
        if($checkCSRF) $params[] = 'csrf_token';

        foreach ($params as $param) 
            if(!isset($postArray[$param])) return false;

        // If token required and equal to session token OR not required -- return true
        return ($checkCSRF && hash_equals($_SESSION['csrf_token'], $postArray['csrf_token'] ?? '')) 
                || !$checkCSRF;
    }

    public static function checkPostReq(array $params, bool $die_404 = false, bool $checkCSRF = true): bool {
        if($_SERVER['REQUEST_METHOD'] === "POST") {
            return self::postParamsExist($_POST, $params, $checkCSRF);
        }   else {
            if($die_404) Server::die_404();
            return false;
        }
    }

    public static function getParamsExist(array $getArray, array $params): bool {
        foreach ($params as $param) 
            if(!isset($getArray[$param])) return false;

        return true;
    }

    public static function redirect($location): void {
        header("Location: ". URLROOT."/$location");
    }
    
    public static function _404(): void {
        self::redirect('err/_404');
    }

    public static function die_404(): void {
        die(require_once(APPROOT.'/Views/errors/404.php'));
    }
    // Something went wrong - 500
    public static function _500(): void {
        self::redirect('err/_500');
    }

    public static function jsonHeader(): void {
        header('Content-Type: application/json');
    }

    public static function getRequestUrl(): string {
        $query = substr($_SERVER['REQUEST_URI'], strlen(BASE_FOLDER)); // Remove root folder name from request URL
        $query = trim($query, '/ '); // Remove traliing and preceeding forward slashes and spaces
        $query = preg_replace('/\\?.*/', '', $query); // Remove GET Parameters
        $query = filter_var($query, FILTER_SANITIZE_URL); // Sanitize URL

        return $query;
    }
}