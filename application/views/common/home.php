<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>

<link rel="stylesheet" type="text/css" href="/css/bootstrap-treeview-1.2.0.min.css">

<div class="container" id="ji-display">

</div>

<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>

<script type="text/javascript">
	require(['jquery', 'ji-display-settings'], function ($)
	{
		eval('$.fn.jiDisplaySettings().<?php echo $type;?>();');
		//$.fn.jiDisplaySettings().scholarship();
	});
</script>
