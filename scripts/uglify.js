/**
 * Created by liu on 17-1-28.
 * Used to uglify the js files
 */

const files = require('./uglify.json');
require('shelljs/global');

echo('Begin to uglify the js files ...');

var root_dir = './js/ji';

for (var type in files) {
    if (files.hasOwnProperty(type)) {
        var dir = root_dir + '/' + type;
        for (var i = 0; i < files[type].length; i++) {
            var filename = files[type][i];
            var input = dir + '/' + filename + '.js';
            var output = dir + '/' + filename + '.min.js';
            echo('Generating ' + input + ' -->\n           ' + output);
            exec('uglifyjs ' + input + ' -o ' + output);
        }
    }
}

echo('Uglified js generated in ' + root_dir);