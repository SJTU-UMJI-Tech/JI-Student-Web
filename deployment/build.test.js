/**
 * Created by sunyi on 2017/2/1.
 */
const builder = require('./requirejs-builder');
let test      = builder.initBuilder({});

test.addAppDir('./js/ji','./js-dist/ji');
