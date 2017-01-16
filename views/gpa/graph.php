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


<script type="text/javascript">
    
    require(['jquery', 'handlebars', 'chartjs', 'pace'], function ($, Handlebars, Chart) {
        $(document).ready(function () {
            // Initialize
            var score_list = <?php echo $score_list;?>;
            
            /*const score_to_grade_list = {
             '43': 'A+', '40': 'A', '37': 'A-', '33': 'B+', '30': 'B', '27': "B-",
             '23': 'C+', '20': 'C', '17': 'C-', '10': 'D', '0': 'F'
             };*/
            const score_to_index_list = {
                '43': 0, '40': 1, '37': 2, '33': 3, '30': 4, '27': 5,
                '23': 6, '20': 7, '17': 8, '10': 9, '0': 10
            };
            const grade_list = ['A+', 'A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'C-', 'D', 'F'];
            const color_list = [
                '#7AA7E1', '#5ADED8', '#5CCFA5', '#39E13B', '#77E17A', '#7FE17C',
                '#BBE162', '#DEB367', '#E16158', '#E1487A', '#DE2027'];
            
            /*const grade_to_color_list = {
             'A+': '#7AA7E1', 'A': '#5ADED8', 'A-': '#5CCFA5', 'B+': '#39E13B', 'B': '#77E17A', 'B-': '#7FE17C',
             'C+': '#BBE162', 'C': '#DEB367', 'C-': '#E16158', 'D': '#E1487A', 'F': '#DE2027'
             };*/
            
            var display = {
                grade: [], data: [], color: [], data_all: []
            };
            for (var i = 0; i < grade_list.length; i++) {
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
            
            var source = $("#ji-ibox-template").html();
            var template = Handlebars.compile(source);
            
            
            // Graph Line
            var config = {
                "id": "graph-line-ibox",
                "title": "Line Graph - <?php echo strtoupper($course_id); ?>",
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
                "title": "Pie Graph - <?php echo strtoupper($course_id); ?>",
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
            
        });
    });

</script>


<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>


