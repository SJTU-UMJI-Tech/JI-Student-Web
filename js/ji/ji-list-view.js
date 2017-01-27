/**
 * Created by liu on 17-1-27.
 */
;(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as anonymous module.
        define(['jquery', 'handlebars'], factory);
    }
    else if (typeof exports === 'object') {
        // Node / CommonJS
        factory(require('jquery'));
    }
    else {
        // Browser globals.
        factory(jQuery);
    }
})(function ($, Handlebars) {
    
    'use strict';
    
    function JIListView($element, options) {
        var i;
        
        this.$container = $element;
        
        if (options.templates) {
            if (options.templates.list) {
                Handlebars.registerPartial('list', options.templates.list);
            } else return;
            if (options.templates.ibox) {
                var config = {
                    title: options.title || "Title",
                    body: [{
                        template: "list"
                    }]
                };
                this.iboxTemplate = Handlebars.compile(options.templates.ibox);
                this.$container.append(this.iboxTemplate(config));
            } else return;
            if (options.templates.item) {
                this.itemTemplate = Handlebars.compile(options.templates.item);
            } else return;
        }
        
        this.$list = this.$container.find("tbody");
        
        this.listData = {};
        
        this.expand();
        
        
    }
    
    JIListView.prototype = {
        
        constructor: JIListView,
        
        expand: function () {
            var _this = this;
            
            this.$list.find(".btn-expand").parentsUntil("tr").remove();
            
            $.ajax({
                type: 'GET',
                url: window.ROOT_DIR + '/scholarships/ajax_search',
                data: {
                    cmd: 'search',
                    key: 'all',
                    keywords: '',
                    order: 'Newest',
                    limit: 5,
                    offset: 0
                },
                dataType: 'json',
                success: function (data) {
                    _this.addItem(data);
                },
                error: function () {
                    console.log('fail')
                }
            });
            
        },
        
        addItem: function (data) {
            var newFlag = false;
            for (var i = 0; i < data.length; i++) {
                var row = data[i];
                if (this.listData.hasOwnProperty(row.id)) {
                    // Update
                    
                } else {
                    // Create
                    this.listData[data.id] = row;
                    this.$list.append(this.itemTemplate(row));
                    console.log(row);
                    newFlag = true;
                }
                
            }
            
            if (!newFlag) {
            
            }
            else {
                this.$list.append('<tr><td colspan="5" class="text-center"><a class="btn-link btn-expand"><i class="fa fa-angle-double-down" aria-hidden="true"></i></a></td></tr>');
                this.$list.find(".btn-expand").on('click', $.proxy(this.expand, this));
            }
        }
        
        
    };
    
    $.fn.jiListView = function (option) {
        return new JIListView(this, option);
    };
    
});