/**
 * Created by liu on 17-1-30.
 */
function initJIRequire(rootUrl) {
    
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
            'chartjs': 'plugins/chartJs/Chart.min',
            'footable': 'plugins/footable/footable.all.min',
            'handlebars': 'bower/handlebars/handlebars.amd.min',
            'handlebars.runtime': 'bower/handlebars/handlebars.runtime.amd.min',
            'select2': 'plugins/select2/select2.full.min',
            'chosen': 'plugins/chosen/chosen.jquery',
            'cropper': 'bower/cropper/dist/cropper.min',
            'inspinia': 'inspinia',
            
            
            'ji-list-view': 'ji/ji-list-view'
        },
        
        shim: {
            'bootstrap': ['jquery'],
            'metisMenu': ['jquery'],
            'slimscroll': ['jquery'],
            'gitter': ['jquery', formCSSPath('js/plugins/gritter/jquery.gritter.css')],
            'footable': ['jquery', formCSSPath('css/plugins/footable/footable.core.css')],
            'select2': [formCSSPath('css/plugins/select2/select2.min.css')],
            'chosen': ['jquery', formCSSPath('css/plugins/chosen/bootstrap-chosen.css')],
            'cropper': [formCSSPath('js/bower/cropper/dist/cropper.min.css')],
            'inspinia': ['bootstrap', 'metisMenu', 'slimscroll']
        },
        waitSeconds: 30
    });
    
    require(['pace'], function (pace) {
        pace.start();
    });
    
    require(['inspinia']);
    
    //require([formCSSPath('css/style.css')]);
}

