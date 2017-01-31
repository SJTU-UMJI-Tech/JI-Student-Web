/**
 * Created by liu on 17-1-28.
 * Used to uglify the js files
 */

require('shelljs/global');

echo('Begin to uglify the js files ...');

var root_dir = './js/ji';

var files = ls('-R', root_dir).grep(/\.js$/).grep('-v', /min\.js$/).stdout.split('\n');
for (var i = 0; i < files.length; i++) {
    if (files[i].length > 0) {
        var input  = root_dir + '/' + files[i];
        var output = input.substring(0, input.length - 3) + '.min.js';
        echo('Generating ' + input + ' -->\n           ' + output);
        exec('uglifyjs ' + input + ' -o ' + output);
    }
}

echo('Uglified js generated in ' + root_dir);