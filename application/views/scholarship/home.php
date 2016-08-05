<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>
<div class="container" id="ji-display">

</div>

<script src="/js/ji-display.js"></script>

<script type="text/javascript">
	$(document).ready(function ()
	{
		var display = $("#ji-display").JIDisplay({
			title: 'Scholarships',
			type: {
				all: 'All scholarships',
				undergraduate: 'Undergraduates',
				graduate: 'Graduates',
				my: 'My scholarships'
			}
		});
		var name = 'all';
		var $barItem = display.$bar.find(".list-group-item[data-text='" + name + "']");
		display.switchCard($barItem);
	});
</script>

<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>
