<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<div id="body-wrapper" class="wrapper wrapper-content animated fadeInRight">


</div>

<?php include dirname(dirname(__FILE__)) . '/common/scripts.php'; ?>

<?php include dirname(dirname(__FILE__)) . '/common/templates/ibox.hbs'; ?>

<?php include 'templates/list.hbs'; ?>
<?php include 'templates/list-item.hbs'; ?>

<script type="text/javascript">
    
    window.ROOT_DIR = '';
    
    function base_url(str) {
        return window.ROOT_DIR + '/scholarships/' + str;
    }
    
    require(['jquery', 'ji-list-view'], function ($) {
        $(document).ready(function () {
            
            
            $("#body-wrapper").jiListView({
                title: "Scholarships",
                url: {
                    view: base_url('view'),
                    edit: base_url('edit'),
                    search: base_url('ajax_search')
                },
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
                    if (percent > 100) {
                        row.closed = true;
                    } else if (percent >= 0) {
                        row.active = true;
                    }
                    percent = Math.max(0, Math.min(100, percent));
                    if (percent === 100) row.time_after = true;
                    
                    row.time_percent = percent;
                    
                    percent = Math.round(row.current_num / row.max_num * 100.);
                    percent = Math.max(0, Math.min(100, percent));
                    row.people_percent = percent;
                    if (percent === 100) row.people_full = true;
                    
                    row.url_view = base_url('view');
                    row.url_edit = base_url('edit');
                    
                    return row;
                }
            });
        });
    });

</script>


<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>


