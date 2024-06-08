# Luminosity

The Complete Modern Blogging Platform

## Features

Features:
  - Read Articles of various topics
  - Use a Web Friendly interface to create posts
  - Comment and React on Posts
  - Bookmark and save articles 
  - Customize Profile
  - Site-wide Dark Mode option
  
*[Note: Some emails may go to your spam folder please check there too]*
  
**User Interface**

![UI](https://media.discordapp.net/attachments/603212735320162304/836531828927365180/unknown.png?width=988&height=480)


## Design 

The project implements *MVC [Model-View-Controller]* design pattern. The project has been built upon a [Custom Framework](https://github.com/cmd3BOT/PHP-MVC-Framework). 

![MVC Structure](https://cdn.educba.com/academy/wp-content/uploads/2019/04/what-is-mvc-design-pattern.jpg)

> Model–view–controller (usually known as MVC) is a software design pattern commonly used for developing user interfaces that divides the related program logic into three interconnected elements. This is done to separate internal representations of information from the ways information is presented to and accepted from the user.

**Model**

The Model component corresponds to all the data-related logic that the user works with. This can represent either the data that is being transferred between the View and Controller components or any other business logic-related data. For example, a Customer object will retrieve the customer information from the database, manipulate it and update it data back to the database or use it to render data.

**View**

The View component is used for all the UI logic of the application. For example, the Customer view will include all the UI components such as text boxes, dropdowns, etc. that the final user interacts with.

**Controller**

Controllers act as an interface between Model and View components to process all the business logic and incoming requests, manipulate data using the Model component and interact with the Views to render the final output. For example, the Customer controller will handle all the interactions and inputs from the Customer View and update the database using the Customer Model. The same controller will be used to view the Customer data.

A common example is [ASP.NET MVC](https://dotnet.microsoft.com/apps/aspnet/mvc)

## Project Structure
  
``app/``
  Contains Backend and Application Logic
  - Config - Contains Config Files for project
  - Libraries - Contains Basic Libraries extended by classes and units.
    - Look at /Core.php for routing based on request URI
  - Controllers
    - Ajax Controllers => Internal API request logic
    - Controller Traits => Config values for controller logic
    - View Controllers => Setup View with data based on request Method
  - Helpers - Helper Files for common methods used accross application
  - SQL - Contains SQL Dump and Procedures for Models
  - Views - Each Controller has a seperate view folder containing files for a specific view [default index]
  - Vendor - Packages used in PHP
  - Bootstrap.php - Load Configs, Start Session and intanstiate autoloader 

``/public``
  - Application frontend
  - Index.php is entry file
 
 ``root/.htaccess``
  - Route requests through public folder
  
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
    ```git clone git@github.com:cmd3BOT/Luminosity.git``` <br>
    Your directory should look like: ```C:\(xampp or any other stack)\htdocs\Luminosity```
  - Check ``public/.htaccess`` so that project directories match
  - Create Database in MySQL and load ``SQL/dump.sql``
  - Setup app configs in ``Config/ConfigDefault.php``. Next rename to ``Config.php`` 
    - Refer to [IP Quality Score](https://www.ipqualityscore.com) and [Cloudinary](https://cloudinary.com/) to setup APIs
    - Be sure to fill all configs carefully to prevent unexpected errors.
 
## Contributing
  You may open an issue [here](https://github.com/cmd3BOT/Luminosity/issues)
