<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>
<div class="container" id="ji-display">

</div>

<?php include dirname(dirname(__FILE__)) . '/common/footer.php'; ?>

<script type="text/javascript">
	require(['jquery', 'marked', 'bootstrap', 'ji-display'], function ($, marked)
	{
		window.console.log(marked);
		var generate = function (data)
		{
			//window.console.log(data);
			var detail_id = 'scholarship-detail-' + data.id;
			var html = [
				'<div class="card-block">',
				'<h4 class="card-title">', data.title, '</h4>',
				'<p class="card-text">', marked(data.abstract), '</p>',
				'<div class="card-text text-xs-center">',
				'<a class="btn btn-link" data-toggle="collapse" data-target="#' + detail_id +
				'" aria-expanded="false" aria-controls="' + detail_id + '">',
				'Details&nbsp;<i class="fa fa-angle-double-down" aria-hidden="true"></i>',
				'</a>',
				'</div>',
				'<div class="collapse" id="' + detail_id + '">',
				'<div class="card-text">', marked(data.content), '</div>',
				'</div>',
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
