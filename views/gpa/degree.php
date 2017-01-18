<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<div id="body-wrapper" class="wrapper wrapper-content animated fadeInRight">
    <div class="alert alert-warning">
        The Degree Progress Check Sheet is simple and naive now!
        I will improve the AI later.
    </div>


</div>

<?php include dirname(dirname(__FILE__)) . '/common/scripts.php'; ?>

<?php include dirname(dirname(__FILE__)) . '/common/templates/ibox.hbs'; ?>

<?php include 'templates/degree.hbs'; ?>

<script type="text/javascript">
    
    require(['jquery', 'handlebars', 'footable', 'chosen'], function ($, Handlebars) {
        $(document).ready(function () {
            var i;
            var major = 'ECE';
            var dd = false;
            
            // Process the student's scores
            <?php /** @var $score string */?>
            var score = [{course_id: true, grade: true}];
            score = <?php echo $score;?>;
            var score_list = {};
            for (i = 0; i < score.length; i++) {
                score_list[score[i].course_id] = score[i].grade;
            }
            
            // Read the course list
            var courses = {"course": true, "equivalent": true};
            <?php /** @var $courses string */?>
            courses = <?php echo $courses;?>;
            for (i in courses.course) {
                if (courses.course.hasOwnProperty(i))courses.course[i].id = i;
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
                        course.hide = true;
                        data.credit_now += course.credit;
                        data.credit_grade += course.credit * Math.min(40, score_list[id]);
                    }
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
                            name += '/' + course_eq.name;
                            id += '/' + course_eq.id;
                            course_list.splice(k, 1);
                        }
                    }
                    if (flag) {
                        course.name = name;
                        course.id = id;
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
            function addSpecial(_major, id, category) {
                if (major === _major && score_list.hasOwnProperty(id)) {
                    setGrade(table_data[category], courses.course[id], id);
                }
            }
            
            addSpecial("ECE", "VG496", "IB");
            addSpecial("ME", "VE401", "AM");
            addSpecial("ME", "VE301", "AM");
            for (i in score_list) {
                if (score_list.hasOwnProperty(i) && courses.course.hasOwnProperty(i)) {
                    course = courses.course[i];
                    setGrade(table_data[course.category], course, i);
                }
            }
            
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
            
            
            var source = $("#ji-ibox-template").html();
            var template = Handlebars.compile(source);
            Handlebars.registerPartial('degree', $("#degree-template").html());
            
            //console.log(courses);
            table_data.courses = courses;
            table_data.letter = grade_list;
            
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
                    "data": table_data
                }]
            };
            $("#body-wrapper").append(template(config));
            
            var $degree = $("#degree");
            
            $('.chosen-select').chosen({width: "100%"});
            
            $degree.find(".select-course").on('change', function () {
                var id = $(this).val();
                var credit = '';
                if (courses.course.hasOwnProperty(id))credit = courses.course[id].credit;
                $degree.find(".text-credit").html(credit);
            });
            
            $degree.find(".btn-add").on('click', function () {
                var $select = $degree.find(".select-course");
                var id = $select.val();
                if (courses.course.hasOwnProperty(id)) {
                    $degree.find(".div-error").collapse('hide');
                    var course = courses.course[id];
                    var html = [
                        '<tr>',
                        '<th>', course.id, '</th>',
                        '<th>', course.name, '</th>',
                        '<th>', course.credit, '</th>',
                        '<th>', $degree.find(".select-grade").val(), '</th>',
                        '<th><a><i class="fa fa-trash"></i></a></th>',
                        '</tr>'
                    ].join('');
                    $degree.find(".tbody-add").append(html)
                            .find("a").last().on('click', function () {
                        var $row = $(this).parents("tr").first();
                        var id = $row.find("th").first().html();
                        if (courses.course.hasOwnProperty(id)) {
                            var course = courses.course[id];
                            if (!course.hasOwnProperty('hide') || !course.hide) {
                                $select.find("option[value=" + id + "]").css('display', '');
                                $select.trigger("chosen:updated");
                            }
                        }
                        $row.remove();
                    });
                    $degree.find(".table-add").css('display', '');
                    $degree.find(".table-add").collapse();
                    $select.find("option[value=" + id + "]").css('display', 'none');
                    $select.val('');
                    $select.trigger("chosen:updated");
                } else {
                    $degree.find(".div-error").css('display', '');
                    $degree.find(".div-error").collapse('show');
                }
            });
            
            $degree.find(".tbody-main i").on('click', function () {
                var $row = $(this).parents("tr").first();
                if (!$row.data('delete')) {
                    $row.data('delete', true);
                    $(this).removeClass('fa-minus');
                    $(this).addClass('fa-plus');
                    $row.children().css('text-decoration', 'line-through');
                } else {
                    $row.data('delete', false);
                    $(this).removeClass('fa-plus');
                    $(this).addClass('fa-minus');
                    $row.children().css('text-decoration', '');
                }
            });
            
            $degree.find("a.edit-link").on('click', function () {
                if (!$degree.data('edit')) {
                    $degree.data('edit', true);
                    $degree.find(".tbody-main i").css('display', '');
                    $degree.find(".module-edit").css('display', '');
                    
                } else {
                    $degree.data('edit', false);
                    $degree.find(".tbody-main i").css('display', 'none');
                    $degree.find(".module-edit").css('display', 'none');
                }
            });
            
        });
    });

</script>


<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>

