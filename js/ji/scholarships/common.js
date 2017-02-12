/**
 * Created by liu on 17-2-12.
 */
"use strict";
define([
    'require', 'exports', 'module',
], function (require, exports, module) {
    
    module.exports = {
        
        base_url: (str) => {
            return window.ROOT_DIR + '/scholarships/' + str;
        },
        
        processData: (data) => {
            
            let start   = new Date(data.start_date).getTime(),
                end     = new Date(data.end_date).getTime(),
                now     = new Date().getTime(),
                percent = Math.round((now - start) / (end - start) * 100.);
            if (percent > 100) {
                data.closed = true;
            } else if (percent >= 0) {
                data.active = true;
            }
            percent = Math.max(0, Math.min(100, percent));
            if (percent === 100) data.time_after = true;
            
            data.time_percent = percent;
            
            percent = Math.round(data.current_num / data.max_num * 100.);
            percent = Math.max(0, Math.min(100, percent));
            
            data.people_percent = percent;
            
            if (percent === 100) data.people_full = true;
            
            data.url_view = module.exports.base_url('view');
            data.url_edit = module.exports.base_url('edit');
            
            return data;
        }
    }
    
});