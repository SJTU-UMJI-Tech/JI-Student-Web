/**
 * Created by liu on 17-1-31.
 */
var CleanCSS = require('clean-css');
var fs       = require('fs');

function minify(file) {
    
    var input  = file + '.css';
    var output = file + '.min.css';
    
    fs.readFile(input, function (err, data) {
        if (err) {
            console.log('Error: ' + input + ' Not Found.');
            return;
        }
        var clean = new CleanCSS({}).minify(data);
        console.log('Generating ' + input + ' -->\n           ' + output);
        fs.writeFileSync(output, clean.styles);
    });
}

minify('./css/style');
minify('./css/animate');