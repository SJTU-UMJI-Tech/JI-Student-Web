/**
 * Created by liu on 2017/2/26.
 */
define([
    'require', 'exports', 'module',
    'popper'
], function (require, exports, module) {
    window.Popper  = require('popper');
    module.exports = window.Popper;
});