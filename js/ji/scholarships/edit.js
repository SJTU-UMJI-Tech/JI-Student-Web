/**
 * Created by liu on 17-2-13.
 */
"use strict";
define([
    'require', 'exports', 'module',
    'jquery', 'marked', 'handlebars.runtime', 'flatpickr', 'touchspin',
    'ji/common/editormd-loader',
    'ji/scholarships/common', 'ji/common/file-icon',
    'templates/common/ibox', 'templates/common/ibox-editor'
], function (require, exports, module) {
    
    const $            = require('jquery'),
          Handlebars   = require('handlebars.runtime'),
          marked       = require('marked'),
          editormd     = require('ji/common/editormd-loader'),
          scholarships = require('ji/scholarships/common'),
          fileicon     = require('ji/common/file-icon');
    
    module.exports = (options) => {
        
        scholarships.processData(options.data);
        
        options.data.content     = marked(options.data.content);
        options.data.attachments = [{
            name: "file1.txt",
            url : "#"
        }, {
            name: "file2.doc",
            url : "#"
        }, {
            name: "file3",
            url : "#"
        }];
        fileicon.processArray(options.data.attachments);
        
        let template = require('templates/common/ibox');
        Handlebars.registerPartial('ibox-editor', require('templates/common/ibox-editor'));
        
        let data   = [{
            "text"       : true,
            "name"       : "Title",
            "placeholder": "Title",
            "value"      : options.data.title
        }, {
            "datetime"   : true,
            "type"       : "date",
            "name"       : "Available Date",
            "placeholder": "Select a date ...",
            "value"      : options.data.start_date
        }, {
            "datetime"   : true,
            "type"       : "date",
            "name"       : "Deadline",
            "placeholder": "Select a date ...",
            "value"      : options.data.end_date
        }, {
            "spin"       : true,
            "name"       : "Limits",
            "placeholder": "The limitation of awarded students",
            "value"      : options.data.max_num
        }, {
            "textarea"   : true,
            "name"       : "Abstract",
            "placeholder": "Abstract",
            "value"      : options.data.abstract
        }, {
            "markdown"   : true,
            "name"       : "Content",
            "placeholder": "Content",
            "value"      : options.data.content
        }];
        // Main Table
        let config = {
            "id"   : "main-view",
            "title": "Scholarships Edit",
            "body" : [{
                "template": "ibox-editor",
                "data"    : {"data": data}
            }]
        };
        $("#body-wrapper").append(template(config));
        
        flatpickr(".flatpickr");
        $(".touchspin").TouchSpin();
        
        editormd("test-editormd", {
            width             : "100%",
            height            : 800,
            syncScrolling     : "single",
            path              : "/node_modules/editor.md/lib/",
            saveHTMLToTextarea: true,
            flowchart         : true
        });
    }
});