/**
 * Created by liu on 17-1-28.
 */
;define([
    'require', 'exports', 'module',
    'jquery', 'handlebars.runtime', 'footable',
    'js/templates/common/ibox.min', 'js/templates/gpa/scoreboard.min'
], function (require, exports, module) {
    
    var $ = require('jquery');
    var Handlebars = require('handlebars.runtime');
    
    module.exports = function (options) {
    
        var template = require('js/templates/common/ibox.min');
        Handlebars.registerPartial('scoreboard', require('js/templates/gpa/scoreboard.min'));
        
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