/**
 * Created by liu on 17-2-15.
 */
"use strict";
define([
    'require', 'exports', 'module',
    "editormd",
    "editor.md/languages/en",
    "editor.md/plugins/link-dialog/link-dialog",
    "editor.md/plugins/reference-link-dialog/reference-link-dialog",
    "editor.md/plugins/image-dialog/image-dialog",
    "editor.md/plugins/code-block-dialog/code-block-dialog",
    "editor.md/plugins/table-dialog/table-dialog",
    "editor.md/plugins/emoji-dialog/emoji-dialog",
    "editor.md/plugins/goto-line-dialog/goto-line-dialog",
    "editor.md/plugins/help-dialog/help-dialog",
    "editor.md/plugins/html-entities-dialog/html-entities-dialog",
    "editor.md/plugins/preformatted-text-dialog/preformatted-text-dialog"
], function (require, exports, module) {
    module.exports = require('editormd');
});
