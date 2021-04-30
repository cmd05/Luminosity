# Luminosity

Luminosity Local development branch.

 Packages used:
  - [PHP Mailer](https://github.com/PHPMailer/PHPMailer)
  - [HTML Purifier](https://github.com/ezyang/htmlpurifier)
  - [Quill JS](https://github.com/quilljs/quill)

 ## Requirements
  - PHP Version >= 7.0
  - Composer package management
  - MySQL drivers
 
 ## Setup Locally 
 
 Follow the given steps to test Luminosity locally:
  - Clone project in htdocs directory using: <br>
    ```git clone --single-branch --branch Local git@github.com:cmd3BOT/Luminosity.git``` <br>
    Your directory should look like: ```C:\(xampp or any other stack)\htdocs\Luminosity```
  - Check that Project Directory Paths:
    - [``public/.htaccess``](https://github.com/cmd3BOT/Luminosity/blob/Local/public/.htaccess#L4) 
    - [``js/script.js``](https://github.com/cmd3BOT/Luminosity/blob/Local/public/js/script.js#L1)
    - [``Config/Config.php``](https://github.com/cmd3BOT/Luminosity/blob/Local/application/Config/Config.php#L28)
  - Create Database ``luminosity`` in MySQL and load ``SQL/dump.sql``
  - Setup app configs in ``Config.php``.
    - You must setup your SMTP mail details. <br>
      To setup using Gmail:
      ```php
      define("SMTP_HOST", 'smtp.gmail.com');
      define("SMTP_USERNAME", 'youremail@gmail.com');
      define("SMTP_MAIL", 'youremail@gmail.com');
      define("SMTP_PASSWORD", 'gmail_psw');
      define("SMTP_PORT", 587);
      ```
    - Refer to [IP Quality Score](https://www.ipqualityscore.com/documentation/email-validation/overview) for email validation API token
    - Cloudinary configs work in default mode. You can optionally create your own [cloud](https://cloudinary.com/users/register/free)
    - Be sure to fill all configs carefully to prevent unexpected errors.

## Contributing
  You may open an issue [here](https://github.com/cmd3BOT/Luminosity/issues)
