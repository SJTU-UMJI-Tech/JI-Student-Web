/**
 * Created by liu on 17-1-28.
 */
;define([
    'require', 'exports', 'module',
    'jquery', 'handlebars.runtime', 'footable',
    'templates/common/ibox.min', 'templates/gpa/graph.min'
], function (require, exports, module) {
    
    var $ = require('jquery');
    var Handlebars = require('handlebars.runtime');
    
    module.exports = function (options) {
    
        var i, j;
    
        var courses = {"course": true};
        courses = options.courses;
        for (i in courses.course) {
            if (courses.course.hasOwnProperty(i)) courses.course[i].id = i;
        }
    
        var score = [{course_id: true, grade: true}];
        score = options.score;
        for (i = 0; i < score.length; i++) {
            if (courses.course.hasOwnProperty(score[i].course_id)) {
                var course = courses.course[score[i].course_id];
                if (score[i].grade == 0) {
                    course.fail = true;
                } else if (score[i].grade > 0) {
                    course.pass = true;
                } else {
                    course.select = true;
                }
                if (course.hasOwnProperty('equivalent')) {
                    for (j = 0; j < course.equivalent.length; j++) {
                        if (courses.course.hasOwnProperty(course.equivalent[j])) {
                            courses.course[course.equivalent[j]].equal = true;
                        }
                    }
                }
            }
        }
    
        var template = require('templates/common/ibox.min');
        Handlebars.registerPartial('graph', require('templates/gpa/graph.min'));
    
        // Main Table
        var config = {
            "id": "graph-table",
            "title": "",
            "body": [{
                "template": "graph",
                "data": {courses: courses.course}
            }]
        };
        $("#body-wrapper").prepend(template(config));
    
        $(".footable").footable();
    
        $(".footable").find(".pagination").on('click', function () {
            window.location.hash = '';
            window.location.hash = 'body-wrapper';
        });
    
    
        // Initialize
    
        const score_to_index_list = {
            '43': 0, '40': 1, '37': 2, '33': 3, '30': 4, '27': 5,
            '23': 6, '20': 7, '17': 8, '10': 9, '0': 10
        };
        const grade_list = ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D', 'F'];
        const color_list = [
            '#7AA7E1', '#5ADED8', '#5CCFA5', '#39E13B', '#77E17A', '#7FE17C',
            '#BBE162', '#DEB367', '#E16158', '#E1487A', '#DE2027'];
    
        $("#graph-table").find(".tr-course").on('click', function (e) {
            if ($(this).data('graph')) {
                var $graph = $(this).next();
                $graph.find(".row").collapse('hide');
                setTimeout(function () {
                    $graph.remove();
                }, 200);
                $(this).data('graph', false);
                return;
            }
            var id = $(this).children("[type=id]").data('id');
            var _this = $(this);
            $.ajax({
                url: '<?php echo ROOT_DIR;?>/GPA/graph_score',
                type: 'GET',
                dataType: 'json',
                data: {id: id},
                success: function (data) {
                    generate_graph(data, _this);
                },
                error: function (data) {
                    console.log('fail');
                }
            });
        });
    
        function generate_graph(score_list, $target) {
            $target.data('graph', true);
        
            var html;
            if (score_list.length === 0) {
                html = ['<div class="row collapsed">',
                    'Sorry, we don\'t have data for this course.',
                    '</div>'].join('');
            }
            else {
                html = ['<div class="row collapsed">',
                    '<div class="graph-line-wrapper col-md-6">',
                    '<strong class="text-center">Line Graph</strong><br>',
                    '<canvas class="graph-line"></canvas>',
                    '</div>',
                    '<div class="graph-pie-wrapper col-md-6">',
                    '<strong class="text-center">Pie Graph</strong><br>',
                    '<canvas class="graph-pie"></canvas>',
                    '</div>',
                    '</div>'].join('');
            }
            $target.after('<tr><td colspan="5" class="text-center td-graph">' + html + '<td></tr>');
            var $graph = $target.next();
        
            if (score_list.length > 0) {
                var display = {
                    grade: [], data: [], color: [], data_all: []
                };
                for (i = 0; i < grade_list.length; i++) {
                    display.data_all.push(0);
                }
                for (i = 0; i < score_list.length; i++) {
                    if (score_to_index_list.hasOwnProperty(score_list[i].grade)) {
                        var index = score_to_index_list[score_list[i].grade];
                        display.grade.push(grade_list[index]);
                        display.data.push(score_list[i]['count(grade)']);
                        display.data_all[index] = score_list[i]['count(grade)'];
                        display.color.push(color_list[index]);
                    }
                }
                //console.log(display.data_all);
            
                // Graph Line
                /*config = {
                 "id": "graph-line-ibox",
                 "title": "Line Graph - ",
                 "body": [{
                 "html": '<div><canvas id="graph-line" height="140"></canvas></div>'
                 }]
                 };
                 $graph.find(".graph-line-wrapper").html(template(config));*/
            
                var lineData = {
                    labels: grade_list,
                    datasets: [
                        {
                            label: "Number",
                            backgroundColor: 'rgba(26,179,148,0.5)',
                            borderColor: "rgba(26,179,148,0.7)",
                            pointBackgroundColor: "rgba(26,179,148,1)",
                            pointBorderColor: "#fff",
                            data: display.data_all
                        }
                    ]
                };
            
                var max = Math.ceil(Math.max.apply(null, display.data) / 5) * 5;
            
                var lineOptions = {
                    responsive: true,
                    legend: {display: false},
                    scales: {
                        yAxes: [{
                            ticks: {
                                max: max,
                                min: 0,
                                stepSize: max / 5
                            }
                        }]
                    }
                };
            
                var graphElement = $graph.find(".graph-line").first();
                var ctx = graphElement[0].getContext("2d");
                //var ctx = document.getElementById("graph-line").getContext("2d");
                new Chart(ctx, {type: 'line', data: lineData, options: lineOptions});
            
                // Graph Pie
                /*config = {
                 "id": "graph-pie-ibox",
                 "title": "Pie Graph - ",
                 "body": [{
                 "html": '<div><canvas id="graph-pie" height="140"></canvas></div>'
                 }]
                 };
                 $graph.find(".graph-pie-wrapper").html(template(config));*/
            
            
                var doughnutData = {
                    labels: display.grade,
                    datasets: [{
                        data: display.data,
                        backgroundColor: display.color
                    }]
                };
                var doughnutOptions = {
                    responsive: true
                };
                graphElement = $graph.find(".graph-pie").first();
                ctx = graphElement[0].getContext("2d");
                //var ctx4 = document.getElementById("graph-pie").getContext("2d");
                new Chart(ctx, {type: 'doughnut', data: doughnutData, options: doughnutOptions});
            }
            $graph.find(".row").collapse();
        
        }
        
    }
    
});