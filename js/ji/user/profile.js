/**
 * Created by liu on 17-1-29.
 */
;define([
    'require', 'exports', 'module',
    'jquery', 'handlebars.runtime',
    'js/ji/user/avatar' + window.JS_SUFFIX, 'css!' + window.ROOT_DIR + '/js/ji/user/avatar.css',
    'templates/common/ibox', 'templates/user/profile'
], function (require, exports, module) {
    
    'use strict';
    
    var $          = require('jquery');
    var Handlebars = require('handlebars.runtime');
    
    module.exports = function (options) {
        
        
        var template = require('templates/user/profile');
        
        var config = {
            name         : options.name,
            user_type    : options.user_type,
            avatar       : options.avatar,
            avatar_upload: options.avatar_upload
        };
        
        window.console.log(config);
        
        $("#body-wrapper").append(template(config));
        
        $("#crop-avatar").cropAvatar();
    }
    
});