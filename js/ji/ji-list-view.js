/**
 * Created by liu on 17-1-27.
 */
;(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as anonymous module.
        define(['jquery', 'handlebars.runtime'], factory);
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
        this.url = options.url;
        
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
                this.iboxTemplate = options.templates.ibox;
                this.$container.append(this.iboxTemplate(config));
            } else return;
            if (options.templates.item) {
                this.itemTemplate = options.templates.item;
            } else return;
        }
        
        this.$list = this.$container.find("tbody");
        this.processRow = options.processRow;
        this.listData = {};
        this.listNum = 0;
        
        this.expand();
        
        
    }
    
    JIListView.prototype = {
        
        constructor: JIListView,
        
        expand: function () {
            var _this = this;
            
            this.$list.find(".btn-expand").parentsUntil("tr").remove();
            
            $.ajax({
                type: 'GET',
                url: this.url.search || './ajax_search',
                data: {
                    cmd: 'search',
                    key: 'all',
                    keywords: '',
                    order: 'Newest',
                    limit: 2,
                    offset: this.listNum
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
                var row = this.processRow(data[i]);
                
                if (this.listData.hasOwnProperty(row.id)) {
                    // Update
                    var $old = this.$list.find("tr[data-id=" + row.id + "]");
                    $old.html(this.itemTemplate(row));
                    //console.log($old);
                } else {
                    // Create
                    this.listData[row.id] = row;
                    this.listNum++;
                    this.$list.append(this.itemTemplate(row));
                    var $new = this.$list.find("tr[data-id=" + row.id + "]");
                    
                    $new.on('click', $.proxy(this.itemCollapse, this));
                    
                    //console.log(row);
                    newFlag = true;
                }
            }
            
            if (!newFlag) {
            
            }
            else {
                this.$list.append('<tr><td colspan="5" class="text-center"><a class="btn-link btn-expand"><i class="fa fa-angle-double-down" aria-hidden="true"></i></a></td></tr>');
                this.$list.find(".btn-expand").on('click', $.proxy(this.expand, this));
            }
        },
        
        itemCollapse: function (e) {
            var $row = $(e.target).parents("tr").first();
            if ($row.data('collapse')) {
                $row.data('collapse', false);
                $row.next().find(".collapsed").collapse('hide');
                setTimeout(function () {
                    $row.next().remove();
                }, 200);
            } else {
                $row.data('collapse', true);
                var id = $row.data('id');
                if (this.listData.hasOwnProperty(id)) {
                    var row = this.listData[id];
                    $row.after('<tr><td colspan="5"><div class="collapsed">' + row.abstract + '</div></td></tr>');
                    $row.next().find(".collapsed").collapse();
                }
            }
        }
        
        
    };
    
    $.fn.jiListView = function (option) {
        return new JIListView(this, option);
    };
    
});