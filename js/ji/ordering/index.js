/**
 * Created by liu on 17-1-29.
 */
;define([
    'require', 'exports', 'module',
    'jquery', 'handlebars.runtime',
    'templates/common/body', 'templates/common/ibox',
    'templates/common/ibox-article', 'templates/common/modal',
    'templates/ordering/profile'
], function (require, exports, module) {
    
    const $ = require('jquery');
    const Handlebars = require('handlebars.runtime');
    
    module.exports = function (options) {
        
        $("#body-wrapper").append('<div class="alert alert-danger">The water order system is under test, version alpha 1</div>');
        
        const generate_list = (min, max) => {
            let arr = new Array(max - min + 1);
            for (let i = min; i <= max; i++) {
                if (max >= 10 && i < 10) arr[i - min] = "0" + i;
                else arr[i - min] = i;
            }
            return arr;
        };
        
        //Handlebars.registerPartial('ibox', require('templates/common/ibox'));
        //Handlebars.registerPartial('article', require('templates/common/ibox-article'));
        
        let template = require('templates/common/ibox');
        Handlebars.registerPartial('profile', require('templates/ordering/profile'));
        
        let config = {
            "id": "water",
            "title": "Profile",
            "tools": [
                {"collapse": true},
            ],
            "body": [{
                "template": "profile",
                "data": {
                    building: ["D20", "D21", "D22"],
                    floor: generate_list(1, 6),
                    room: generate_list(1, 21),
                    url: options.url
                }
            }]
        };
        $("#body-wrapper").append(template(config));
        
        $("select[title=building]").val(options.profile.building);
        $("select[title=floor]").val(options.profile.floor);
        $("select[title=room]").val(options.profile.room);
        
        
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