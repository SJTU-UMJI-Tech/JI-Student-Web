<?php include 'header.php'; ?>

<div class="container" id="ji-viewer">
	
	<div class="card">
		<div class="card-header">
			<?php echo $option['title']; ?>
		</div>
		<div class="card-block">
			<?php foreach ($option['item'] as $key => $item): ?>
				<?php if ($key > 0): ?>
					<br/>
					<div class="dropdown-divider"></div>
					<br/>
				<?php endif; ?>
				<?php if ($item['type'] == 'text'): ?>
					<div class="card-text">
						<h4><?php echo $item['name']; ?></h4>
						<br/>
						<?php echo $item['value']; ?>
					</div>
				<?php elseif ($item['type'] == 'md'): ?>
					<div class="card-text">
						<h4><?php echo $item['name']; ?></h4>
						<br/>
						<div class="markdown"><?php echo $item['value']; ?></div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	
	</div>
</div>

<?php include 'footer.php'; ?>

<script type="text/javascript">
	require(['jquery', 'marked'], function ($, marked)
	{
		$(".markdown").each(function ()
		{
			$(this).html(marked($(this).html()));
		});
	});
</script>
