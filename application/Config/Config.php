<?php

/**
 * Config using environment variables. 
 * 
 * If your hosting does not support env:
 * - Delete this file
 * - Fill up configs in ConfigDefault.php
 * - Rename ConfigDefault.php to Config.php
 * 
 * Bootstrap.php includes only file named ``Config.php``
 */

$dbopts = parse_url(getenv('CLEARDB_DATABASE_URL'));

define("DB_HOST", $dbopts["host"]);
define("DB_USER", $dbopts["user"]);
define("DB_PASS", $dbopts["pass"]);
define("DB_NAME", ltrim($dbopts["path"],'/'));
define('DB_TIMESTAMP_FMT', 'Y-m-d H:i:s');

define("SERVER_TIMEZONE", $_ENV['SERVER_TIMEZONE']);

// App root
define('APPROOT', dirname(__DIR__));

// URL ROOT
define('URLROOT', $_ENV['URLROOT']);

// Site Name
define('SITENAME', $_ENV['SITENAME']);

// App Version
define('APP_VERSION', $_ENV['APP_VERSION']);

// Unique Session Name
define('SESSION_NAME', SITENAME);

// Website Logo Path
define("LOGO_PATH", URLROOT."/assets/logo.png");

// Uploads Path
define('UPLOAD_PATH', dirname(dirname(__DIR__)).'/public/uploads/');
// Directory of profile images
define('PROFILE_IMG_DIR', URLROOT.'/uploads');
// Default profile image
define("DEFAULT_PROFILE_NAME", 'default-profile.png');
define("DEFAULT_PROFILE_PATH", PROFILE_IMG_DIR."/".DEFAULT_PROFILE_NAME);

// Mail Server
$mailParams = [
    "SMTP_HOST", "SMTP_USERNAME", "SMTP_MAIL", "SMTP_PASSWORD"
];

foreach ($mailParams as $var) define($var, $_ENV[$var]);

define("SMTP_PORT", (int) $_ENV['SMTP_PORT']);

// Email CHECK API www.ipqualityscore.com/api/json/email/API_TOKEN
define('EMAIL_API_TOKEN', $_ENV['EMAIL_API_TOKEN']);
define('EMAIL_API', 'https://www.ipqualityscore.com/api/json/email/'.EMAIL_API_TOKEN.'/');

// Image upload to cloudinary
define('IMG_CLOUD_NAME', $_ENV['IMG_CLOUD_NAME']);
define('IMG_UPLOAD_URL', 'https://api.cloudinary.com/v1_1/'.IMG_CLOUD_NAME.'/image/upload');
define('IMG_API_SECRET', $_ENV['IMG_API_SECRET']);
define('IMG_API_KEY', $_ENV["IMG_API_KEY"]);
define('IMG_API_PRESET', $_ENV['IMG_API_PRESET']);
define('IMG_VALIDATE_URL', 'https://res.cloudinary.com/'.IMG_CLOUD_NAME.'/image/upload');
define("IMG_404_PATH", URLROOT.'/assets/img-not-found.png');