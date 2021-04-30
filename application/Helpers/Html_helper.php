<?php 

declare(strict_types = 1); 

$GLOBALS['HTML_CONFIG'] = \HTMLPurifier_Config::createDefault();

$GLOBALS['HTML_CONFIG']->set('HTML.SafeIframe', true);
$GLOBALS['HTML_CONFIG']->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
$GLOBALS['HTML_CONFIG']->set('HTML.Allowed', 'h2,p,u,strong,s,a[href],img[src],iframe[src],br,em,code,ul,ol,li,pre,blockquote,sup,sub');
$GLOBALS['HTML_TAGS_ALLOWED'] = '<h2><p><u><strong><s><a><img><iframe><br><em><code><ul><ol><li><pre><blockquote><sup><sub>';

class Html {
    public static function purifyHTML(string $dirtyHTML): string {
        $purifier = new HTMLPurifier($GLOBALS['HTML_CONFIG']);
        $sanitized = $purifier->purify($dirtyHTML);
        $sanitized = self::sanitizeImages($sanitized);
        // Keep span content but remove tag
        $sanitized = strip_tags($sanitized, $GLOBALS['HTML_TAGS_ALLOWED']);

        return $sanitized;
    }

    public static function sanitizeImages(string $dirtyHTML): string {
        $doc = self::newDoc($dirtyHTML);

        $imgs = $doc->getElementsByTagName('img');
        foreach($imgs as $img) {
            $src = $img->getAttribute('src');
            if(!Image::validateImgUrl($src)) {
                $img->setAttribute('src', URLROOT.'/assets/img-not-found.png');
            }
        }   
                 
        return $doc->saveHTML();
    }

    public static function getTagResults(string $html, string $tag) {
        $doc = self::newDoc($html);
        return $doc->getElementsByTagName($tag);
    }

    public static function tagCount(string $content, string $tag): int {
        $doc = self::newDoc($content);
        $count = count($doc->getElementsByTagName($tag));
        
        return $count;
    }

    public static function newDoc(string $html) {
        $doc = new DOMDocument();
        $page = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");

        libxml_use_internal_errors(true);
        if(Str::isEmptyStr($page)) $page = "<p></p>";
        $doc->loadHTML($page, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        return $doc;
    }

    public static function getChars(string $html): string {
        $doc = self::newDoc($html);
        $text = $doc->textContent;
        $chars = (preg_replace("/\n|\r/i", "", $text));
        return $chars;
    }

    public static function getCharCount(string $html): int {
        return mb_strlen(self::getChars($html));
    }

    public static function isHTML(string $string): bool {
        return $string !== strip_tags($string);
    }
}