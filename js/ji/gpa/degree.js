/**
 * Created by liu on 17-1-28.
 */
;define([
    'require', 'exports', 'module',
    'jquery', 'handlebars.runtime', 'footable', 'chosen',
    'templates/common/ibox', 'templates/gpa/degree'
], function (require, exports, module) {
    
    var $ = require('jquery');
    var Handlebars = require('handlebars.runtime');
    
    module.exports = function (options) {
    
        $("#body-wrapper").append('<div class="alert alert-warning">The Degree Progress Check Sheet is simple and naive now! I will improve the AI later. (version alpha.2.1)</div>');
        
        var i;
        var major = 'ECE';
        var dd = false;
        
        // Process the student's scores
        var score = [{course_id: true, grade: true}];
        score = options.score;
        var score_list = {};
        for (i = 0; i < score.length; i++) {
            score_list[score[i].course_id] = score[i].grade;
        }
        
        // Read the course list
        var courses = {"course": true, "equivalent": true};
        courses = options.courses;
        for (i in courses.course) {
            if (courses.course.hasOwnProperty(i)) courses.course[i].id = i;
        }
        const grade_list = ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D', 'F'];
        
        // Generate the Table Data shown in the page
        var table_data = {};
        var degree = courses.degree[major];
        if (!degree.hasOwnProperty('credit-dd') || !dd) degree['credit-dd'] = {};
        for (i in degree.credit) {
            if (degree.credit.hasOwnProperty(i)) {
                if (degree['credit-dd'].hasOwnProperty(i)) {
                    degree.credit[i] = degree['credit-dd'][i];
                }
                if (degree.credit[i] > 0 && courses.category.hasOwnProperty(i)) {
                    table_data[i] = {
                        title: courses.category[i],
                        course_list: [],
                        credit: degree.credit[i],
                        credit_now: 0,
                        credit_shift: 0,
                        credit_display: 0,
                        gpa: 0
                    };
                }
            }
        }
        
        function setGrade(data, course, id) {
            course.score = 0;
            if (score_list.hasOwnProperty(id)) {
                if (courses.letter.hasOwnProperty(score_list[id])) {
                    course.grade = courses.letter[score_list[id]];
                    course.score = score_list[id];
                    data.credit_now += course.credit;
                    data.credit_grade += course.credit * Math.min(40, score_list[id]);
                }
                course.hide = true;
            }
            data.course_list.push(course);
            delete score_list[id];
        }
        
        // Process courses in "EF", "PS", "SJTU" (not elective)
        var cat_temp = ["EF", "PS", "SJTU"];
        for (i in courses.course) {
            if (courses.course.hasOwnProperty(i)) {
                var course = courses.course[i];
                if (cat_temp.indexOf(course.category) >= 0 &&
                    course.degree.indexOf(major) >= 0) {
                    //table_data[course.category].courses[i] = course;
                    setGrade(table_data[course.category], course, i);
                }
            }
        }
        
        // Remove Equivalent EF courses
        var course_list = table_data["EF"].course_list;
        for (i = 0; i < course_list.length; i++) {
            course = course_list[i];
            if (course.hasOwnProperty('equivalent')) {
                var flag = true;
                if (course.hasOwnProperty('grade')) flag = false;
                else {
                    var name = course.name;
                    var id = course.id;
                }
                for (var j = 0; j < course.equivalent.length; j++) {
                    for (var k = 0; k < course_list.length; k++) {
                        if (course.equivalent[j] === course_list[k].id)break;
                    }
                    if (k === course_list.length)continue;
                    var course_eq = course_list[k];
                    if (!flag) {
                        course_list.splice(k, 1);
                        course_eq.hide = true;
                    }
                    else if (course_eq.hasOwnProperty('grade')) flag = false;
                    else {
                        name += ' / ' + course_eq.name;
                        id += '/' + course_eq.id;
                        course_list.splice(k, 1);
                    }
                }
                if (flag) {
                    course_eq = {
                        name: name,
                        id: id,
                        score: 0
                    };
                    course_list[k] = course_eq;
                } else if (!course.hasOwnProperty('grade')) {
                    for (k = 0; k < course_list.length; k++) {
                        if (course.id === course_list[k].id)break;
                    }
                    if (k < course_list.length) {
                        course_list.splice(k, 1);
                        course.hide = true;
                    }
                }
            }
        }
        
        
        window.console.log(table_data);
        
        // Process elective courses
        
        // Add special courses which is necessary to either program
        function addSpecial(_major, id, category, flag) {
            if (major === _major && (score_list.hasOwnProperty(id) || flag)) {
                setGrade(table_data[category], courses.course[id], id);
            }
        }
        
        addSpecial("ECE", "VG496", "IB", true);
        addSpecial("ME", "VE401", "AM", false);
        addSpecial("ME", "VE301", "AM", false);
        for (i in score_list) {
            if (score_list.hasOwnProperty(i) && courses.course.hasOwnProperty(i)) {
                course = courses.course[i];
                setGrade(table_data[course.category], course, i);
            }
        }
        
        // Shift credits
        if (major === "ECE") {
            cat_temp = ["CE", "UTE", "FTE", "GE"];
        } else if (major === "ME") {
            cat_temp = ["AM", "FTE", "GE"];
        }
        for (i = 0; i < cat_temp.length - 1; i++) {
            var credit = table_data[cat_temp[i]].credit - table_data[cat_temp[i]].credit_shift;
            var credit_now = table_data[cat_temp[i]].credit_now;
            flag = true;
            while (table_data[cat_temp[i]].course_list.length > 0) {
                course = table_data[cat_temp[i]].course_list.pop();
                credit_now -= course.credit;
                if (credit_now >= credit) {
                    table_data[cat_temp[i + 1]].course_list.unshift(course);
                    table_data[cat_temp[i + 1]].credit_now += course.credit;
                } else {
                    flag = false;
                    table_data[cat_temp[i]].course_list.push(course);
                    break;
                }
            }
            if (!flag) credit_now += course.credit;
            table_data[cat_temp[i]].credit_now = credit_now;
            if (credit_now > credit) {
                table_data[cat_temp[i + 1]].credit_shift += credit_now - credit;
            }
        }
        
        // Calculate GPA
        for (i in table_data) {
            if (table_data.hasOwnProperty(i)) {
                credit = table_data[i].credit_now + table_data[i].credit_shift;
                table_data[i].credit_display = Math.min(credit, table_data[i].credit);
                var credit_score = 0;
                for (j = 0; j < table_data[i].course_list.length; j++) {
                    course = table_data[i].course_list[j];
                    credit_score += course.credit * Math.min(40, course.score);
                }
                table_data[i].gpa = table_data[i].credit_now > 0 ?
                    credit_score / 10. / table_data[i].credit_now : 0;
                table_data[i].gpa = Number(table_data[i].gpa).toFixed(3);
            }
        }
        
        
        //var source = $("#ji-ibox-template").html();
        //var template = Handlebars.compile(source);
        //Handlebars.registerPartial('degree', $("#degree-template").html());
        
        var template = require('templates/common/ibox');
        Handlebars.registerPartial('degree', require('templates/gpa/degree'));
        
        var config = {
            "id": "degree",
            "title": "Degree Process Check Sheet",
            "tools": [
                {"collapse": true},
                {"edit": true},
                {"close": true}
            ],
            "body": [{
                "template": "degree",
                "data": {
                    data: table_data,
                    courses: courses,
                    letter: grade_list
                }
            }]
        };
        $("#body-wrapper").append(template(config));
        
        
        $('.chosen-select').chosen({width: "100%"});
        
        // Operations about the degree ibox
        //
        
        var $degree = $("#degree");
        $degree.find(".btn-submit").hide();
        $degree.find(".table-add").hide();
        $degree.find(".div-error").hide();
        
        // Display the credit of the course when selecting
        $degree.find(".select-course").on('change', function () {
            var id = $(this).val();
            var credit = '';
            if (courses.course.hasOwnProperty(id)) credit = courses.course[id].credit;
            $degree.find(".text-credit").html(credit);
        });
        
        // Adding courses into the table on the right and update the chosen plugin
        $degree.find(".select-course").val('').trigger("chosen:updated");
        $degree.find(".select-grade").val('').trigger("chosen:updated");
        $degree.find(".btn-add").on('click', function () {
            var $select = $degree.find(".select-course");
            var id = $select.val();
            if (courses.course.hasOwnProperty(id)) {
                $degree.find(".div-error").collapse('hide');
                setTimeout(function () {
                    $degree.find(".div-error").hide();
                }, 200);
                var course = courses.course[id];
                var html = [
                    '<tr>',
                    '<td type="id">', course.id, '</td>',
                    '<td>', course.name, '</td>',
                    '<td>', course.credit, '</td>',
                    '<td type="grade">', $degree.find(".select-grade").val(), '</td>',
                    '<td><a><i class="fa fa-trash"></i></a></td>',
                    '</tr>'
                ].join('');
                $degree.find(".tbody-add").append(html)
                    .find("a").last().on('click', function () {
                    var $row = $(this).parents("tr").first();
                    var id = $row.find("td").first().html();
                    if (courses.course.hasOwnProperty(id)) {
                        var course = courses.course[id];
                        if (!course.hasOwnProperty('hide') || !course.hide) {
                            $select.find("option[value=" + id + "]").show();
                            if (course.hasOwnProperty('equivalent')) {
                                for (var i = 0; i < course.equivalent.length; i++) {
                                    $select.find("option[value=" + course.equivalent[i] + "]").show();
                                }
                            }
                            $select.trigger("chosen:updated");
                        }
                    }
                    $row.remove();
                });
                $degree.find(".table-add").show();
                $degree.find(".table-add").collapse();
                $degree.find(".btn-submit").show();
                $select.find("option[value=" + id + "]").hide();
                if (course.hasOwnProperty('equivalent')) {
                    for (var i = 0; i < course.equivalent.length; i++) {
                        $select.find("option[value=" + course.equivalent[i] + "]").hide();
                    }
                }
                $select.val('').trigger("chosen:updated");
                $degree.find(".select-grade").val('').trigger("chosen:updated");
            } else {
                $degree.find(".div-error").show();
                $degree.find(".div-error").collapse('show');
            }
        });
        
        // Submit the result through ajax, if success, refresh the page
        $degree.find(".btn-submit").on('click', function () {
            var ajax_data = [];
            $degree.find(".tbody-add tr").each(function () {
                var id = $(this).children("[type=id]").html();
                if (courses.course.hasOwnProperty(id)) {
                    var grade = $(this).children("[type=grade]").html();
                    ajax_data.push({method: "put", course_id: id, grade: grade});
                }
            });
            $degree.find(".tbody-main tr[type=course]").each(function () {
                var id = $(this).children("[type=id]").html();
                if ($(this).data('delete') && courses.course.hasOwnProperty(id)) {
                    ajax_data.push({method: "delete", course_id: id});
                }
            });
            if (ajax_data.length === 0) {
                console.log('empty');
                return;
            }
            console.log(ajax_data);
            $.ajax({
                url: "<?php echo ROOT_DIR;?>/GPA/update",
                method: "POST",
                //data: JSON.stringify(ajax_data),
                data: {data: ajax_data},
                success: function (data) {
                    console.log(data);
                    window.location.reload();
                },
                error: function (data) {
                    console.log('fail');
                }
            });
        });
        
        // The delete button in the check sheet
        $degree.find(".tbody-main i").on('click', function () {
            var $row = $(this).parents("tr").first();
            if (!$row.data('delete')) {
                $row.data('delete', true);
                $(this).removeClass('fa-minus');
                $(this).addClass('fa-plus');
                $row.children().css('text-decoration', 'line-through');
                $degree.find(".btn-submit").show();
            } else {
                $row.data('delete', false);
                $(this).removeClass('fa-plus');
                $(this).addClass('fa-minus');
                $row.children().css('text-decoration', '');
            }
        });
        
        // The edit button on the toolbar
        $degree.find("a.edit-link").on('click', function () {
            if (!$degree.data('edit')) {
                $degree.data('edit', true);
                $degree.find(".tbody-main i").show();
                $degree.find(".module-edit").show();
                $degree.find(".module-edit").collapse('show');
            } else {
                $degree.data('edit', false);
                $degree.find(".tbody-main i").hide();
                setTimeout(function () {
                    $degree.find(".module-edit").hide();
                }, 200);
                $degree.find(".module-edit").collapse('hide');
            }
        });
        
    }
    
});