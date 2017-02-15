/**
 * Created by liu on 17-1-28.
 * Used to precompile Handlebars templates
 */

const mkdir = (dir) => {
    try {
        fs.accessSync(dir);
    } catch (err) {
        fs.mkdirSync(dir);
    }
};

let files = require('./template.json');
require('shelljs/global');
const fs = require('fs');

echo('Begin to generate the templates ...');

const root_dir = './js/templates';
mkdir(root_dir);

if (process.argv.length > 2) {
    const type = process.argv[2];
    if (files.hasOwnProperty(type)) {
        const temp = files[type];
        files = {};
        files[type] = temp;
    } else {
        files = {};
    }
}


for (let type in files) {
    if (files.hasOwnProperty(type)) {
        const dir = root_dir + '/' + type;
        mkdir(dir);
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

if (process.argv.length > 3) {
    exec('node deployment/build.js');
}