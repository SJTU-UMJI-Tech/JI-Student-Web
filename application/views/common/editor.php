<?php include 'header.php'; ?>

<link rel="stylesheet" href="/vendors/editor.md-1.5.0/css/editormd.min.css"/>
<link rel="stylesheet" href="/vendors/editor.md-1.5.0/lib/codemirror/codemirror.min.css"/>
<link rel="stylesheet" href="/css/lib/bootstrap-datetimepicker-2.3.8.min.css"/>

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
	require(['jquery', 'ji-editor', 'bootstrapDatetimepicker'], function ($)
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
		
	});
</script>
