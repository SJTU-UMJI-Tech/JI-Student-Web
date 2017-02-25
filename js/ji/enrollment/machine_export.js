/**
 * Created by liu on 2017/2/25.
 */
define([
    'require', 'exports', 'module',
    'jquery', 'handlebars.runtime',
    'templates/common/ibox'
], function (require, exports, module) {
    
    const $          = require('jquery'),
          Handlebars = require('handlebars.runtime');
    
    module.exports = (options) => {
        
        const template = require('templates/common/ibox');
        
        const html = [
            '<div class="row">',
            '<div class="card col-lg-6 offset-lg-3">',
            '<div class="card-block text-center">',
            '<h2 class="card-title">Use the link to Download</h2>',
            '<hr>',
            '<a href="', options.file_url, '" class="btn btn-primary">Download</a>',
            '</div>',
            '</div>',
            '</div>'
        ].join('');
        
        const config = {
            "title": "Result Workbook Download",
            "body" : [{
                "html": html
            }]
        };
        
        $("#body-wrapper").append(template(config));
    }
});