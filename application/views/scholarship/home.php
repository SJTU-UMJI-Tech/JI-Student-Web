<?php include dirname(dirname(__FILE__)) . '/common/header.php'; ?>
<div class="container" id="ji-display">
	
<!--	<nav aria-label="...">
		<ul class="pagination">
			<li class="page-item disabled">
				<a class="page-link" href="#" tabindex="-1" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
					<span class="sr-only">Previous</span>
				</a>
			</li>
			<li class="page-item active">
				<a class="page-link" href="#">1 <span class="sr-only">(current)</span></a>
			</li>
			<li class="page-item"><a class="page-link" href="#">2</a></li>
			<li class="page-item"><a class="page-link" href="#">3</a></li>
			<li class="page-item"><a class="page-link" href="#">4</a></li>
			<li class="page-item"><a class="page-link" href="#">5</a></li>
			<li class="page-item">
				<a class="page-link" href="#" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
					<span class="sr-only">Next</span>
				</a>
			</li>
		</ul>
	</nav>-->
	
	
</div>

<script src="/js/ji-display.js"></script>

<script type="text/javascript">
	$(document).ready(function ()
	{
		var display = $("#ji-display").JIDisplay({
			title: 'Scholarships',
			item: {
				all: {
					name: 'All scholarships',
					url: '/scholarship/ajax'
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
