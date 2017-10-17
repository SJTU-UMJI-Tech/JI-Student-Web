/**
 * Created by liu on 17-1-29.
 */
;define([
    'require', 'exports', 'module',
    'jquery', 'handlebars.runtime',
    'templates/common/body', 'templates/common/ibox',
    'templates/common/ibox-article', 'templates/common/modal',
    'templates/ordering/water'
], function (require, exports, module) {

    const $ = require('jquery');
    const Handlebars = require('handlebars.runtime');

    module.exports = function (options) {

        $("#body-wrapper").append('<div class="alert alert-danger">The water order system is under test, version alpha 1</div>');

        //Handlebars.registerPartial('ibox', require('templates/common/ibox'));
        //Handlebars.registerPartial('article', require('templates/common/ibox-article'));

        let template = require('templates/common/ibox');
        Handlebars.registerPartial('water', require('templates/ordering/water'));

        let config = {
            "id": "water",
            "title": "Order water",
            "tools": [
                {"collapse": true},
            ],
            "body": [{
                "template": "water",
                "data": {
                    "orders": [{
                        "paid": true,
                        "num": 10,
                        "time": "2017-10-17 12:00",
                        "address": "D20-405"
                    }]
                }
            }]
        };
        $("#body-wrapper").append(template(config));

        /*var config = {
            "id": "article-body",
            "body": [{
                "template": "article",
                "data": options.terms_body
            }, {
                "center": true,
                "html": '<button id="btn-agree" class="btn btn-primary" type="button" data-toggle="modal" data-target="#modal-agree"><i class="fa fa-check"></i>&nbsp;Agree</button>'
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
                    "type": "primary", "href": options.confirm_url
                }
            }]
        };
        $("#body-wrapper").append(template(config));*/

    }

});