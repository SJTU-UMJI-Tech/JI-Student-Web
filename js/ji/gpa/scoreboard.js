/**
 * Created by liu on 17-1-28.
 */
;define([
    'require', 'exports', 'module',
    'jquery', 'handlebars.runtime', 'footable',
    'templates/common/ibox', 'templates/gpa/scoreboard'
], function (require, exports, module) {
    
    var $ = require('jquery');
    var Handlebars = require('handlebars.runtime');
    
    module.exports = function (options) {
    
        var template = require('templates/common/ibox');
        Handlebars.registerPartial('scoreboard', require('templates/gpa/scoreboard'));
        
        var config = {
            "id": "scoreboard",
            "title": "UM-SJTU-JI GPA SCOREBOARD",
            "body": [{
                "template": "scoreboard",
                "data": {
                    "rows": options.scoreboard
                }
            }]
        };
        $("#body-wrapper").append(template(config));
        
        $(".footable").footable();
        
    }
    
});