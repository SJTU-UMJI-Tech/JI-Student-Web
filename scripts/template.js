/**
 * Created by liu on 17-1-28.
 * Used to precompile Handlebars templates
 */

var files = require('./template.json');
require('shelljs/global');

echo('Begin to generate the templates ...');

var root_dir = './js/templates';

exec('if [ ! -d "' + root_dir + '" ]; then echo "mkdir ' + root_dir + '"; mkdir ' + root_dir + '; fi');

if (process.argv.length > 2) {
    var type = process.argv[2];
    if (files.hasOwnProperty(type)) {
        var temp = files[type];
        files = {};
        files[type] = temp;
    } else {
        files = {};
    }
}


for (type in files) {
    if (files.hasOwnProperty(type)) {
        var dir = root_dir + '/' + type;
        exec('if [ ! -d "' + dir + '" ]; then echo "mkdir ' + dir + '"; mkdir ' + dir + '; fi');
        for (var i = 0; i < files[type].length; i++) {
            var filename = files[type][i];
            var input = './templates/' + type + '/' + filename + '.hbs';
            var output = dir + '/' + filename + '.js';
            var output_min = dir + '/' + filename + '.min.js';
            echo('Generating ' + input + ' -->\n           ' + output);
            exec('handlebars ' + input + ' -a -f ' + output);
            exec('handlebars ' + input + ' -a -m -f ' + output_min);
        }
    }
}

echo('Templates generated in ' + root_dir);