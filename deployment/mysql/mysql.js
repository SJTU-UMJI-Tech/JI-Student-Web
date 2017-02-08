/**
 * Created by liu on 17-2-8.
 */
const mysql = require('mysql');

const connection = mysql.createConnection({
    host    : 'localhost',
    user    : 'student',
    password: 'password',
    database: 'student'
});

connection.connect();

connection.query()