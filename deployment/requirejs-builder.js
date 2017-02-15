/**
 * Created by liu on 17-2-1.
 */
"use strict";
const fs       = require('fs');
const shelljs  = require('shelljs');
const crypto   = require('crypto');
const UglifyJS = require('uglify-js');
const CleanCSS = require('clean-css');
const babel    = require('babel-core');

const HEADER                                            =
          '/*\n' +
          ' This file is generated by requirejs-builder, used for config of RequireJS. \n' +
          ' Please don\'t modify this file.\n' +
          ' */\n\n';

class RequireJSBuilder {
    constructor() {
        this.init();
        this.nameArr   = {};
        this.appConfig = {};
        this.libConfig = {};
    }
    
    init(options                                        = {}) {
        this.options      = {
            require_css_dir : options.require_css_dir,
            hash_method     : options.hash_method || 'sha1',
            node_modules    : options.node_modules || 'node_modules',
            bower_modules   : options.bower_modules || 'bower_modules',
            lib_output      : options.lib_output || 'js-dist',
            js_output_dir   : options.js_output_dir || 'dist/js',
            css_output_dir  : options.css_output_dir || 'dist/css',
            fonts_output_dir: options.fonts_output_dir || 'dist/fonts'
        };
        this.remove_files = {};
        this.initDir(this.options.fonts_output_dir);
        this.removeFiles();
        this.initDir(this.options.js_output_dir);
        this.initDir(this.options.css_output_dir);
        if (this.options.require_css_dir) {
            this.mkdirMulti(`${this.options.js_output_dir}/lib`);
            this.options.require_css_dir =
                this.processJS(options.require_css_dir, options.require_css_dir, 'lib');
        }
    }
    
    initDir(dir, root                                   = true) {
        if (root) this.mkdirMulti(dir);
        const files = fs.readdirSync(dir);
        for (let i = 0; i < files.length; i++) {
            let filePath = dir + '/' + files[i];
            const stats  = fs.statSync(filePath);
            if (stats.isDirectory()) {
                this.initDir(filePath);
            } else {
                this.remove_files[filePath] = true;
            }
        }
    }
    
    removeFiles() {
        for (let item in this.remove_files) {
            try {
                fs.unlinkSync(item);
                this.log('Remove', item);
            } catch (err) {
            }
        }
    }
    
    addName(name) {
        if (!this.nameArr.hasOwnProperty(name)) {
            this.nameArr[name] = true;
            return true;
        }
        return false;
    }
    
    log(order, info) {
        for (let i = order.length; i < 8; i++) {
            order += ' ';
        }
        console.log(`${order} | ${info}`);
    }
    
    mkdirMulti(dir) {
        try {
            fs.accessSync(dir);
        } catch (err) {
            let index = dir.lastIndexOf('/');
            if (index > 0) {
                this.mkdirMulti(dir.substring(0, index));
            }
            if (index !== dir.length - 1) {
                fs.mkdirSync(dir);
            }
        }
    }
    
    addFonts(inputDir, relativeDir                      = '/') {
        const files = fs.readdirSync(inputDir + relativeDir);
        for (let i = 0; i < files.length; i++) {
            const inputPath  = inputDir + relativeDir + files[i],
                  outputPath = `${this.options.fonts_output_dir}${relativeDir}${files[i]}`,
                  stats      = fs.statSync(inputPath);
            if (stats.isDirectory()) {
                this.addFonts(inputDir, relativeDir + files[i] + '/');
                this.mkdirMulti(outputPath);
            }
            else {
                shelljs.cp(inputPath, outputPath);
                this.log('Copy', `${inputPath} -> ${outputPath}`);
            }
        }
    }
    
    addFile(name, filePath, deps, css, prefix           = 'lib') {
        if (this.addName(name)) {
            this.mkdirMulti(`${this.options.js_output_dir}/${prefix}`);
            let data    = this.processJS(filePath, name, prefix);
            data.css    = [];
            data.mincss = [];
            for (let i = 0; i < css.length; i++) {
                let _data = this.processCSS(css[i]);
                data.css.push(_data.css);
                data.mincss.push(_data.mincss);
            }
            this.libConfig[name] = {
                js    : data.js,
                minjs : data.minjs,
                deps  : typeof(deps) === 'string' ? (deps.length > 0 ? [deps] : []) : deps,
                css   : data.css,
                mincss: data.mincss
            }
        }
    }
    
    addFileTraversal(inputDir, prefix, es6, relativeDir = '/') {
        this.mkdirMulti(`${this.options.js_output_dir}/${prefix}`);
        const files = fs.readdirSync(inputDir + relativeDir);
        for (let i = 0; i < files.length; i++) {
            let file_dir = inputDir + relativeDir + files[i];
            const stats  = fs.statSync(file_dir);
            if (stats.isDirectory()) {
                this.addFileTraversal(inputDir, prefix, es6, relativeDir + files[i] + '/');
            }
            else {
                let check_min_js = files[i].match(/\.min[\W\w]*\.js/);
                let check_js     = files[i].match(/\.js$/);
                if (!check_min_js && check_js) {
                    const fileName  = files[i].replace(/\.js$/, ''),
                          fileAlias = prefix + relativeDir.replace(/\//g, '/') + fileName;
                    if (this.addName(fileAlias)) {
                        
                        const data = this.processJS(inputDir + relativeDir + fileName, fileAlias, prefix, es6);
                        
                        this.appConfig[fileAlias] = {
                            js   : data.js,
                            minjs: data.minjs
                        }
                    }
                }
            }
        }
    }
    
    processJS(filePath, fileAlias                       = filePath, prefix = '', es6 = false) {
        const inputPath     = `${filePath}.js`;
        let buffer          = fs.readFileSync(inputPath, 'utf-8');
        const hashcode      = crypto.createHash(this.options.hash_method).update(buffer + fileAlias).digest('hex'),
              signature     = hashcode.substring(0, 16),
              //outputPath    = `${this.options.js_output_dir}/${signature}.js`,
              outputPathMin = `${this.options.js_output_dir}/${prefix}/${signature}.min.js`;
        this.log('Process', `${inputPath} (${signature}) ...`);
        try {
            //fs.accessSync(outputPath);
            fs.accessSync(outputPathMin);
        } catch (err) {
            //this.log('Generate', outputPath + (es6 ? ' (babel)' : ''));
            if (es6) {
                buffer = babel.transform(buffer, {"presets": ["es2015"]}).code;
            }
            //fs.writeFileSync(outputPath, buffer);
            this.log('Generate', outputPathMin + (es6 ? ' (babel)' : ''));
            let stream = UglifyJS.OutputStream({comments: true});
            UglifyJS.parse(buffer).print(stream);
            fs.writeFileSync(outputPathMin, stream.toString());
        }
        /*if (this.remove_files.hasOwnProperty(outputPath)) {
         delete this.remove_files[outputPath];
         }*/
        if (this.remove_files.hasOwnProperty(outputPathMin)) {
            delete this.remove_files[outputPathMin];
        }
        this.log('Status', 'Up to date');
        return {
            js   : filePath,//`${signature}`,
            minjs: `${prefix}/${signature}.min`
        };
    }
    
    processCSS(filePath) {
        const inputPath     = `${filePath}.css`,
              buffer        = fs.readFileSync(inputPath, 'utf-8'),
              hashcode      = crypto.createHash(this.options.hash_method).update(buffer + filePath).digest('hex'),
              signature     = hashcode.substring(0, 16),
              //outputPath    = `${this.options.css_output_dir}/${signature}.css`,
              outputPathMin = `${this.options.css_output_dir}/${signature}.min.css`;
        this.log('Process', `${inputPath} (${signature}) ...`);
        try {
            //fs.accessSync(outputPath);
            fs.accessSync(outputPathMin);
        } catch (err) {
            //this.log('Generate', outputPath);
            //fs.writeFileSync(outputPath, buffer);
            let data = new CleanCSS({}).minify(buffer);
            fs.writeFileSync(outputPathMin, data.styles);
        }
        /*if (this.remove_files.hasOwnProperty(outputPath)) {
         delete this.remove_files[outputPath];
         }*/
        if (this.remove_files.hasOwnProperty(outputPathMin)) {
            delete this.remove_files[outputPathMin];
        }
        this.log('Status', 'Up to date');
        return {
            css   : inputPath,//`${signature}.css`,
            mincss: `${signature}.min.css`
        };
    }
    
    build(options                                       = {}) {
        
        options = {
            root_dir   : options.root_dir || '',
            filePath   : options.filePath || 'js/app',
            environment: options.environment !== 'production'
        };
        
        this.removeFiles();
        
        const printArr = (arr) => {
            let str = '[';
            if (arr.length > 0) {
                for (let i = 0; i < arr.length - 1; i++) {
                    str += `'${arr[i]}', `;
                }
                str += `'${arr[arr.length - 1]}'`;
            }
            return str + ']';
        };
        
        const appFilePath    = `${options.filePath}.js`;
        const appFilePathMin = `${options.filePath}.min.js`;
        
        let fd = fs.openSync(appFilePath, 'w');
        fs.writeSync(fd, HEADER);
        
        fs.writeSync(fd, 'requirejs.config({\n\n');
        let jsDir = options.environment ? '' : `${this.options.js_output_dir}/`;
        fs.writeSync(fd, `    baseUrl: '${options.root_dir}/${jsDir}',\n\n`);
        
        // Map
        fs.writeSync(fd, '    map: {\n        \'*\': {\n');
        if (this.options.require_css_dir) {
            let file = options.environment ?
                this.options.require_css_dir.js : this.options.require_css_dir.minjs;
            fs.writeSync(fd, `            css: '${file}',`);
        }
        fs.writeSync(fd, '\n        }\n    },\n\n');
        
        // Paths
        fs.writeSync(fd, '    paths: {\n\n');
        fs.writeSync(fd, '        // lib config\n');
        for (let item in this.libConfig) {
            let file = options.environment ?
                this.libConfig[item].js : this.libConfig[item].minjs;
            fs.writeSync(fd, `        '${item}': '${file}',\n`)
        }
        fs.writeSync(fd, '\n');
        fs.writeSync(fd, '        // app config\n');
        for (let item in this.appConfig) {
            let file = options.environment ?
                this.appConfig[item].js : this.appConfig[item].minjs;
            fs.writeSync(fd, `        '${item}': '${file}',\n`)
        }
        fs.writeSync(fd, '\n    },\n\n');
        
        // Shim
        fs.writeSync(fd, '    shim: {\n\n');
        for (let item in this.libConfig) {
            const config = this.libConfig[item];
            let deps     = [];
            for (let i = 0; i < config.deps.length; i++) {
                deps.push(config.deps[i]);
            }
            for (let i = 0; i < config.mincss.length; i++) {
                let file = options.environment ? config.css[i] :
                    `${options.root_dir}/${this.options.css_output_dir}/${config.mincss[i]}`;
                deps.push(`css!${file}`);
            }
            if (deps.length > 0) {
                fs.writeSync(fd, `        '${item}': ${printArr(deps)},\n`);
            }
        }
        
        fs.writeSync(fd, '\n    }\n\n');
        
        
        fs.writeSync(fd, '});\n\n');
        
        fs.closeSync(fd);
        
        fs.writeFileSync(appFilePathMin, UglifyJS.minify(appFilePath).code);
        
        //console.log(filePath);
    }
}

let builder = new RequireJSBuilder();

module.exports = {
    init: (options) => {
        builder.init(options);
    },
    
    addAppDir: (inputDir, prefix, es6 = true) => {
        builder.addFileTraversal(inputDir, prefix, es6);
    },
    
    addFile: (name, fileDir, deps = '', css = '') => {
        let _css = typeof(css) === 'string' ? (css.length > 0 ? [css] : []) : css;
        builder.addFile(name, fileDir, deps, _css);
    },
    
    addNode: (name, fileDir, deps = '', css = '') => {
        let _css = typeof(css) === 'string' ? (css.length > 0 ? [css] : []) : css;
        for (let i = 0; i < _css.length; i++) {
            _css[i] = builder.options.node_modules + '/' + _css[i];
        }
        builder.addFile(name, builder.options.node_modules + '/' + fileDir, deps, _css);
    },
    
    addBower: (name, fileDir, deps = '', css = '') => {
        let _css = typeof(css) === 'string' ? (css.length > 0 ? [css] : []) : css;
        for (let i = 0; i < _css.length; i++) {
            _css[i] = builder.options.bower_modules + '/' + _css[i];
        }
        builder.addFile(name, builder.options.bower_modules + '/' + fileDir, deps, _css);
    },
    
    addFonts: (dir) => {
        builder.addFonts(dir);
    },
    
    build: (filePath) => {
        builder.build(filePath);
    }
};

