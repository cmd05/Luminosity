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
 * Create a database in MySQL called luminosity and load dump.sql
 */
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "luminosity");
define('DB_TIMESTAMP_FMT', 'Y-m-d H:i:s');

/**
 * Site Settings
 */
define("SERVER_TIMEZONE", 'Asia/Kolkata');
define('APPROOT', dirname(dirname(__FILE__)));
define('BASE_FOLDER', basename(dirname(__DIR__, 2)));
define('URLROOT', "http://localhost/".BASE_FOLDER);
define('SITENAME', 'Luminosity');
define('APP_VERSION', '1.0.0');
define('SESSION_NAME', SITENAME);

/**
 * Website Image Directories
 */
define("LOGO_PATH", URLROOT."/assets/logo.png");
define('UPLOAD_PATH', dirname(dirname(dirname(__FILE__))).'/public/uploads/');
define('PROFILE_IMG_DIR', URLROOT.'/uploads');
define("DEFAULT_PROFILE_NAME", 'default-profile.png');
define("DEFAULT_PROFILE_PATH", PROFILE_IMG_DIR."/".DEFAULT_PROFILE_NAME);

/**
 * Settings to setup mail server
 * 
 * Avoid incorrect credentials or settings to decrease potential errors
 * Some mailing hosts may require access from lesser known sources.
 * Default port is 587
 */
define("SMTP_HOST", 'smtp.gmail.com');
define("SMTP_USERNAME", '...');
define("SMTP_MAIL", '...');
define("SMTP_PASSWORD", '...');
define("SMTP_PORT", 587);

/**
 * Email validation API
 */
define('EMAIL_API_TOKEN', '...');
define('EMAIL_API', 'https://www.ipqualityscore.com/api/json/email/'.EMAIL_API_TOKEN.'/');

/**
 * Setup remote location for bulk image storage
 */
define('IMG_CLOUD_NAME', 'demo');
define('IMG_UPLOAD_URL', 'https://api.cloudinary.com/v1_1/'.IMG_CLOUD_NAME.'/image/upload');
define('IMG_API_SECRET', '');
define('IMG_API_KEY', '');
define('IMG_API_PRESET', 'docs_upload_example_us_preset');
define('IMG_VALIDATE_URL', 'https://res.cloudinary.com/'.IMG_CLOUD_NAME.'/image/upload');
define("IMG_404_PATH", URLROOT.'/assets/img-not-found.png');