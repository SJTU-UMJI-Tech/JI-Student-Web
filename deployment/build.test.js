/**
 * Created by sunyi on 2017/2/1.
 */
const builder = require('./requirejs-builder');
let test      = builder.initBuilder({
    node_modules : 'node_modules',
    bower_modules: 'bower_modules',
});

test.addAppDir('js/ji', 'js-dist/ji', 'ji')
    .addNode('jquery', 'jquery/dist/jquery')
    .addNode('bootstrap', 'bootstrap/dist/js/bootstrap', ['jquery'])
    .addNode('metisMenu', 'metismenu/dist/metisMenu', ['jquery'])
    .addNode('slimscroll', 'jquery-slimscroll/jquery.slimscroll', ['jquery'])
    .addNode('pace', 'pace-progress/pace')
    .addNode('jquery-ui', 'jquery-ui-dist/jquery-ui')
    .addNode('gritter', 'gritter/js/jquery.gritter')
    .addNode('toastr', 'toastr/build/toastr')
    .addNode('chartjs', 'chart.js/dist/Chart')
    .addNode('handlebars', 'handlebars/dist/handlebars.amd')
    .addNode('handlebars.runtime', 'handlebars/dist/handlebars.runtime.amd')
    .addNode('select2', 'select2/dist/select2.full')
    .addNode('chosen', 'chosen-js/chosen.jquery')
    .addNode('cropper', 'cropper/dist/cropper')
    .addFile('footable', 'js/plugins/footable/footable.all')
    .addFile('inspinia', 'js/inspinia');


test.build('js/app.build');