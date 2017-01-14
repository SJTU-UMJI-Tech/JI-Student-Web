<!--Close the div in header.php-->
</div>

<!-- requireJS -->
<script src="<?php echo ROOT_DIR ?>/js/bower/requirejs/require.js"></script>

<script type="text/javascript">
    var rootUrl = "<?php echo ROOT_DIR; ?>/";
    function formCSSPath(path) {
        return 'css!' + rootUrl + path;
    }
    requirejs.config({
        baseUrl: rootUrl + 'js/',
        map: {
            '*': {
                'css': 'bower/require-css/css'
            }
        },
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
            'gitter': ['jquery',formCSSPath('/js/plugins/gritter/jquery.gritter.css')],
            'inspinia': ['bootstrap', 'metisMenu', 'slimscroll']
        },
        waitSeconds: 30
    });
    
    require(['pace'], function (pace) {
        pace.start();
    });
    
    require(['inspinia']);
    
    require([formCSSPath('css/style.css')]);

</script>