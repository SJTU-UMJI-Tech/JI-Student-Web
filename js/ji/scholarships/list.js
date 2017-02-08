/**
 * Created by liu on 17-1-29.
 */
;define([
    'require', 'exports', 'module',
    'jquery', 'ji-list-view',
    'templates/common/ibox', 'templates/scholarships/list',
    'js/templates/scholarships/list-item.min'
], function (require, exports, module) {
    
    var $ = require('jquery');
    
    module.exports = function (options) {
    
        function base_url(str) {
            return window.ROOT_DIR + '/scholarships/' + str;
        }
    
        $("#body-wrapper").jiListView({
            title: "Scholarships",
            url: {
                view: base_url('view'),
                edit: base_url('edit'),
                search: base_url('ajax_search')
            },
            templates: {
                "ibox": require('templates/common/ibox'),
                "list": require('templates/scholarships/list'),
                "item": require('js/templates/scholarships/list-item.min')
            },
            processRow: function (row) {
                var start = new Date(row.start_date).getTime();
                var end = new Date(row.end_date).getTime();
                var now = new Date().getTime();
                var percent = Math.round((now - start) / (end - start) * 100.);
                if (percent > 100) {
                    row.closed = true;
                } else if (percent >= 0) {
                    row.active = true;
                }
                percent = Math.max(0, Math.min(100, percent));
                if (percent === 100) row.time_after = true;
            
                row.time_percent = percent;
            
                percent = Math.round(row.current_num / row.max_num * 100.);
                percent = Math.max(0, Math.min(100, percent));
                row.people_percent = percent;
                if (percent === 100) row.people_full = true;
            
                row.url_view = base_url('view');
                row.url_edit = base_url('edit');
            
                return row;
            }
        });
        
    }
    
});