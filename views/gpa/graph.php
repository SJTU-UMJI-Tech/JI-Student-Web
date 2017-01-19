<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<div id="body-wrapper" class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div id="graph-line-wrapper" class="col-lg-6">
        
        </div>
        <div id="graph-pie-wrapper" class="col-lg-6">
        
        </div>
    </div>
</div>

<?php include dirname(dirname(__FILE__)) . '/common/scripts.php'; ?>

<?php include dirname(dirname(__FILE__)) . '/common/templates/ibox.hbs'; ?>
<?php include 'templates/graph.hbs' ?>

<script type="text/javascript">

    require(['jquery', 'handlebars', 'chartjs', 'footable', 'pace'], function ($, Handlebars, Chart) {
        $(document).ready(function () {
            var i, j;

            var courses = {"course": true};
            <?php /** @var $courses string */?>
            courses = <?php echo $courses;?>;
            for (i in courses.course) {
                if (courses.course.hasOwnProperty(i)) courses.course[i].id = i;
            }
            
            <?php /** @var $score string */?>
            var score = [{course_id: true, grade: true}];
            score = <?php echo $score;?>;
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


            var source = $("#ji-ibox-template").html();
            var template = Handlebars.compile(source);
            Handlebars.registerPartial('graph', $("#graph-template").html());

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

                if ($target.data('graph')) {
                    $target.next().html('');
                }
                $target.data('graph', true);
                $target.after('<tr><td>1<td></tr>');
                console.log($graph.html());

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
                console.log(display.data_all);

                // Graph Line
                config = {
                    "id": "graph-line-ibox",
                    "title": "Line Graph - ",
                    "body": [{
                        "html": '<div><canvas id="graph-line" height="140"></canvas></div>'
                    }]
                };
                $("#graph-line-wrapper").html(template(config));

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


                var ctx = document.getElementById("graph-line").getContext("2d");
                new Chart(ctx, {type: 'line', data: lineData, options: lineOptions});

                // Graph Pie
                config = {
                    "id": "graph-pie-ibox",
                    "title": "Pie Graph - ",
                    "body": [{
                        "html": '<div><canvas id="graph-pie" height="140"></canvas></div>'
                    }]
                };
                $("#graph-pie-wrapper").html(template(config));


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
                var ctx4 = document.getElementById("graph-pie").getContext("2d");
                new Chart(ctx4, {type: 'doughnut', data: doughnutData, options: doughnutOptions});
            }


        });
    });

</script>


<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>


