<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<link rel="stylesheet" href="/vendors/editor.md-1.5.0/css/editormd.min.css"/>

<div class="container">
	
	<div class="card">
		<div class="card-header">
			New scholarships
		</div>
		<div class="card-block">
			<div class="form-group">
				<label>Title</label>
				<input class="form-control" type="text" placeholder="Title">
			</div>
			<div class="form-group">
				<label>Abstract</label>
				<textarea class="form-control" type="text" placeholder="Abstract" rows="5"
				          style="resize: none"></textarea>
			</div>
			<div class="form-group">
				<label>Content</label>
				<div id="editor-md">
				</div>
			</div>
		</div>
	</div>
</div>

<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>

<script type="text/javascript">
	
	var deps = [
		"editormd",
		"/vendors/editor.md-1.5.0/languages/en.js",
		"/vendors/editor.md-1.5.0/plugins/link-dialog/link-dialog.js",
		"/vendors/editor.md-1.5.0/plugins/reference-link-dialog/reference-link-dialog.js",
		"/vendors/editor.md-1.5.0/plugins/image-dialog/image-dialog.js",
		"/vendors/editor.md-1.5.0/plugins/code-block-dialog/code-block-dialog.js",
		"/vendors/editor.md-1.5.0/plugins/table-dialog/table-dialog.js",
		"/vendors/editor.md-1.5.0/plugins/emoji-dialog/emoji-dialog.js",
		"/vendors/editor.md-1.5.0/plugins/goto-line-dialog/goto-line-dialog.js",
		"/vendors/editor.md-1.5.0/plugins/help-dialog/help-dialog.js",
		"/vendors/editor.md-1.5.0/plugins/html-entities-dialog/html-entities-dialog.js",
		"/vendors/editor.md-1.5.0/plugins/preformatted-text-dialog/preformatted-text-dialog.js"
	];
	
	require(deps, function (editormd)
	{
		//console.log(editormd);
		testEditor = editormd("editor-md", {
			width: "100%",
			height: 640,
			syncScrolling: "single",
			path: "/vendors/editor.md-1.5.0/lib/",
			
		});
	});
</script>
