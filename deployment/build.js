/**
 * Created by sunyi on 2017/2/1.
 */
const builder = require('./requirejs-builder');
builder.init({
    root_dir: process.argv.length > 2 ? process.argv[2] : '',
    require_css_dir: 'node_modules/require-css/css'
});

builder.addAppDir('js/ji', 'ji');
builder.addAppDir('js/templates', 'templates');

builder.addNode('jquery', 'jquery/dist/jquery');
//builder.addNode('bootstrap', 'bootstrap/dist/js/bootstrap', 'jquery');
builder.addNode('popper', 'popper.js/dist/umd/popper');
builder.addNode('bootstrap', 'bootstrap/dist/js/bootstrap', ['jquery', 'ji/common/popper-loader']);
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

//builder.addNode('jquery-ui/ui/widget', 'jquery-ui-dist/jquery-ui', 'jquery', 'jquery-ui-dist/jquery-ui');
builder.addFile('load-image', 'js/load-image/js/load-image');
builder.addFile('load-image-scale', 'js/load-image/js/load-image-scale');
builder.addFile('load-image-meta', 'js/load-image/js/load-image-meta');
builder.addFile('load-image-exif', 'js/load-image/js/load-image-exif');
builder.addNode('canvas-to-blob', 'blueimp-canvas-to-blob/js/canvas-to-blob');
builder.addNode('blueimp-tmpl', 'blueimp-tmpl/js/tmpl');
builder.addFile('jquery.fileupload', 'js/file-upload/js/jquery.fileupload', 'jquery-ui', 'js/file-upload/css/jquery.fileupload');
builder.addFile('jquery.fileupload-process', 'js/file-upload/js/jquery.fileupload-process', 'jquery.fileupload');
builder.addFile('jquery.fileupload-audio', 'js/file-upload/js/jquery.fileupload-audio', 'jquery.fileupload-process');
builder.addFile('jquery.fileupload-image', 'js/file-upload/js/jquery.fileupload-image', 'jquery.fileupload-process');
builder.addFile('jquery.fileupload-video', 'js/file-upload/js/jquery.fileupload-video', 'jquery.fileupload-process');
builder.addFile('jquery.fileupload-validate', 'js/file-upload/js/jquery.fileupload-validate', 'jquery.fileupload-process');
builder.addFile('jquery.fileupload-ui', 'js/file-upload/js/jquery.fileupload-ui', '', 'js/file-upload/css/jquery.fileupload-ui');

//builder.addAppDir('node_modules/blueimp-file-upload/js', 'blueimp-file-upload');

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

builder.addNode('xterm', 'xterm/dist/xterm', '', 'xterm/dist/xterm');
builder.addNode('xterm/fullscreen/fullscreen', 'xterm/dist/addons/fullscreen/fullscreen', 'xterm', 'xterm/dist/addons/fullscreen/fullscreen');
builder.addNode('xterm/fit/fit', 'xterm/dist/addons/fit/fit', 'xterm');


builder.addBower('footable', 'footable/compiled/footable', 'jquery', 'footable/compiled/footable.bootstrap');
builder.addBower('qrcodejs', 'qrcode.js/qrcode');

builder.addFile('inspinia', 'js/ji/inspinia', ['bootstrap', 'metisMenu', 'slimscroll']);

builder.build({
    root_dir: '',
    filePath: 'js/app.development',
    environment: 'development'
});

builder.build({
    root_dir: '',
    filePath: 'js/app.testing',
    environment: 'production'
});

builder.build({
    root_dir: '/student',
    filePath: 'js/app.production',
    environment: 'production'
});
