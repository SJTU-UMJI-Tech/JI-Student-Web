<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<div id="body-wrapper" class="wrapper wrapper-content animated fadeInRight">


</div>

<?php include dirname(dirname(__FILE__)) . '/common/scripts.php'; ?>

<?php include dirname(dirname(__FILE__)) . '/common/templates/ibox.hbs'; ?>

<?php include 'templates/list.hbs'; ?>
<?php include 'templates/list-item.hbs'; ?>

<script type="text/javascript">
    
    window.ROOT_DIR = '';
    
    require(['jquery', 'ji-list-view'], function ($) {
        $(document).ready(function () {
            
            $("#body-wrapper").jiListView({
                title: "Scholarships",
                templates: {
                    "ibox": $("#ji-ibox-template").html(),
                    "list": $("#list-template").html(),
                    "item": $("#list-item-template").html()
                }
            });
        });
    });

</script>


<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>


