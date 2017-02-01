/**
 * Created by liu on 17-2-1.
 */
"use strict";
const fs      = require('fs');
const shelljs = require('shelljs');
const crypto  = require('crypto');
const hash    = crypto.createHash('md5');

class RequireJSBuilder {
    constructor(options) {
    
    }
    
    addAppDir(inputDir, outputDir) {
        let traversal = (relativeDir) => {
            const files = fs.readdirSync(inputDir + relativeDir);
            for (let i = 0; i < files.length; i++) {
                let file_dir = relativeDir + '/' + files[i];
                const stats  = fs.statSync(inputDir + file_dir);
                if (stats.isDirectory()) {
                    traversal(file_dir);
                }
                else {
                    let check_min_js = files[i].match(/\.min[\W\w]*\.js/);
                    let check_js     = files[i].match(/\.js$/);
                    if (!check_min_js && check_js) {
                        file_dir = file_dir.replace(/\.js$/, '');
                        console.log(outputDir + file_dir);
                    }
                }
            }
        };
        traversal('');
    }
    
}


module.exports = {
    initBuilder: (options) => {
        return new RequireJSBuilder(options);
    }
};

