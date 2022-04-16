<p align="center">
  <img src="https://user-images.githubusercontent.com/63466463/163665819-61a014b6-9520-4143-bef9-5be50caa0a95.png" alt="UI" width='300'>
</p>

# Luminosity

Remote repository for [Luminosity](http://luminosity-dev.herokuapp.com/) - The Modern Blogging Platform. <br>
  
## Features
  - Find articles on various topics
  - Follow other users
  - Use a web-friendly interface to create posts
  - Create drafts before publishing articles
  - Comment and react on articles
  - Bookmark articles 
  - Customize your profile
  - Responsive Design and Dark Mode option
  
<br>
<p align = "center">
  <img src = "https://user-images.githubusercontent.com/63466463/163675168-bebbe574-b4f2-42dd-ad4c-12dfdfbd5824.png"/>
</p>
<p align = "center"><b>Create Drafts and Articles</b></p>
<br>
<p align = "center">
  <img src = "https://user-images.githubusercontent.com/63466463/163675165-c3cf9593-5ae5-41a0-9213-249b94417f2a.png">
</p>
<p align = "center"><b>Explore Articles</b></p>

<br>
<p align = "center">
  <img src = "https://user-images.githubusercontent.com/63466463/163675167-2fccc976-0969-4e0d-831a-4db700789b8d.png">
</p>
<p align = "center"><b>Customize your profile</b></p>

## Design

The project implements ``Model-View-Controller`` design pattern. This project has been built using a [custom framework](https://github.com/cmd3BOT/PHP-MVC-Framework)

``application/`` App Logic and Backend
  * ``Config`` - Configuration items and files
  * ``Libraries`` - Base classes and main libraries used by derived classes
  * ``Controllers``
    * ``Ajax Controllers`` - Handle internal API requests. ``(Returns: JSON)``
    * ``Controller Traits`` - Configuration values for controllers
    * ``View Controllers`` - Load appropriate view and display data
  * ``Helpers`` - Includes common utility functions used across the application
  * ``SQL`` - Contains ``SQL Dump`` and ``Procedures`` for Models
  * ``Views`` - Each view controller has unique view folder with different pages ``(default index.php)``
  * ``Vendor`` - External Packages used by PHP
  * ``Bootstrap.php`` - The bootstrap file builds the application by including the setup files and starting the session. It also initializes the class and vendor autoloaders.

``public/`` Application frontend
  * ``index.php`` - Main file
 
``.htaccess`` Route Requests through Public folder
  
 Packages used
  - [PHP Mailer](https://github.com/PHPMailer/PHPMailer)
  - [HTML Purifier](https://github.com/ezyang/htmlpurifier)
  - [Quill JS](https://github.com/quilljs/quill)
 
 ## Setup Luminosity 
 
 Follow the [Installation Guide](https://github.com/cmd3BOT/Luminosity/blob/main/INSTALLATION.md) to set up Luminosity.

## Contributing
  You may open an issue [here](https://github.com/cmd3BOT/Luminosity/issues)
