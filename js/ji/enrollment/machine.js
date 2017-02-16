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
        
        const template = require('templates/common/ibox');
        Handlebars.registerPartial('machine', require('templates/enrollment/machine'));
        
        let member_info = {};
        for (let i = 0; i < options.member_info.length; i++) {
            let member                  = options.member_info[i];
            member.verified             = member.verified > 0;
            member_info[member.USER_ID] = member;
        }
        options.member = [];
        for (let i = 0; i < options.member_list.length; i++) {
            let user_id = options.member_list[i];
            if (member_info.hasOwnProperty(user_id)) {
                options.member.push(member_info[user_id]);
            } else {
                options.member.push({USER_ID: user_id, verified: false});
            }
        }
        
        const config = {
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
        
        const $main = $("#machine");
        
        $main.find(".btn-expand").on('click', function () {
            $main.find(".main-form").show().collapse();
        });
        
        $main.find(".btn-add").on('click', function () {
            const $list = $main.find("tbody tr");
            if ($list.length >= options.member_max) {
                alert(`队伍最多由1位队长和${options.member_max}位队员组成`);
                return;
            }
            const user_id = prompt("请输入被邀请队员的学号");
            if (user_id) {
                if (!user_id.match(/^(\d{12}|\d{10})$/)) {
                    alert("学号格式不合法，请再输一次");
                    return;
                }
                if (user_id == options.user_id) {
                    alert("请不要邀请自己");
                    return;
                }
                let duplicate = false;
                $list.each(function () {
                    if (!duplicate && $(this).data('id') == user_id) duplicate = true;
                });
                if (duplicate) alert("已经邀请过这位队员了");
                else $main.find("tbody").append(`<tr data-id="${user_id}"><td>${user_id}</td><td class="td-name"></td><td></td><td align="center"><a class="btn-delete" href="javascript:void(0);"><i class="fa fa-trash"></i></a></td></tr>`);
                const $tr   = $main.find("tbody tr").last();
                const $name = $tr.find(".td-name");
                $tr.find(".btn-delete").on('click', onBtnDelete);
                $.ajax({
                    type    : 'GET',
                    url     : options.check_url,
                    data    : {user_id: user_id,},
                    dataType: 'json',
                    success : (data) => {
                        if (!$name)return;
                        if (data.status === 'error') {
                            $name.html('<small>暂无</small>');
                        } else {
                            $name.html(data.name);
                        }
                    }
                });
            }
        });
        
        const onBtnDelete = function (e) {
            const $element = $(e.target).parents("tr");
            const user_id  = $element.data('id');
            const flag     = confirm(`你确定要删除学号为 ${user_id} 的队员吗？\n(删除后需要提交才能生效)`);
            if (flag) $element.remove();
        };
        
        $main.find(".btn-delete").on('click', onBtnDelete);
        
        $main.find(".btn-submit").on('click', function () {
            const class_id  = $main.find(".input-class-id").val();
            let member_list = [];
            $main.find("tbody tr").each(function () {
                member_list.push($(this).data('id'));
            });
            $.ajax({
                type    : 'GET',
                url     : options.submit_url,
                data    : {
                    class_id   : class_id,
                    member_list: member_list.join(',')
                },
                dataType: 'json',
                success : (data) => {
                    if (data.status === 'error') {
                        alert(data.message);
                    } else {
                        window.location.reload();
                    }
                },
                error   : () => {
                    alert("发生未知错误，请稍后重试");
                }
            });
        });
        
        $main.find(".btn-cancel").on('click', function () {
            let flag = confirm(`你确定取消报名吗？\n(该操作是不可逆的，请慎重操作)`);
            if (flag && options.leader) {
                flag = confirm(`你确定取消报名吗？\n(队长取消报名会使所有队员一起取消报名，请慎重操作)`);
            }
            if (flag) {
                window.location.href = options.cancel_url;
            }
        });
    }
});