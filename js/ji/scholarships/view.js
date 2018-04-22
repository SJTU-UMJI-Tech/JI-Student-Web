/**
 * Created by liu on 17-2-12.
 */
"use strict";
define([
    'require', 'exports', 'module',
    'jquery', 'marked', 'handlebars.runtime', 'ji/scholarships/common', 'ji/common/file-icon',
    'templates/common/body', 'templates/common/ibox',
    'templates/scholarships/view', 'templates/scholarships/view-sidebar'
], function (require, exports, module) {
    
    const $ = require('jquery'),
        Handlebars = require('handlebars.runtime'),
        marked = require('marked'),
        scholarships = require('ji/scholarships/common'),
        fileicon = require('ji/common/file-icon');
    
    module.exports = (options) => {
        
        scholarships.processData(options.data);
        scholarships.processAttachment(options.data.attachment);
        options.data.content = marked(options.data.content);
        fileicon.processArray(options.data.attachment);
        
        console.log(options.data);
        
        
        let template = require('templates/common/body');
        Handlebars.registerPartial('ibox', require('templates/common/ibox'));
        Handlebars.registerPartial('scholarships-view', require('templates/scholarships/view'));
        Handlebars.registerPartial('scholarships-view-sidebar', require('templates/scholarships/view-sidebar'));
        
        // Main Table
        let config = {
            "id": "main-view",
            "title": "Scholarships Detail",
            "tools": [],
            "body": [{
                "template": "scholarships-view",
                "data": options.data
            }]
        };
        if (options.edit_url) {
            config.tools.push({
                "edit": true,
                "href": options.edit_url
            });
        }
        $("#body-wrapper").append(
            template([{
                grid: 'col-lg-9',
                template: 'ibox',
                data: config
            }, {
                grid: 'col-lg-3',
                template: 'scholarships-view-sidebar',
                data: options.data
            }])
        );
        
        const $mainView = $("#main-view");
        
    }
});