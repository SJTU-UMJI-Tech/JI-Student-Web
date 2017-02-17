/**
 * Created by liu on 17-1-30.
 */
"use strict";
define([
    'require', 'exports', 'module',
    'jquery', 'bootstrap', 'handlebars.runtime', 'inspinia',
    'templates/common/header', 'templates/common/navbar'
], function (require, exports, module) {
    
    const $          = require('jquery'),
          Handlebars = require('handlebars.runtime');
    
    /**
     * @param {object}  options
     * @param {object}  options.navbar          - The recursive structure of the left navbar
     * @param {object}  options.info            - The information about user (only set when login)
     * @param {string}  options.info.avatar     - The url of small avatar of the user
     * @param {string}  options.info.user_name  - User name in Chinese
     * @param {string}  options.info.user_type  - User type in English (student/...)
     * @param {object}  options.url             - The relative url of some related pages
     * @param {string}  options.url.profile     - User profile page
     * @param {string}  options.url.login       - Login url
     * @param {string}  options.url.logout      - Logout url
     */
    module.exports = function (options) {
        
        // This helper is useful for generating urls in templates,
        // since the ROOT_DIR is different in various environments
        Handlebars.registerHelper('base_url', function (url) {
            if (!url) url = '';
            else if (url[0] === '/') url = url.substring(1);
            return new Handlebars.SafeString(window.BASE_URL + url);
        });
        
        // Generate the left navbar
        let template = require('templates/common/navbar');
        $("#ji-life-navbar").html(template(options));
        
        // Generate the header navbar
        template = require('templates/common/header');
        $("#ji-life-header").html(template(options));
        
    }
    
});