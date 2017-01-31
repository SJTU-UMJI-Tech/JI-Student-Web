/**
 * Created by liu on 17-1-29.
 */
;define([
    'require', 'exports', 'module',
    'jquery', 'handlebars.runtime',
    'js/templates/common/body.min', 'js/templates/common/ibox.min',
    'js/templates/common/ibox-article.min', 'js/templates/common/modal.min'
], function (require, exports, module) {
    
    var $ = require('jquery');
    var Handlebars = require('handlebars.runtime');
    
    module.exports = function (options) {
        
        Handlebars.registerPartial('ibox', require('js/templates/common/ibox.min'));
        Handlebars.registerPartial('article', require('js/templates/common/ibox-article.min'));
        
        var config = {
            "id": "article-body",
            "body": [{
                "template": "article",
                "data": options.terms_body
            }, {
                "center": true,
                "html": '<button id="btn-agree" class="btn btn-primary" type="button" data-toggle="modal" data-target="#modal-agree"><i class="fa fa-check"></i>&nbsp;Agree</button>'
            }]
        };
        
        var template = require('js/templates/common/body.min');
        
        $("#body-wrapper").append(template(
            [{
                grid: 'col-lg-10 col-lg-offset-1',
                template: 'ibox',
                data: config
            }]));
        
        template = require('js/templates/common/modal.min');
        config = {
            "id": "modal-agree",
            "header": {"title": "Confirmation"},
            "body": [{
                "html": "<h3><strong>I've read the terms of service and I agreed about all of the items.</strong></h3>"
            }],
            "footer": [{
                "button": {
                    "id": "modal-agree-btn-close", "text": "Close",
                    "type": "white", "close": true
                }
            }, {
                "button": {
                    "id": "modal-agree-btn-confirm", "text": "Confirm",
                    "type": "primary", "href": "<?php echo base_url('GPA/terms?confirm=1');?>"
                }
            }]
        };
        $("#body-wrapper").append(template(config));
        
    }
    
});