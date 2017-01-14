<?php include VIEW_DIR . 'common/header.php'; ?>

<div id="body-wrapper" class="wrapper wrapper-content animated fadeInRight">


</div>


<div id="scoreboard">

</div>


<?php include VIEW_DIR . 'common/scripts.php'; ?>

<?php include VIEW_DIR . 'templates/ibox.hbs'; ?>

<?php include 'scoreboard.hbs'; ?>


<script type="text/javascript">
    
    require(['jquery', 'handlebars', 'footable'], function ($, Handlebars) {
        $(document).ready(function () {
            var source = $("#ji-ibox-template").html();
            var template = Handlebars.compile(source);
            Handlebars.registerPartial('scoreboard', $("#scoreboard-template").html());
            var config = {
                "id": "scoreboard",
                "body": {
                    "template": "scoreboard",
                    "data": {
                        "rows": [
                            {"No": 1},
                            {"No": 2},
                            {"No": 3},
                            {"No": 4}
                        ]
                    }
                }
            };
            $("#body-wrapper").append(template(config));
            
            $(".footable").footable();
            
        });
    });

</script>


<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>


