/**
 * Created by sunyi on 2017/2/1.
 */
const builder = require('./requirejs-builder');
var test=builder.initBuilder({});

test.addAppDir(process.cwd ()+'/js/ji');
