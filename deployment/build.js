/**
 * Created by sunyi on 2017/2/1.
 */
const builder = require('./requirejs-builder');
builder.init({
    root_dir       : '',
    require_css_dir: 'node_modules/require-css/css'
});

builder.addAppDir('js/ji', 'ji');
builder.addAppDir('js/templates', 'templates');
builder.addNode('jquery', 'jquery/dist/jquery');
builder.addNode('bootstrap', 'bootstrap/dist/js/bootstrap', 'jquery');
builder.addNode('metisMenu', 'metismenu/dist/metisMenu', 'jquery');
builder.addNode('slimscroll', 'jquery-slimscroll/jquery.slimscroll', 'jquery');
builder.addNode('pace', 'pace-progress/pace');
builder.addNode('jquery-ui', 'jquery-ui-dist/jquery-ui');
builder.addNode('gritter', 'gritter/js/jquery.gritter', 'jquery', 'gritter/css/jquery.gritter');
builder.addNode('toastr', 'toastr/toastr');
builder.addNode('chartjs', 'chart.js/dist/Chart');
builder.addNode('handlebars', 'handlebars/dist/handlebars.amd');
builder.addNode('handlebars.runtime', 'handlebars/dist/handlebars.runtime.amd');
builder.addNode('select2', 'select2/dist/js/select2.full', '', 'select2/dist/css/select2');
builder.addNode('chosen', 'chosen-js/chosen.jquery', 'jquery', 'chosen-js/chosen');
builder.addNode('cropper', 'cropper/dist/cropper', '', 'cropper/dist/cropper');
builder.addBower('footable', 'footable/compiled/footable', 'jquery', 'footable/compiled/footable.bootstrap');
//builder.addFile('footable', 'js/plugins/footable/footable.all.min', 'jquery', 'css/plugins/footable/footable.core');
builder.addFile('inspinia', 'js/inspinia', ['bootstrap', 'metisMenu', 'slimscroll']);


builder.build('js/app.build');
