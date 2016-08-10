<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>
<div class="container" id="ji-display">

</div>

<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>

<script type="text/javascript">
	require(['jquery', 'ji-display-settings'], function ($)
	{
		$.fn.jiDisplaySettings().scholarship();
	});
</script>
