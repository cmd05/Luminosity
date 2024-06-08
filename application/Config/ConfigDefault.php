<?php
/**
 * Default configurations for setting up application
 * 
 * Running instance of the application first requires complete configuration file.
 * All constants are global
 * It is reccomended to carefully check all credentials and details to prevent unexpected **errors**.
 * 
 * @author cmd3BOT
 */

/**
 * Database credentials and parameters
 */
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "");
define('DB_TIMESTAMP_FMT', 'Y-m-d H:i:s');

/**
 * Site Settings
 */
define("SERVER_TIMEZONE", '');
define('SITENAME', 'Luminosity');
define('URLROOT', "localhost/".SITENAME); // use as base url for entire site
define('SESSION_NAME', SITENAME); // Set user session name to the website
define('APP_VERSION', '1.0.0');
define('APPROOT', dirname(dirname(__FILE__))); // directory containing application backend


/**
 * Website Image Directories
 */

define("LOGO_PATH", URLROOT."/assets/logo.png"); // logo of the website
define('UPLOAD_PATH', dirname(dirname(dirname(__FILE__))).'/public/uploads/'); // system path for uploading user images programatically
define('PROFILE_IMG_DIR', URLROOT.'/uploads'); // public directory to access user profile images
define("DEFAULT_PROFILE_NAME", 'default-profile.png'); // File name of default user profile image
define("DEFAULT_PROFILE_PATH", PROFILE_IMG_DIR."/".DEFAULT_PROFILE_NAME); // Set default image public path 

/**
 * Settings to setup mail server
 * 
 * Avoid incorrect credentials or settings to decrease potential errors
 * Some mailing hosts may require access from lesser known sources.
 */
define("SMTP_HOST", '');
define("SMTP_USERNAME", '');
define("SMTP_MAIL", '');
define("SMTP_PASSWORD", '');
define("SMTP_PORT", 587);

/**
 * Email check up API for user sign up
 * 
 * Application requires user email authentication. 
 * Prevents spam email and checks for trusted source
 * Check [IP Quality Score](https://www.ipqualityscore.com) for more info
 */
define('EMAIL_API_TOKEN', '');
define("EMAIL_API_URL", 'https://www.ipqualityscore.com/api/json/email');
define('EMAIL_API', EMAIL_API_URL.'/'.EMAIL_API_TOKEN.'/');

/**
 * Setup remote location for bulk image storage
 * 
 * Cloudinary provides remote image storage for application. 
 * Provides method for safe storage and source validation
 * Define cloudinary cloud name, authentication url, secret key, preset mode
 * Define default error image for invalid sources
 */
define('IMG_CLOUD_NAME', '');
define('IMG_UPLOAD_URL', 'https://api.cloudinary.com/v1_1/'.IMG_CLOUD_NAME.'/image/upload');
define('IMG_API_SECRET', '');
define('IMG_API_KEY', '');
define('IMG_API_PRESET', '');
define('IMG_VALIDATE_URL', 'https://res.cloudinary.com/'.IMG_CLOUD_NAME.'/image/upload');
define("IMG_404_PATH", URLROOT.'/assets/img-not-found.png');