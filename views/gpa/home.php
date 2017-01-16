<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<div id="body-wrapper" class="wrapper wrapper-content animated fadeInRight">


</div>

<?php include dirname(dirname(__FILE__)) . '/common/scripts.php'; ?>

<?php include dirname(dirname(__FILE__)) . '/common/templates/ibox.hbs'; ?>

<?php include 'templates/scoreboard.hbs'; ?>

<script type="text/javascript">
    
    require(['jquery', 'handlebars', 'footable'], function ($, Handlebars) {
        $(document).ready(function () {
            var source = $("#ji-ibox-template").html();
            var template = Handlebars.compile(source);
            Handlebars.registerPartial('scoreboard', $("#scoreboard-template").html());
            
            var config = {
                "id": "scoreboard",
                "title": "UM-SJTU-JI GPA SCOREBOARD",
                "body": [{
                    "template": "scoreboard",
                    "data": {
                        "rows": <?php echo $scoreboard;?>
                    }
                }]
            };
            $("#body-wrapper").append(template(config));
            
            $(".footable").footable();
            
        });
    });

</script>


<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>


