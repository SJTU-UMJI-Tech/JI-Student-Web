/**
 * Created by sunyi on 2017/2/1.
 */
const builder = require('./requirejs-builder');
builder.init({
    root_dir       : process.argv.length > 2 ? process.argv[2] : '',
    require_css_dir: 'node_modules/require-css/css'
});

builder.addAppDir('js/ji', 'ji');
builder.addAppDir('js/templates', 'templates');

builder.addNode('jquery', 'jquery/dist/jquery');
//builder.addNode('bootstrap', 'bootstrap/dist/js/bootstrap', 'jquery');
builder.addNode('Tether', 'tether/dist/js/tether');
builder.addNode('bootstrap', 'bootstrap/dist/js/bootstrap', ['jquery', 'Tether']);
builder.addNode('metisMenu', 'metismenu/dist/metisMenu', 'jquery');
builder.addNode('slimscroll', 'jquery-slimscroll/jquery.slimscroll', 'jquery');
builder.addNode('pace', 'pace-progress/pace');
builder.addNode('jquery-ui', 'jquery-ui-dist/jquery-ui', '', 'jquery-ui-dist/jquery-ui');
builder.addNode('gritter', 'gritter/js/jquery.gritter', 'jquery', 'gritter/css/jquery.gritter');
builder.addNode('toastr', 'toastr/toastr');
builder.addNode('chartjs', 'chart.js/dist/Chart');
builder.addNode('handlebars', 'handlebars/dist/handlebars.amd');
builder.addNode('handlebars.runtime', 'handlebars/dist/handlebars.runtime.amd');
builder.addNode('select2', 'select2/dist/js/select2.full', '', 'select2/dist/css/select2');
builder.addNode('chosen', 'chosen-js/chosen.jquery', 'jquery', 'chosen-js/chosen');
builder.addNode('cropper', 'cropper/dist/cropper', '', 'cropper/dist/cropper');
builder.addNode('marked', 'marked/lib/marked');
builder.addNode('flatpickr', 'flatpickr/dist/flatpickr', '', 'flatpickr/dist/flatpickr');
builder.addNode('touchspin', 'bootstrap-touchspin/dist/jquery.bootstrap-touchspin', 'bootstrap', 'bootstrap-touchspin/dist/jquery.bootstrap-touchspin');

builder.addNode('editormd', 'editor.md/editormd.amd', '', ['editor.md/css/editormd', 'editor.md/lib/codemirror/addon/fold/foldgutter']);
builder.addNode('prettify', 'google-code-prettify/bin/prettify.min', '', 'google-code-prettify/bin/prettify.min');
builder.addNode('katex', 'katex/dist/katex', '', 'katex/dist/katex');
builder.addNode('raphael', 'raphael/raphael');
builder.addNode('underscore', 'underscore/underscore');
builder.addNode('flowchart', 'flowchart.js/release/flowchart');
builder.addNode('jqueryflowchart', 'jquery.flowchart/jquery.flowchart', 'jquery-ui', 'jquery.flowchart/jquery.flowchart');
builder.addNode('sequenceDiagram', 'js-sequence-diagrams/fucknpm/sequence-diagram', 'Raphael');
builder.addNode('Raphael', 'js-sequence-diagrams/node_modules/raphael/raphael');
builder.addNode('codemirror/lib/codemirror', 'editor.md/lib/codemirror/lib/codemirror', '', 'editor.md/lib/codemirror/lib/codemirror');
builder.addAppDir('node_modules/editor.md/lib/codemirror/mode', 'codemirror/mode');
builder.addAppDir('node_modules/editor.md/lib/codemirror/addon', 'codemirror/addon');
builder.addAppDir('node_modules/editor.md/plugins', 'editor.md/plugins');
builder.addNode('editor.md/languages/en', 'editor.md/languages/en');
builder.addFonts('node_modules/editor.md/fonts');

builder.addBower('footable', 'footable/compiled/footable', 'jquery', 'footable/compiled/footable.bootstrap');
builder.addBower('qrcodejs','qrcode.js/qrcode');

builder.addFile('inspinia', 'js/inspinia', ['bootstrap', 'metisMenu', 'slimscroll']);

builder.build({
    root_dir   : '',
    filePath   : 'js/app.development',
    environment: 'development'
});

builder.build({
    root_dir   : '',
    filePath   : 'js/app.test',
    environment: 'production'
});

builder.build({
    root_dir   : '/student',
    filePath   : 'js/app.production',
    environment: 'production'
});
