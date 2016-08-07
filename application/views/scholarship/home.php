<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>
<div class="container" id="ji-display">

</div>

<script src="/js/ji-display.js"></script>

<script type="text/javascript">
	$(document).ready(function ()
	{
		var generate = function (data)
		{
			window.console.log(data);
			var html = [
				'<div class="card-block">',
				'<h4 class="card-title">', data.title, '</h4>',
				'<p class="card-text">', data.abstract, '</p>',
				'</div>'
			].join('');
			return html;
		};
		
		var display = $("#ji-display").JIDisplay({
			title: 'Scholarships',
			item: {
				all: {
					name: 'All scholarships',
					url: '/scholarship/ajax',
					sort: ['Newest', 'Oldest'],
					primary: 'id',
					limit: 2,
					generate: generate
				},
				undergraduate: {
					name: 'Undergraduates',
					url: '/scholarship/ajax'
				},
				graduate: {
					name: 'Graduates',
					url: '/scholarship/ajax'
				},
				my: {
					name: 'My scholarships',
					url: '/scholarship/ajax'
				}
			}
		});
		var name = 'all';
		var $barItem = display.$bar.find(".list-group-item[data-text='" + name + "']");
		display.switchCard($barItem);
	});
</script>

<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>
