<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<div id="body-wrapper" class="wrapper wrapper-content animated fadeInRight article">
    
    <div class="row">
        <div id="article-wrapper" class="col-lg-10 col-lg-offset-1">
        
        
        </div>
    </div>


</div>

<?php include dirname(dirname(__FILE__)) . '/common/scripts.php'; ?>

<!-- Import ji-ibox-template -->
<?php include dirname(dirname(__FILE__)) . '/common/templates/ibox.hbs'; ?>
<!-- Import ji-ibox-article-template -->
<?php include dirname(dirname(__FILE__)) . '/common/templates/ibox-article.hbs'; ?>
<!-- Import ji-modal-template -->
<?php include dirname(dirname(__FILE__)) . '/common/templates/modal.hbs'; ?>

<script type="text/javascript">
    
    require(['jquery', 'handlebars'], function ($, Handlebars) {
        $(document).ready(function () {
            var source = $("#ji-ibox-template").html();
            var template = Handlebars.compile(source);
            Handlebars.registerPartial('article', $("#ji-ibox-article-template").html());
            
            var config = {
                "id": "article-body",
                "body": [{
                    "template": "article",
                    "data": <?php echo $terms_body?>
                }, {
                    "center": true,
                    "html": '<button id="btn-agree" class="btn btn-primary" type="button" data-toggle="modal" data-target="#modal-agree"><i class="fa fa-check"></i>&nbsp;Agree</button>'
                }]
            };
            $("#article-wrapper").html(template(config));
            
            source = $("#ji-modal-template").html();
            template = Handlebars.compile(source);
            config = {
                "id": "modal-agree",
                "header": {"title": "Confirmation"},
                "body": [{
                    "html": "<h3><strong>I've read the terms of service and I agreed about all of the items.</strong></h3>"
                }],
                "footer": [{
                    "button": {
                        "id": "modal-agree-btn-close", "text": "Close",
                        "type": "white", "close": true
                    }
                }, {
                    "button": {
                        "id": "modal-agree-btn-confirm", "text": "Confirm",
                        "type": "primary", "href": "<?php echo base_url('GPA/terms?confirm=1');?>"
                    }
                }]
            };
            $("#article-wrapper").append(template(config));
        });
    });

</script>


<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>


