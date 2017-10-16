# JI-LIFE
[![Build Status](https://travis-ci.org/SJTU-UMJI-Tech/JI-Student-Web.svg?branch=master)](https://travis-ci.org/SJTU-UMJI-Tech/JI-Student-Web)

A website for ALL students in JI

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
sudo npm run deploy:development
```
To start production, run  
```
sudo npm run deploy:production
```  

This script copy different files for different usages and then you can upload the whole project to the FTP server. You may change the content of files in deployment/development|production for certain purposes.

Remember to set the mode of folders `js`, `css`, `node_modules`, `bower_modules` to `755`,  and that of folder `uploads` to `777`, or some functions won't work on Linux Server.

Edit 000-default.conf in etc/apache2/sites-enabled and change the DocumentRoot to your working directory, for example, "var/www/ipp". Then, add
```
<Directory /var/www/ipp>
    Options Indexes FollowSymLinks
    AllowOverride All
    Order allow,deny
    Allow from all
</Directory>
```
to the end of file.

Apache2 on Ubuntu doesn't have rewrite engine open, use the command to enable it.
```
sudo a2enmod rewrite
sudo service apache2 restart
```

## Contribution

All students in JI are welcomed to contribute in this project. For development reference, see the WIKI.

## Reference
+ [babel](https://github.com/babel/babel) Babel is a compiler for writing next generation JavaScript.
+ [bootstrap](https://github.com/twbs/bootstrap) The most popular HTML, CSS, and JavaScript framework for developing responsive, mobile first projects on the web.
+ [bootstrap-touchspin](https://github.com/istvan-ujjmeszaros/bootstrap-touchspin) A mobile and touch friendly input spinner component for Bootstrap 3.
+ [bower](https://github.com/bower/bower) A package manager for the web
+ [Chart.js](https://github.com/chartjs/Chart.js) Simple HTML5 Charts using the <canvas> tag
+ [chosen](https://github.com/harvesthq/chosen) Chosen is a library for making long, unwieldy select boxes more friendly.
+ [clean-css](https://github.com/jakubpawlowicz/clean-css) Fast and efficient CSS optimizer for node.js and the Web
+ [code-prettify](https://github.com/google/code-prettify) An embeddable script that makes source-code snippets in HTML prettier.
+ [CodeIgniter](https://github.com/bcit-ci/CodeIgniter) Open Source PHP Framework (originally from EllisLab)
+ [console](https://github.com/symfony/console) The Console component eases the creation of beautiful and testable command line interfaces.
+ [cropper](https://github.com/fengyuanchen/cropper) A simple jQuery image cropping plugin.
+ [editor.md](https://github.com/pandao/editor.md) The open source embeddable online markdown editor (component).
+ [flatpickr](https://github.com/chmln/flatpickr) lightweight and powerful datetimepicker with no dependencies
+ [flowchart.js](https://github.com/adrai/flowchart.js) Draws simple SVG flow chart diagrams from textual representation of the diagram
+ [FontAwesome](https://github.com/FortAwesome/Font-Awesome) The iconic font and CSS toolkit
+ [Footable](https://github.com/fooplugins/FooTable) jQuery plugin to make HTML tables responsive
+ [Gritter](https://github.com/jboesch/Gritter) A small growl-like notification plugin for jQuery 
+ [Handlebars](https://github.com/wycats/handlebars.js) Handlebars.js is an extension to the Mustache templating language created by Chris Wanstrath.
+ [jQuery](https://github.com/jquery/jquery) jQuery JavaScript Library
+ [jQuery-slimScroll](https://github.com/rochal/jQuery-slimScroll) small jQuery plugin that transforms any div into a scrollable area with a nice scrollbar.
+ [jquery-ui](https://github.com/jquery/jquery-ui) The official jQuery user interface library.
+ [jquery.flowchart](https://github.com/sdrdis/jquery.flowchart) JQuery plugin that allows you to draw a flow chart.
+ [js-sequence-diagrams](https://github.com/bramp/js-sequence-diagrams) Draws simple SVG sequence diagrams from textual representation of the diagram
+ [katex](https://github.com/Khan/KaTeX) Fast math typesetting for the web. 
+ [marked](https://github.com/chjj/marked) A markdown parser and compiler. Built for speed.
+ [metismenu](https://github.com/onokumus/metismenu) A jQuery menu plugin
+ [oauth2-client](https://github.com/thephpleague/oauth2-client) Easy integration with OAuth 2.0 service providers.
+ [pace](https://github.com/HubSpot/pace) Automatically add a progress bar to your site.
+ [PHPExcel](https://github.com/PHPOffice/PHPExcel) A pure PHP library for reading and writing spreadsheet files
+ [qrcodejs](https://github.com/davidshimjs/qrcodejs) Cross-browser QRCode generator for javascript
+ [raphael](https://github.com/DmitryBaranovskiy/raphael) JavaScript Vector Library
+ [require-css](https://github.com/guybedford/require-css) A RequireJS CSS loader plugin to allow CSS requires and optimization
+ [requirejs](https://github.com/requirejs/requirejs) A file and module loader for JavaScript
+ [select2](https://github.com/select2/select2) Select2 is a jQuery based replacement for select boxes. It supports searching, remote data sets, and infinite scrolling of results.
+ [shelljs](https://github.com/shelljs/shelljs) Portable Unix shell commands for Node.js
+ [toastr](https://github.com/CodeSeven/toastr) Simple javascript toast notifications
+ [UglifyJs](https://github.com/mishoo/UglifyJS) JavaScript parser / mangler / compressor / beautifier library for NodeJS
+ [underscore](https://github.com/jashkenas/underscore) JavaScript's utility _ belt
+ [xterm.js](https://github.com/sourcelair/xterm.js) Full xterm terminal, in your browser
+ [zend-permissions-acl](https://github.com/zendframework/zend-permissions-acl) Provides a lightweight and flexible access control list (ACL) implementation for privileges management.
