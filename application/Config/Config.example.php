<?php
/**
 * Default configurations for setting up application
 * All configurations are globally available
 * 
 * @author cmd3BOT
 */

 
/**
 * Connect to MySQL database
 * 
 * Database credentials and parameters
 * Create a database in MySQL called luminosity and load dump.sql
 */
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "luminosity");
define('DB_TIMESTAMP_FMT', 'Y-m-d H:i:s');

/**
 * Site Settings
 * 
 * Server Settings and Path locations
 */
define("ENVIRONMENT", "local"); // use local or prod

define("SERVER_TIMEZONE", 'Asia/Kolkata');
define('APPROOT', dirname(dirname(__FILE__)));

$basePath = ENVIRONMENT === "local" ? '/'.basename(dirname(__DIR__, 2)) : '';
define('BASE_FOLDER', $basePath);
define('URLROOT', "http://localhost".BASE_FOLDER);

define('SITENAME', 'Luminosity');
define('APP_VERSION', '1.1.0');
define('SESSION_NAME', SITENAME);

/**
 * App Image Directories
 */
define("LOGO_PATH", URLROOT."/assets/logo.png");
define('UPLOAD_PATH', dirname(dirname(dirname(__FILE__))).'/public/uploads/');
define('PROFILE_IMG_DIR', URLROOT.'/uploads');
define("DEFAULT_PROFILE_NAME", 'default-profile.png');
define("DEFAULT_PROFILE_PATH", PROFILE_IMG_DIR."/".DEFAULT_PROFILE_NAME);

/**
 * Connect to SMTP Server
 * 
 * Check your SMTP mail settings to avoid errors
 * Default port is 587
 */
define("SMTP_HOST", 'smtp.gmail.com');
define("SMTP_USERNAME", '...');
define("SMTP_MAIL", '...');
define("SMTP_PASSWORD", '...');
define("SMTP_PORT", 587);

/**
 * Email validation API
 * [IPQS](https://www.ipqualityscore.com/email-verification)
 */
define('EMAIL_API_TOKEN', '...');
define('EMAIL_API', 'https://www.ipqualityscore.com/api/json/email/'.EMAIL_API_TOKEN.'/');

/**
 * Setup remote host for bulk image storage
 * [Cloudinary](https://cloudinary.com/documentation/image_upload_api_reference)
 */
define('IMG_CLOUD_NAME', 'demo');
define('IMG_UPLOAD_URL', 'https://api.cloudinary.com/v1_1/'.IMG_CLOUD_NAME.'/image/upload');
define('IMG_API_SECRET', '');
define('IMG_API_KEY', '');
define('IMG_API_PRESET', 'docs_upload_example_us_preset');
define('IMG_VALIDATE_URL', 'https://res.cloudinary.com/'.IMG_CLOUD_NAME.'/image/upload');
define("IMG_404_PATH", URLROOT.'/assets/img-not-found.png');