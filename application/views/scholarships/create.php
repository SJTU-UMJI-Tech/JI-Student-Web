<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<link rel="stylesheet" href="/vendors/editor.md-1.5.0/css/editormd.min.css"/>
<link rel="stylesheet" href="/vendors/editor.md-1.5.0/lib/codemirror/codemirror.min.css"/>


<div class="container" id="ji-editor">
	
	<div class="card">
		<div class="card-header">
			<?php echo $options['title']; ?>
		</div>
		<div class="card-block">
			<?php foreach ($options['item'] as $item): ?>
				<div class="form-group">
					<label><?php echo $item['name']; ?></label>
					<?php if ($item['type'] == 'text'): ?>
						<input class="form-control" type="text" placeholder="<?php echo $item['name']; ?>">
					<?php elseif ($item['type'] == 'textarea'): ?>
						<textarea class="form-control" type="text" placeholder="<?php echo $item['name']; ?>"
						          rows="5" style="resize: none"></textarea>
					<?php elseif ($item['type'] == 'editor'): ?>
						<div class="editormd" id="editor-md-<?php echo $item['name']; ?>"></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>

<script type="text/javascript">
	require(['jquery', 'ji-editor'], function ($)
	{
		var options = '<?php echo json_encode($options);?>';
		var editor = $("#ji-editor").jiEditor(JSON.parse(options));
	});
</script>
