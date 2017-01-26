<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<div id="body-wrapper" class="wrapper wrapper-content animated fadeInRight">


</div>

<?php include dirname(dirname(__FILE__)) . '/common/scripts.php'; ?>

<?php include dirname(dirname(__FILE__)) . '/common/templates/ibox.hbs'; ?>

<?php include 'templates/list.hbs'; ?>
<?php include 'templates/list-item.hbs'; ?>

<script type="text/javascript">
    
    window.ROOT_DIR = '';
    
    require(['jquery', 'handlebars', 'footable'], function ($, Handlebars) {
        $(document).ready(function () {
            var source = $("#ji-ibox-template").html();
            var template = Handlebars.compile(source);
            Handlebars.registerPartial('list', $("#list-template").html());
            
            var config = {
                "id": "scholarships",
                "title": "Scholarships",
                "body": [{
                    "template": "list"
                }]
            };
            $("#body-wrapper").append(template(config));
            
            
            source = $("#list-item-template").html();
            template = Handlebars.compile(source);
            
            var $listBody = $("#scholarships").find("tbody");
            
            function addItem(data) {
                for (var i = 0; i < data.length; i++) {
                    $listBody.append(template(data));
                }
                $listBody.append('<tr><td colspan="5" class="text-center"><a class="btn-link btn-expand"><i class="fa fa-angle-double-down" aria-hidden="true"></i></a></td></tr>');
                $(".btn-expand").on('click', expand);
            }
            
            function expand() {
                
                $listBody.find(".btn-expand").parentsUntil("tr").remove();
                
                $.ajax({
                    type: 'GET',
                    url: window.ROOT_DIR+'/scholarships/ajax_search',
                    data: {
                        cmd: 'search',
                        key: 'all',
                        keywords: '',
                        order: 'Newest',
                        limit: 5,
                        offset: 0
                    },
                    dataType: 'json',
                    success: function (data)
                    {
                        addItem(data);
                    },
                    error: function ()
                    {
                        console.log('fail')
                    }
                });
    
                //addItem([{},{},{}]);
                
            }
            
            expand();
            
            //$(".footable").footable();
            
        });
    });

</script>


<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>


