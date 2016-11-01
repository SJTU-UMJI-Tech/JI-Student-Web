<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_DIR; ?>/css/bootstrap-treeview-1.2.0.min.css">

<div class="container" id="ji-display">

</div>

<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>

<script type="text/javascript">
	require(['jquery', 'ji-display-settings'], function ($)
	{
		var data = JSON.parse('<?php echo isset($data) ? json_encode($data) : '{}';?>');
		window.console.log(data);
		var settings = $.fn.jiDisplaySettings({ROOT_DIR:'<?php echo ROOT_DIR; ?>'});
		eval('settings.<?php echo $type;?>(data);');
		//$.fn.jiDisplaySettings().scholarship();
	});
</script>
