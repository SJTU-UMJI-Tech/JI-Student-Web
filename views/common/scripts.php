<!--Close the div in header.php-->
</div>

<!-- requireJS -->
<script src="<?php echo ROOT_DIR ?>/js/bower/requirejs/require.js"></script>

<script type="text/javascript">
    
    requirejs.config({
        baseUrl: "..<?php echo ROOT_DIR; ?>/js/",
        paths: {
            'jquery': 'jquery-3.1.1.min',
            'bootstrap': 'bootstrap.min',
            'metisMenu': 'plugins/metisMenu/jquery.metisMenu',
            'slimscroll': 'plugins/slimscroll/jquery.slimscroll.min',
            'pace': 'plugins/pace/pace.min',
            'jquery-ui': 'plugins/jquery-ui/jquery-ui.min',
            'gitter': 'plugins/gritter/jquery.gritter.min',
            'toastr': 'plugins/toastr/toastr.min',
            
            
            'inspinia': 'inspinia'
        },
        
        shim: {
            'bootstrap': ['jquery'],
            'metisMenu': ['jquery'],
            'slimscroll': ['jquery'],
            'gitter': ['jquery'],
            'inspinia': ['bootstrap', 'metisMenu', 'slimscroll']
            //bootstrapHoverDropdown: ['jquery', 'bootstrap'],
            //fullCalendar: ['moment'],
            //flowchart: ['raphael'],
            //sequenceDiagram: ['raphael', 'underscore'],
            //jqueryflowchart: ['flowchart'],
            //'jquery.md5': ['jquery'],
            //'xlsx': {deps: ['jszip'], exports: 'XLSX'},
            //bootstrapDatetimepicker: ['jquery', 'bootstrap']
        },
        waitSeconds: 30
    });
    
    require(['pace'], function (pace) {
        pace.start();
    });
    
    require(['inspinia']);

</script>


<!-- Mainly scripts -->
<!--<script src="js/jquery-3.1.1.min.js"></script>-->
<!--<script src="js/bootstrap.min.js"></script>-->
<!--<script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>-->
<!--<script src="js/plugins/slimscroll/jquery.slimscroll.min.js"></script>-->

<!-- Flot -->
<!--<script src="js/plugins/flot/jquery.flot.js"></script>-->
<!--<script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>-->
<!--<script src="js/plugins/flot/jquery.flot.spline.js"></script>-->
<!--<script src="js/plugins/flot/jquery.flot.resize.js"></script>-->
<!--<script src="js/plugins/flot/jquery.flot.pie.js"></script>-->

<!-- Peity -->
<!--<script src="js/plugins/peity/jquery.peity.min.js"></script>-->
<!--<script src="js/demo/peity-demo.js"></script>-->

<!-- Custom and plugin javascript -->
<!--<script src="js/inspinia.js"></script>-->
<!--<script src="js/plugins/pace/pace.min.js"></script>-->

<!--<!-- jQuery UI -->-->
<!--<script src="js/plugins/jquery-ui/jquery-ui.min.js"></script>-->
<!---->
<!--<!-- GITTER -->-->
<!--<script src="js/plugins/gritter/jquery.gritter.min.js"></script>-->
<!---->
<!--<!-- Sparkline -->-->
<!--<script src="js/plugins/sparkline/jquery.sparkline.min.js"></script>-->
<!---->
<!--<!-- Sparkline demo data  -->-->
<!--<script src="js/demo/sparkline-demo.js"></script>-->
<!---->
<!--<!-- ChartJS-->-->
<!--<script src="js/plugins/chartJs/Chart.min.js"></script>-->

<!-- Toastr -->
<!--<script src="js/plugins/toastr/toastr.min.js"></script>-->