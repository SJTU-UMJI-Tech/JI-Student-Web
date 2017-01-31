# JI-LIFE

Production Enviornment  
PHP >= v5.4  
CodeIgniter >= v3.1.3

Development Enviornment  
Nodejs >= v7.4.0  
npm >= 4.0.5

To start development, run  
``npm install``  
``npm run deploy:development``

To start production, run  
``npm install``  
``npm run deploy:production``  
and upload the whole project to the FTP server  
Remember to set the mode of folders `js`, `css`, `node_modules`, `bower_modules` to `755`, and `uploads` to `777`, or some functions won't work