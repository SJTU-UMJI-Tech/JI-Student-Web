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
					<label><?php echo $item['label']; ?></label>
					<?php if ($item['type'] == 'text'): ?>
						<input class="form-control" type="text" placeholder="<?php echo $item['label']; ?>"
						       name="<?php echo $item['name']; ?>">
					<?php elseif ($item['type'] == 'textarea'): ?>
						<textarea class="form-control" type="text" placeholder="<?php echo $item['label']; ?>"
						          rows="8" name="<?php echo $item['name']; ?>" style="resize: none"></textarea>
					<?php elseif ($item['type'] == 'markdown'): ?>
						<div class="editormd ji-markdown" id="editor-md-<?php echo $item['name']; ?>"
						     name="<?php echo $item['name']; ?>"></div>
					<?php elseif ($item['type'] == 'dropdown'): ?>
						<div class="input-group-btn ji-dropdown">
							<button type="button" class="btn btn-secondary dropdown-toggle"
							        data-toggle="dropdown" aria-haspopup="true"
							        data-text="<?php echo isset($item['default']) ? $item['default'] : ''; ?>"
							        aria-expanded="false" name="<?php echo $item['name']; ?>"></button>
							<div class="dropdown-menu">
								<?php foreach ($item['option'] as $key => $value): ?>
									<a class="dropdown-item" href="javascript:void(0);"
									   data-text="<?php echo $key; ?>"><?php echo $value; ?></a>
								<?php endforeach; ?>
							</div>
						</div>
					<?php elseif ($item['type'] == 'date' || $item['type'] == 'time'): ?>
						<div class="input-append date ji-date" type="<?php echo $item['type']; ?>">
							<div class="input-group">
								<div class="input-group-addon">
									<span class="add-on"><i class="icon-th fa fa-th-list"></i></span>
								</div>
								<input class="form-control" type="text" onfocus="this.blur()"
								       name="<?php echo $item['name']; ?>"
								       placeholder="<?php echo $item['label']; ?>">
								<div class="input-group-addon">
									<span class="add-on"><i class="icon-remove fa fa-times"></i></span>
								</div>
							</div>
						</div>
					<?php elseif ($item['type'] == 'file'): ?>
						<form class="ji-file" action="" method="POST"
						      enctype="multipart/form-data">
							<!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
							<div class="row fileupload-buttonbar">
								<div class="col-xs-12">
									<!-- The fileinput-button span is used to style the file input field as button -->
									<button class="btn btn-outline-success fileinput-button">
										<i class="fa fa-plus" aria-hidden="true"></i>
										<span>Add files...</span>
										<input type="file" name="files[]" multiple>
									</button>
									<button type="submit" class="btn btn-outline-primary start">
										<i class="fa fa-upload" aria-hidden="true"></i>
										<span>Start upload</span>
									</button>
									<button type="reset" class="btn btn-outline-warning cancel">
										<i class="fa fa-ban" aria-hidden="true"></i>
										<span>Cancel upload</span>
									</button>
									<button type="button" class="btn btn-outline-danger delete">
										<i class="fa fa-trash" aria-hidden="true"></i>
										<span>Delete</span>
									</button>
									<input type="checkbox" class="toggle">
									<span>Select All</span>
									<!-- The global file processing state -->
									<span class="fileupload-process"></span>
								</div>
								<!-- The global progress state -->
								<div class="col-xs-12 fileupload-progress fade">
									<!-- The global progress bar -->
									<progress class="progress progress-striped progress-success active"
									          role="progressbar" max="100" value="0"
									<!--aria-valuemin="0" aria-valuemax="100"-->>
									<div class="progress-bar progress-bar-success" style="width:0%;"></div>
									</progress>
									<!-- The extended global progress state -->
									<div class="progress-extended">&nbsp;</div>
								</div>
							</div>
							<!-- The table listing the files available for upload/download -->
							<table role="presentation" class="table table-striped">
								<tbody class="files" name="<?php echo $item['name']; ?>"></tbody>
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
		/*$(".ji-date").datetimepicker({
		 format: "yyyy-mm-dd",
		 minView: 'month',
		 autoclose: true,
		 todayBtn: true,
		 pickerPosition: "bottom-right",
		 todayHighlight: true,
		 keyboardNavigation: true,
		 fontAwesome: true
		 });*/
		
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
            <progress class="progress progress-striped progress-success active" role="progressbar" max="100" value="0"><div class="progress-bar progress-success" style="width:0%;"></div></progress>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-outline-primary start" disabled>
                    <i class="fa fa-upload" aria-hidden="true"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-outline-warning cancel">
                    <i class="fa fa-ban" aria-hidden="true"></i>
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
                <button class="btn btn-outline-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="fa fa-trash" aria-hidden="true"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-outline-warning cancel">
                    <i class="fa fa-ban" aria-hidden="true"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}

</script>