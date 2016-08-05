<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>
<div class="container" id="ji-display">

</div>

<script src="/js/ji_display.js"></script>

<script type="text/javascript">
	$(document).ready(function ()
	{
		$("#ji-display").JIDisplay({
			title: 'Scholarships'
		});
	});
</script>

<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>
