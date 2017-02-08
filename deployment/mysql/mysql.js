/**
 * Created by liu on 17-2-8.
 */
const fs    = require('fs');
const mysql = require('mysql');

const connection = mysql.createConnection({
    host              : 'localhost',
    post              : '3306',
    user              : 'student',
    password          : 'password',
    database          : 'student',
    multipleStatements: true
});

connection.connect();

let buffer = fs.readFileSync('deployment/mysql/config.sql', 'utf-8');
//console.log(buffer);
connection.query(buffer, function (err) {
    console.log(err);
});

connection.end();
