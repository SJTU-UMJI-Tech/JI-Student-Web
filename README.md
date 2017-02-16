# JI-LIFE

## Requirements
PHP >= v5.4  
Composer >= v1.3.2  
Nodejs >= v7.4.0  
npm >= v4.0.5

The server is built under CodeIgniter 3 and the front-end is built by requirejs.

We strongly recommend you to develop or deploy this website with jetbrains PhpStorm IDE, which applies free education license to students. Our tutorials are mostly based on it.

## Installation

Make sure you meet all of the requirements above and run the following script:
```
php composer.phar install
npm install
npm run compile:template
npm run compile:build
```
The script does these things:
+ Install PHP dependencies through composer
+ Install Node and Bower dependencies through node and bower
+ Compile the Handlebars templates 
+ Build the whole front-end config file app.*.js

To start development, run  
```
npm run deploy:development
```
To start production, run  
```
npm run deploy:production
```  

This script copy different files for different usages and then you can upload the whole project to the FTP server. You may change the content of files in deployment/development|production for certain purposes.

Remember to set the mode of folders `js`, `css`, `node_modules`, `bower_modules` to `755`,  and that of folder `uploads` to `777`, or some functions won't work on Linux Server.

## Contribution

All students in JI are welcomed to contribute in this project. For development reference, see the WIKI.