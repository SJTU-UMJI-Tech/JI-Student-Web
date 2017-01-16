<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<div id="body-wrapper" class="wrapper wrapper-content animated fadeInRight">


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
            
            // Process the course list
            var courses = {};
            courses = <?php echo $courses;?>;
            var category = courses.category;
            for (i in category) {
                if (category.hasOwnProperty(i)) {
                    console.log(i, category[i])
                }
            }
            
            // Generate the Table Data shown in the page
            var table_data = [];
            var degree = courses.degree[major];
            if (!degree.hasOwnProperty('credit-dd') || !dd)degree['credit-dd'] = {};
            for (i in degree.credit) {
                if (degree.credit.hasOwnProperty(i)) {
                    if (degree['credit-dd'].hasOwnProperty(i)) {
                        //console.log(i);
                        degree.credit[i] = degree['credit-dd'][i];
                    }
                    if (degree.credit[i] > 0 && category.hasOwnProperty(i)) {
                        table_data.push({title: category[i]});
                    }
                }
            }
            console.log(table_data);
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


