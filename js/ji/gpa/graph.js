/**
 * Created by liu on 17-1-28.
 */
;define([
    'require', 'exports', 'module',
    'jquery', 'handlebars.runtime', 'footable', 'chartjs',
    'templates/common/ibox', 'templates/common/modal', 'templates/gpa/graph'
], function (require, exports, module) {

    const $          = require('jquery'),
          Handlebars = require('handlebars.runtime'),
          $body      = $("#body-wrapper");

    module.exports = (options) => {

        let courses = options.courses;
        for (let i in courses.course) {
            if (courses.course.hasOwnProperty(i)) courses.course[i].id = i;
        }

        let score = options.score;
        for (let i = 0; i < score.length; i++) {
            if (courses.course.hasOwnProperty(score[i].course_id)) {
                let course = courses.course[score[i].course_id];
                if (score[i].grade == 0) {
                    course.fail = true;
                } else if (score[i].grade > 0) {
                    course.pass = true;
                } else {
                    course.select = true;
                }
                if (course.hasOwnProperty('equivalent')) {
                    for (let j = 0; j < course.equivalent.length; j++) {
                        if (courses.course.hasOwnProperty(course.equivalent[j])) {
                            courses.course[course.equivalent[j]].equal = true;
                        }
                    }
                }
            }
        }

        let template = require('templates/common/ibox');
        Handlebars.registerPartial('graph', require('templates/gpa/graph'));

        // Main Table
        let config = {
            "id"   : "graph-table",
            "title": "Graphs",
            "body" : [{
                "template": "graph",
                "data"    : {courses: courses.course}
            }]
        };
        $body.append(template(config));


        // The modal of two graphs
        template = require('templates/common/modal');
        config   = {
            "id"    : "modal-graph",
            "header": {"title": "Analyze"},
            "body"  : [{
                "html": ""
            }],
            "footer": [{
                "button": {
                    "id"  : "modal-agree-btn-close", "text": "Close",
                    "type": "white", "close": true
                }
            }, {
                "button": {
                    "id"  : "modal-agree-btn-confirm", "text": "Confirm",
                    "type": "primary", "href": "<?php echo base_url('GPA/terms?confirm=1');?>"
                }
            }]
        };
        $body.append(template(config));


        $(".footable").footable();

        $(".footable").find(".pagination").on('click', () => {
            window.location.hash = '';
            window.location.hash = 'body-wrapper';
        });


        // Initialize

        const score_to_index_list = {
            '43': 0, '40': 1, '37': 2, '33': 3, '30': 4, '27': 5,
            '23': 6, '20': 7, '17': 8, '10': 9, '0': 10
        };
        const grade_list          = ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D', 'F'];
        const color_list          = [
            '#7AA7E1', '#5ADED8', '#5CCFA5', '#39E13B', '#77E17A', '#7FE17C',
            '#BBE162', '#DEB367', '#E16158', '#E1487A', '#DE2027'];

        $("#graph-table").find(".tr-course").on('click', function (e) {
            $("#modal-graph").modal();
            const id = $(this).children("[type=id]").data('id');
            $("#modal-graph").find(".modal-title").html('Analyze - ' + id);
            $.ajax({
                url     : window.ROOT_DIR + '/GPA/graph_score',
                type    : 'GET',
                dataType: 'json',
                data    : {id: id},
                success : (data) => {
                    generate_graph(data, $("#modal-graph").find(".modal-body"));
                },
                error   : (data) => {
                    window.console.log('fail', data);
                }
            });
        });

        const generate_graph = (score_list, $target) => {
            //$target.data('graph', true);

            let html;
            if (score_list.length === 0) {
                html = ['<div class="row collapsed">',
                    'Sorry, we don\'t have data for this course.',
                    '</div>'].join('');
            }
            else {
                html = ['<div class="row collapsed">',
                    '<div class="graph-line-wrapper col-xs-12 text-center">',
                    '<strong>Line Graph</strong><br>',
                    '<canvas class="graph-line"></canvas>',
                    '</div>',
                    '<div class="graph-pie-wrapper col-xs-offset-2 col-xs-8 text-center">',
                    '<strong>Pie Graph</strong><br>',
                    '<canvas class="graph-pie"></canvas>',
                    '</div>',
                    '</div>'].join('');
            }
            $target.html('<div>' + html + '</div>');
            const $graph = $target;

            if (score_list.length > 0) {
                let display = {
                    grade: [], data: [], color: [], data_all: []
                };
                for (let i = 0; i < grade_list.length; i++) {
                    display.data_all.push(0);
                }
                for (let i = 0; i < score_list.length; i++) {
                    if (score_to_index_list.hasOwnProperty(score_list[i].grade)) {
                        const index = score_to_index_list[score_list[i].grade];
                        display.grade.push(grade_list[index]);
                        display.data.push(score_list[i]['count(grade)']);
                        display.data_all[index] = score_list[i]['count(grade)'];
                        display.color.push(color_list[index]);
                    }
                }

                const lineData    = {
                    labels  : grade_list,
                    datasets: [
                        {
                            label               : "Number",
                            backgroundColor     : 'rgba(26,179,148,0.5)',
                            borderColor         : "rgba(26,179,148,0.7)",
                            pointBackgroundColor: "rgba(26,179,148,1)",
                            pointBorderColor    : "#fff",
                            data                : display.data_all
                        }
                    ]
                };
                const max         = Math.ceil(Math.max.apply(null, display.data) / 5) * 5;
                const lineOptions = {
                    responsive: true,
                    legend    : {display: false},
                    scales    : {
                        yAxes: [{
                            ticks: {
                                max     : max,
                                min     : 0,
                                stepSize: max / 5
                            }
                        }]
                    }
                };
                let graphElement  = $graph.find(".graph-line").first();
                let ctx           = graphElement[0].getContext("2d");
                new Chart(ctx, {type: 'line', data: lineData, options: lineOptions});

                const doughnutData    = {
                    labels  : display.grade,
                    datasets: [{
                        data           : display.data,
                        backgroundColor: display.color
                    }]
                };
                const doughnutOptions = {
                    responsive: true
                };
                graphElement          = $graph.find(".graph-pie").first();
                ctx                   = graphElement[0].getContext("2d");
                new Chart(ctx, {type: 'doughnut', data: doughnutData, options: doughnutOptions});
            }
            $graph.find(".row").collapse();
        }

    }

});