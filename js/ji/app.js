/**
 * Created by liu on 17-1-30.
 */
function initJIRequire(rootUrl) {
    
    'use strict';
    
    var MIN_SUFFIX = '.min';
    
    function formCSSPath(path) {
        return 'css!' + rootUrl + path;
    }appConfig
    
    function nodeCSS(path, no_min) {
        return 'css!' + rootUrl + 'node_modules/' + path + (no_min ? '' : MIN_SUFFIX) + '.css';
    }
    
    function node(path, no_min) {
        return 'node_modules/' + path + (no_min ? '' : MIN_SUFFIX);
    }
    
    requirejs.config({
        baseUrl: rootUrl,// + 'js/',
        map    : {
            '*': {
                'css': node('require-css/css')
            }
        },
        paths  : {
            'jquery'            : node('jquery/dist/jquery'),
            'bootstrap'         : node('bootstrap/dist/js/bootstrap'),
            'metisMenu'         : node('metismenu/dist/metisMenu'),
            'slimscroll'        : node('jquery-slimscroll/jquery.slimscroll'),
            'pace'              : node('pace-progress/pace'),
            'jquery-ui'         : node('jquery-ui-dist/jquery-ui'),
            'gritter'           : node('gritter/js/jquery.gritter'),
            'toastr'            : node('toastr/build/toastr'),
            'chartjs'           : node('chart.js/dist/Chart'),
            'footable'          : 'js/plugins/footable/footable.all.min',
            'handlebars'        : node('handlebars/dist/handlebars.amd'),
            'handlebars.runtime': node('handlebars/dist/handlebars.runtime.amd'),
            'select2'           : node('select2/dist/select2.full'),
            'chosen'            : node('chosen-js/chosen.jquery', true),
            'cropper'           : node('cropper/dist/cropper'),
            'inspinia'          : 'js/inspinia',
            
            
            'ji-list-view': 'ji/ji-list-view'
        },
        
        shim       : {
            'bootstrap' : ['jquery'],
            'metisMenu' : ['jquery'],
            'slimscroll': ['jquery'],
            'gritter'   : ['jquery', nodeCSS('gritter/css/jquery.gritter', true)],
            'footable'  : ['jquery', formCSSPath('css/plugins/footable/footable.core.css')],
            'select2'   : [nodeCSS('select2/dist/css/select2')],
            'chosen'    : ['jquery', nodeCSS('chosen-js/chosen', true)],
            'cropper'   : [nodeCSS('cropper/dist/cropper')],
            'inspinia'  : ['bootstrap', 'metisMenu', 'slimscroll']
        },
        waitSeconds: 30
    });
    
    require(['pace'], function (pace) {
        pace.start();
    });
    
    require(['inspinia']);
    
    //require([formCSSPath('css/style.css')]);
}

