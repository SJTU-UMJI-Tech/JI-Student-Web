/**
 * Created by liu on 17-2-12.
 */
"use strict";
define([
    'require', 'exports', 'module',
    'jquery', 'marked', 'handlebars.runtime', 'ji/scholarships/common',
    'templates/common/body', 'templates/common/ibox',
    'templates/scholarships/view', 'templates/scholarships/view-sidebar'
], function (require, exports, module) {
    
    const $            = require('jquery'),
          Handlebars   = require('handlebars.runtime'),
          marked       = require('marked'),
          scholarships = require('ji/scholarships/common');
    
    module.exports = (options) => {
        
        scholarships.processData(options.data);
        
        options.data.content = marked(options.data.content);
        console.log(options.data.content);
        
        let template = require('templates/common/body');
        Handlebars.registerPartial('ibox', require('templates/common/ibox'));
        Handlebars.registerPartial('scholarships-view', require('templates/scholarships/view'));
        Handlebars.registerPartial('scholarships-view-sidebar', require('templates/scholarships/view-sidebar'));
        
        // Main Table
        let config = {
            "id"   : "main-view",
            "title": "Scholarships Detail",
            "body" : [{
                "template": "scholarships-view",
                "data"    : options.data
            }]
        };
        $("#body-wrapper").append(
            template([{
                grid    : 'col-lg-9',
                template: 'ibox',
                data    : config
            }, {
                grid    : 'col-lg-3',
                template: 'scholarships-view-sidebar',
                data    : options.data
            }])
        );
    }
});