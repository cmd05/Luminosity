<?php 

declare(strict_types = 1); 

class Image {
    public static function isValidImg($img, $mb, $allowedTypes = [IMAGETYPE_PNG, IMAGETYPE_JPEG]): bool {
        if($img['tmp_name']) {
            $detectedType = @exif_imagetype($img['tmp_name']);
            $bytes = $mb * 1048576;

            return in_array($detectedType, $allowedTypes) && $img['size'] <= $bytes;
        }
        return false;
    }

    public static function validateImgUrl(string $url): bool {
        // Allow Error Image
        if(trim($url) === IMG_404_PATH) return true;
        
        if(filter_var($url, FILTER_VALIDATE_URL) && strpos($url, IMG_VALIDATE_URL) === 0) {
            $headers = get_headers($url, true);
            if (strpos($headers['Content-Type'], 'image/') === 0 && strpos($headers[0], '200') !== false) 
                return true;
        }

        return false;
    }
}