/**
 * Created by liu on 2017/2/15.
 */
"use strict";
define([
    'require', 'exports', 'module',
    'jquery', 'handlebars.runtime', 'qrcodejs',
    'templates/common/ibox', 'templates/enrollment/machine'
], function (require, exports, module) {
    
    const $          = require('jquery'),
          Handlebars = require('handlebars.runtime');
    
    module.exports = (options) => {
        
        let template = require('templates/common/ibox');
        Handlebars.registerPartial('machine', require('templates/enrollment/machine'));
        
        options.member = [
            {user_name: "aaa", user_id: "111", verified: false},
            {user_name: "bbb", user_id: "222", verified: true},
            {user_name: "ccc", user_id: "333", verified: false}
        ];
        
        options.group_id  = 1;
        options.group_url = 'http://www.umji.sjtu.edu.cn/student/Enrollment/Machine/verify?id=1';
        
        let config = {
            "id"   : "machine",
            "title": "Mechanic Competition",
            "body" : [{
                "template": "machine",
                "data"    : options
            }]
        };
        
        $("#body-wrapper").append(template(config));
        
        const $qrcode = $(".qrcode");
        if ($qrcode.length > 0) {
            new QRCode($qrcode.first()[0], options.group_url);
        }
        
        //
    }
});