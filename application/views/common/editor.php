<?php include 'header.php'; ?>

<link rel="stylesheet" href="/vendors/editor.md-1.5.0/css/editormd.min.css"/>
<link rel="stylesheet" href="/vendors/editor.md-1.5.0/lib/codemirror/codemirror.min.css"/>
<link rel="stylesheet" href="/css/bootstrap-datetimepicker-2.3.8.min.css"/>
<link rel="stylesheet" href="/css/jquery.fileupload.css"/>
<link rel="stylesheet" href="/css/jquery.fileupload-ui.css"/>

<div class="container" id="ji-editor">
	
	<div class="card">
		<div class="card-header">
			<?php echo $option['title']; ?>
		</div>
		<div class="card-block">
			<?php foreach ($option['item'] as $item): ?>
				<div class="form-group">
					<label><?php echo $item['name']; ?></label>
					<?php if ($item['type'] == 'text'): ?>
						<input class="form-control" type="text" placeholder="<?php echo $item['name']; ?>"
						       name="<?php echo $item['name']; ?>">
					<?php elseif ($item['type'] == 'textarea'): ?>
						<textarea class="form-control" type="text" placeholder="<?php echo $item['name']; ?>"
						          rows="5" name="<?php echo $item['name']; ?>" style="resize: none"></textarea>
					<?php elseif ($item['type'] == 'editor'): ?>
						<div class="editormd" id="editor-md-<?php echo $item['name']; ?>"
						     name="<?php echo $item['name']; ?>"></div>
					<?php elseif ($item['type'] == 'dropdown'): ?>
						<div class="input-group-btn ji-dropdown">
							<button type="button" class="btn btn-secondary dropdown-toggle"
							        data-toggle="dropdown" aria-haspopup="true"
							        data-text="<?php echo isset($item['default']) ? $item['default'] : ''; ?>"
							        aria-expanded="false" name="<?php echo $item['name']; ?>"></button>
							<div class="dropdown-menu">
								<?php foreach ($item['text'] as $key => $value): ?>
									<a class="dropdown-item" href="javascript:void(0);"
									   data-text="<?php echo $key; ?>"><?php echo $value; ?></a>
								<?php endforeach; ?>
							</div>
						</div>
					<?php elseif ($item['type'] == 'date'): ?>
						<div class="input-append date" id="datetimepicker"
						     data-date-format="dd-mm-yyyy">
							<div class="input-group">
								<div class="input-group-addon">
									<span class="add-on"><i class="icon-th fa fa-th-list"></i></span>
								</div>
								<input class="form-control" type="text" onfocus=this.blur()>
								<div class="input-group-addon">
									<span class="add-on"><i class="icon-remove fa fa-times"></i></span>
								</div>
							</div>
						</div>
					<?php elseif ($item['type'] == 'file'): ?>
						<form id="fileupload" action="/upload" method="POST"
						      enctype="multipart/form-data">
							<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
							<div class="row fileupload-buttonbar">
								<div class="col-lg-7">
									<!-- The fileinput-button span is used to style the file input field as button -->
									<button class="btn btn-outline-success fileinput-button">
										<i class="glyphicon glyphicon-plus"></i>
										<span>Add files...</span>
										<input type="file" name="files[]" multiple>
									</button>
									<button type="submit" class="btn btn-outline-primary start">
										<i class="glyphicon glyphicon-upload"></i>
										<span>Start upload</span>
									</button>
									<button type="reset" class="btn btn-outline-warning cancel">
										<i class="glyphicon glyphicon-ban-circle"></i>
										<span>Cancel upload</span>
									</button>
									<button type="button" class="btn btn-outline-danger delete">
										<i class="glyphicon glyphicon-trash"></i>
										<span>Delete</span>
									</button>
									<input type="checkbox" class="toggle" style="display: none">
									<!-- The global file processing state -->
									<span class="fileupload-process"></span>
								</div>
								<!-- The global progress state -->
								<div class="col-lg-5 fileupload-progress fade">
									<!-- The global progress bar -->
									<div class="progress progress-striped active" role="progressbar" aria-valuemin="0"
									     aria-valuemax="100">
										<div class="progress-bar progress-bar-success" style="width:0%;"></div>
									</div>
									<!-- The extended global progress state -->
									<div class="progress-extended">&nbsp;</div>
								</div>
							</div>
							<!-- The table listing the files available for upload/download -->
							<table role="presentation" class="table table-striped">
								<tbody class="files"></tbody>
							</table>
						</form>
						
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
			<div class="card-text">
				<button class="btn btn-outline-success btn-submit">Submit</button>
			</div>
		</div>
		<div class="card-footer text-xs-center">
			<div class="autosave">Changes will be autosaved</div>
		</div>
	</div>
</div>

<?php include 'footer.php'; ?>

<script type="text/javascript">
	require(['jquery', 'ji-editor', 'bootstrapDatetimepicker', 'jquery.fileupload-ui'], function ($)
	{
		var option = '<?php echo json_encode($option);?>';
		var editor = $("#ji-editor").jiEditor(JSON.parse(option));
		/*editor.unserialize({
		 Title: 'Test title',
		 Abstract: 'Test Abstract',
		 Content: '## Test content'
		 });*/
		editor.unserialize(editor.loadCookie());
		editor.autosave(1000);
		window.console.log(editor.getCookieName());
		$("#datetimepicker").datetimepicker({
			format: "yyyy-mm-dd",
			minView: 'month',
			autoclose: true,
			todayBtn: true,
			pickerPosition: "bottom-right",
			todayHighlight: true,
			keyboardNavigation: true,
			fontAwesome: true
		});
		$('#fileupload').fileupload({
			dataType: 'json',
			url: '/upload/',
		});
	});
</script>

<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>

<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>