;define([
    'require', 'exports', 'module',
    'jquery', 'handlebars.runtime',
    'templates/common/body', 'templates/common/ibox',
    'templates/common/ibox-article', 'templates/common/modal'
], function (require, exports, module) {

    var $ = require('jquery');
    var Handlebars = require('handlebars.runtime');

    module.exports = function (options) {

        Handlebars.registerPartial('ibox', require('templates/common/ibox'));
        Handlebars.registerPartial('article', require('templates/common/ibox-article'));

        var config = {
            "id": "article-body",
            "body": [{
                "template": "article",
                "data": options.terms_body
            }, {
                //"center": true,
                //"html": '<button id="btn-agree" class="btn btn-primary" type="button" data-toggle="modal" data-target="#modal-agree"><i class="fa fa-check"></i>&nbsp;Agree</button>'
            }]
        };

        var template = require('templates/common/body');

        $("#body-wrapper").append(template(
            [{
                grid: 'col-lg-10 offset-lg-1',
                template: 'ibox',
                data: config
            }]));

        template = require('templates/common/modal');
        config = {
            "id": "modal-agree",
            "header": {"title": "Confirmation"},
            "body": [{
                "html": "<h3><strong>I've read the terms and I agreed about all of the items.</strong></h3>"
            }],
            "footer": [{
                "button": {
                    "id": "modal-agree-btn-close", "text": "Close",
                    "type": "white", "close": true
                }
            }, {
                "button": {
                    "id": "modal-agree-btn-confirm", "text": "Confirm",
                    "type": "primary", "href": "scholarships/all"
                }
            }]
        };
        $("#body-wrapper").append(template(config));

    }

});