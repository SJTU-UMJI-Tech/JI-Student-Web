/**
 * Created by liu on 17-1-29.
 */
;define([
    'require', 'exports', 'module',
    'jquery', 'ji/ji-list-view', 'ji/scholarships/common',
    'templates/common/ibox', 'templates/scholarships/list',
    'templates/scholarships/list-item'
], function (require, exports, module) {
    
    const $            = require('jquery'),
          scholarships = require('ji/scholarships/common');
    
    module.exports = function (options) {
        
        $("#body-wrapper").jiListView({
            title     : "Scholarships",
            url       : {
                view  : scholarships.base_url('view'),
                edit  : scholarships.base_url('edit'),
                search: scholarships.base_url('ajax_search')
            },
            templates : {
                "ibox": require('templates/common/ibox'),
                "list": require('templates/scholarships/list'),
                "item": require('templates/scholarships/list-item')
            },
            processRow: scholarships.processData
        });
        
    }
    
});