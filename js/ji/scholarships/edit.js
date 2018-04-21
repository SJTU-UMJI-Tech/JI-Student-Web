/**
 * Created by liu on 17-2-13.
 */
"use strict";
define([
    'require', 'exports', 'module',
    'jquery', 'marked', 'handlebars.runtime', 'flatpickr', 'touchspin',
    'ji/common/editormd-loader', 'jquery.fileupload-ui', //'ji/common/fileupload-loader',
    'ji/scholarships/common', 'ji/common/file-icon','templates/common/ibox-article',
    'templates/common/ibox', 'templates/common/ibox-editor','templates/common/modal','templates/common/body'
], function (require, exports, module) {

    const $ = require('jquery'),
        Handlebars = require('handlebars.runtime'),
        marked = require('marked'),
        editormd = require('editormd'),
        scholarships = require('ji/scholarships/common'),
        fileicon = require('ji/common/file-icon');

    module.exports = (options) => {

        scholarships.processData(options.data);

        options.data.attachments = [{
            name: "file1.txt",
            url: "#"
        }, {
            name: "file2.doc",
            url: "#"
        }, {
            name: "file3",
            url: "#"
        }];
        fileicon.processArray(options.data.attachments);

        let template = require('templates/common/ibox');
        Handlebars.registerPartial('ibox-editor', require('templates/common/ibox-editor'));

        let data = [{
            "text": true,
            "title": "Title",
            "name": "title",
            "placeholder": "Title",
            "value": options.data.title
        }, {
            "datetime": true,
            "type": "date",
            "title": "Available Date",
            "name": "start_date",
            "placeholder": "Select a date ...",
            "value": options.data.start_date
        }, {
            "datetime": true,
            "type": "date",
            "title": "Deadline",
            "name": "end_date",
            "placeholder": "Select a date ...",
            "value": options.data.end_date
        }, {
            "spin": true,
            "title": "Limits",
            "placeholder": "The limitation of awarded students",
            "value": options.data.max_num
        }, {
            "textarea": true,
            "title": "Abstract",
            "name": "abstract",
            "placeholder": "Abstract",
            "value": options.data.abstract
        }, {
            "markdown": true,
            "title": "Content",
            "name": "content",
            "value": options.data.content
        }, {
            "file": true,
            "title": "Attachments",
        }];
        // Main Table
        let config = {
            "id": "main-view",
            "title": "Scholarships Edit",
            "body": [{
                "template": "ibox-editor",
                "data": {"data": data}
            }, {
                "center": true,
                "html": '<button id="btn-save" class="btn btn-primary" type="button" data-toggle="modal" data-target="#modal-agree"><i class="fa fa-check"></i>&nbsp;Save</button>'
            }]
        };
        $("#body-wrapper").append(template(config));

        flatpickr(".flatpickr");
        $(".touchspin").TouchSpin();
    
        $(".file-upload").fileupload({
            //dataType: 'json',
            url: '/upload/'
        });
        
        console.log(options.data);

        let testEditor = editormd("test-editormd", {
            width: "100%",
            height: 600,
            path: `${window.ROOT_DIR}/node_modules/editor.md/lib/`,
            markdown: options.data.content,
            codeFold: true,
            searchReplace: true,
            //saveHTMLToTextarea  : true,                // 保存HTML到Textarea
            htmlDecode: "style,script|on*",       // 开启HTML标签解析，为了安全性，默认不开启
            emoji: true,
            taskList: true,
            tex: true,
            tocm: true,         // Using [TOCM]
            autoLoadModules: false,
            previewCodeHighlight: true,
            flowChart: true,
            sequenceDiagram: true,
            //dialogLockScreen : false,   // 设置弹出层对话框不锁屏，全局通用，默认为true
            //dialogShowMask : false,     // 设置弹出层对话框显示透明遮罩层，全局通用，默认为true
            //dialogDraggable : false,    // 设置弹出层对话框不可拖动，全局通用，默认为true
            //dialogMaskOpacity : 0.4,    // 设置透明遮罩层的透明度，全局通用，默认值为0.1
            //dialogMaskBgColor : "#000", // 设置透明遮罩层的背景颜色，全局通用，默认为#fff
            //imageUpload         : true,
            //imageFormats        : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
            //imageUploadURL      : "./php/upload.php",
        });

        const $view = $("#main-view");
        //console.log($view.find(`[data-name='deadline']`).val());

        $("#btn-save").on('click', () => {
            const data = {
                'title': $view.find(`[data-name='title']`).val(),
                'abstract': $view.find(`[data-name='abstract']`).val(),
                'content': testEditor.getMarkdown(),
                'start_date': $view.find(`[data-name='start_date']`).val(),
                'end_date': $view.find(`[data-name='end_date']`).val(),
            };
            console.log(data);
            $.ajax({
                type: 'POST',
                url: options.ajax_url,
                data: {
                    id: options.data.id,
                    data: data
                },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    window.location.reload();
                },
                error: function (data) {
                    console.log(data)
                }
            });
        });

        Handlebars.registerPartial('ibox', require('templates/common/ibox'));
        Handlebars.registerPartial('article', require('templates/common/ibox-article'));

/*        var template_alert = require('templates/common/body');

        $("#body-wrapper").append(template_alert(
            [{
                grid: 'col-lg-10 offset-lg-1',
                template: 'ibox',
                data: config
            }]));*/

        const template_alert = require('templates/common/modal');
        config = {
            "id": "modal-agree",
            "header": {"title": "Confirmation"},
            "body": [{
                "html": "<h3><strong>All the edited terms will be saved.</strong></h3>"
            }],
            "footer": [{
                "button": {
                    "id": "modal-agree-btn-close", "text": "Close",
                    "type": "white", "close": true
                }
            }, {
                "button": {
                    "id": "modal-agree-btn-confirm", "text": "OK",
                    "type": "primary", "href": "all"
                }
            }]
        };
        $("#body-wrapper").append(template_alert(config));

    }
});