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
                },
                processRow: function (row) {
                    var start = new Date(row.start_date).getTime();
                    var end = new Date(row.end_date).getTime();
                    var now = new Date().getTime();
                    var percent = Math.round((now - start) / (end - start) * 100.);
                    percent = Math.max(0, Math.min(100, percent));
                    if (percent === 100) row.time_after = true;
                    
                    row.time_percent = percent;

                    percent = Math.round(row.current_num / row.max_num * 100.);
                    percent = Math.max(0, Math.min(100, percent));
                    row.people_percent = percent;
                    if (percent === 100) row.people_full = true;

                    return row;
                }
            });
        });
    });

</script>


<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>


