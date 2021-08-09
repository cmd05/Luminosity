# Luminosity

Luminosity Local development branch.

 Packages used:
  - [PHP Mailer](https://github.com/PHPMailer/PHPMailer)
  - [HTML Purifier](https://github.com/ezyang/htmlpurifier)
  - [Quill JS](https://github.com/quilljs/quill)

 ## Requirements
  - PHP Version >= 7.0
  - Composer package management (optional)
  - MySQL drivers
 
 ## Setup Locally 
 
 Follow the given steps to test Luminosity locally.  <br> <br>

  ### (I) Setup Project
  - Clone project in htdocs directory using: <br>
    ```
    git clone --single-branch --branch Local git@github.com:cmd3BOT/Luminosity.git
    ```
    Your directory should look like: ```C:\(xampp or any other stack)\htdocs\Luminosity```
    <br><br>
    **Rename** `application/Config/Config.example.php` **to** ``Config.php``
    <br><br>
  - Check that Project Directory Paths match: <br><br>
    - [``public/.htaccess``](https://github.com/cmd3BOT/Luminosity/blob/Local/public/.htaccess#L4) 
      ```apache
      RewriteBase /Luminosity/public #append /public folder to path 
      ```
    - [``js/script.js``](https://github.com/cmd3BOT/Luminosity/blob/Local/public/js/script.js#L1)
      ```js
      const URL = 'http://' + window.location.host + "/luminosity"; // set app url
      ```
    - [``Config/Config.php``](https://github.com/cmd3BOT/Luminosity/blob/Local/application/Config/Config.php#L28)
      ```php
      define('BASE_FOLDER', basename(dirname(__DIR__, 2))); // root folder containing project
      define('URLROOT', "http://localhost/".BASE_FOLDER); // URL for application
      ```
      
  
  ### (II) App Configurations
   Setup app configs in ``application/Config.php``. <br><br>
 
  - Add SMTP Mail details to app. <br><br>
    To setup using Gmail:
    ```php
    define("SMTP_HOST", 'smtp.gmail.com');
    define("SMTP_USERNAME", 'username@gmail.com');
    define("SMTP_MAIL", 'username@gmail.com');
    define("SMTP_PASSWORD", 'password');
    define("SMTP_PORT", 587); // default mail port
    ```    
    Testing with gmail on localhost requires [access from less secure apps](https://myaccount.google.com/lesssecureapps)
    <br>
    More options: [Sendgrid](http://sendgrid.com/), [Mailjet](https://www.mailjet.com/feature/smtp-relay/)
    
  - Refer to [IP Quality Score](https://www.ipqualityscore.com/documentation/email-validation/overview) for email validation API token.
    ```php
    define('EMAIL_API_TOKEN', '...');
    ```
  - Cloudinary has been used as image host for application. Cloudinary works in default ``demo`` mode as given. 
    <br>
    You can optionally create your own [cloud](https://cloudinary.com/users/register/free). Reference [Upload API](https://cloudinary.com/documentation/image_upload_api_reference)
    ```php
    define('IMG_UPLOAD_URL', 'https://api.cloudinary.com/v1_1/demo/image/upload');
    define('IMG_CLOUD_NAME', 'demo');
    ``` 
    <br>
    
    **Note:** 
    Cloudinary automatically restricts images greater than 10MB for cloud accounts. <br>
    Allowed Extensions can be set in the console at ``settings/uploads/<UPLOAD_PRESET>/Upload Control``
    <br> <br>
    Using demo mode allows unrestricted file upload for users. To prevent this, edit [``Models/WriteModel #L32``](https://github.com/cmd3BOT/Luminosity/blob/Local/application/Models/WriteModel.php#L32):
    ```php
    if($array['bytes'] <= 10000000 && in_array($array['format'], ['jpg','jpeg','gif','webp','png'])
       && isset($array['secure_url'])) {
         return $array['secure_url'];
    }
    ```
    <br>
    
  - Configurations should be setup carefully to prevent unexpected errors

  ### (III) Setup Database
  Use MySQL database for the application. 
  
  - Add database credentials to ``Config.php``
    ```php
    define("DB_HOST", "localhost");
    define("DB_USER", "root");
    define("DB_PASS", "");
    define("DB_NAME", "luminosity");
    ```
  - Create a database ``luminosity`` and load [``application/SQL/dump.sql``](https://github.com/cmd3BOT/Luminosity/blob/Local/application/SQL/dump.sql)
  
## Contributing
  You may open an issue [here](https://github.com/cmd3BOT/Luminosity/issues)
  
