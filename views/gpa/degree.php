<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<div id="body-wrapper" class="wrapper wrapper-content animated fadeInRight">
    
    <div class="alert alert-warning">
        This module is in alpha version, more functions will be added soon. Please contact the developers if you have
        any issue.
    </div>

</div>

<?php include dirname(dirname(__FILE__)) . '/common/scripts.php'; ?>

<?php include dirname(dirname(__FILE__)) . '/common/templates/ibox.hbs'; ?>

<?php include 'templates/degree.hbs'; ?>

<script type="text/javascript">
    
    require(['jquery', 'handlebars', 'footable'], function ($, Handlebars) {
        $(document).ready(function () {
            
            var major = 'ECE';
            var dd = false;
            
            // Process the student's scores
            var score = [];
            score = <?php echo $score;?>;
            var score_list = {};
            for (var i = 0; i < score.length; i++) {
                score_list[score[i].course_id] = score[i].grade;
            }
            console.log(score_list);
            
            // Read the course list
            var courses = {};
            courses = <?php echo $courses;?>;
            
            // Generate the Table Data shown in the page
            var table_data = {};
            var degree = courses.degree[major];
            if (!degree.hasOwnProperty('credit-dd') || !dd) degree['credit-dd'] = {};
            for (i in degree.credit) {
                if (degree.credit.hasOwnProperty(i)) {
                    if (degree['credit-dd'].hasOwnProperty(i)) {
                        //console.log(i);
                        degree.credit[i] = degree['credit-dd'][i];
                    }
                    if (degree.credit[i] > 0 && courses.category.hasOwnProperty(i)) {
                        table_data[i] = {
                            title: courses.category[i],
                            courses: {},
                            credit: degree.credit[i]
                        };
                    }
                }
            }
            
            function setGrade(course, i) {
                course.id = i;
                if (score_list.hasOwnProperty(i)) {
                    if (courses.letter.hasOwnProperty(score_list[i])) {
                        course.grade = courses.letter[score_list[i]];
                        course.score = score_list[i];
                    }
                    delete score_list[i];
                }
            }
            
            // Process courses in "EF", "PS", "SJTU"
            var cat_temp = ["EF", "PS", "SJTU"];
            for (i in courses.course) {
                if (courses.course.hasOwnProperty(i)) {
                    var course = courses.course[i];
                    for (j = 0; j < cat_temp.length; j++) {
                        if (course.category == cat_temp[j] && course.degree.indexOf(major) >= 0) {
                            setGrade(course, i);
                            table_data[cat_temp[j]].courses[i] = course;
                            break;
                        }
                    }
                }
            }
            
            // Remove Equivalent EF courses
            var _courses = table_data["EF"].courses;
            for (i in _courses) {
                if (_courses.hasOwnProperty(i)) {
                    course = _courses[i];
                    if (course.hasOwnProperty('equivalent')) {
                        var flag = true;
                        if (course.hasOwnProperty('grade')) flag = false;
                        else {
                            var name = course.name;
                            var id = course.id;
                        }
                        for (var j = 0; j < course.equivalent.length; j++) {
                            if (_courses.hasOwnProperty(course.equivalent[j])) {
                                if (!flag) delete _courses[course.equivalent[j]];
                                else if (_courses[course.equivalent[j]].hasOwnProperty('grade')) flag = false;
                                else {
                                    name += '/' + _courses[course.equivalent[j]].name;
                                    id += '/' + _courses[course.equivalent[j]].id;
                                    delete _courses[course.equivalent[j]];
                                }
                            }
                        }
                        if (flag) {
                            course.name = name;
                            course.id = id;
                        } else if (!course.hasOwnProperty('grade')) {
                            delete _courses[i];
                        }
                    }
                }
            }
            
            console.log(table_data);
            
            // Process courses in "IB"
            const ECE_IB = 'VG496';
            _courses = table_data["IB"].courses;
            if (major == "ECE" && score_list.hasOwnProperty(ECE_IB)) {
                _courses[ECE_IB] = courses.course[ECE_IB];
                setGrade(_courses[ECE_IB], ECE_IB);
            }
            for (i in score_list) {
                if (score_list.hasOwnProperty(i) && courses.course.hasOwnProperty(i)) {
                    _courses[i] = courses.course[i];
                    setGrade(_courses[i], i);
                }
            }
            
            
            
            
            // Calculate the credits and GPA
            for (i in table_data) {
                if (table_data.hasOwnProperty(i)) {
                    var credit = 0;
                    var credit_score = 0;
                    for (j in table_data[i].courses) {
                        if (table_data[i].courses.hasOwnProperty(j)) {
                            course = table_data[i].courses[j];
                            if (course.hasOwnProperty('grade')) {
                                credit += course.credit;
                                credit_score += course.score * course.credit;
                            }
                        }
                    }
                    table_data[i].credit_now = credit;
                    table_data[i].gpa = credit > 0 ? credit_score / 10. / credit : 0;
                }
            }
            
            
            var source = $("#ji-ibox-template").html();
            var template = Handlebars.compile(source);
            Handlebars.registerPartial('degree', $("#degree-template").html());
            
            var config = {
                "id": "degree",
                "title": "Degree Process Check Sheet",
                "body": [{
                    "template": "degree",
                    "data": table_data
                }]
            };
            $("#body-wrapper").append(template(config));
        });
    });

</script>


<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>


