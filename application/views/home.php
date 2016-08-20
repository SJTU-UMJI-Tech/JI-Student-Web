<?php include "common/header.php"; ?>

<link href="//cdn.bootcss.com/fullcalendar/2.9.1/fullcalendar.min.css" rel="stylesheet">

<div class="container">
	<?php if (!$this->Site_model->is_login()): ?>
		<div class="jumbotron">
			<h1 class="display-3">Welcome!</h1>
			<hr>
			<h3>UM-SJTU JI Student Online</h3>
			<p class="lead">
				This website is aiming at providing a collection of information to JI students, in student
				organizations,
				career development, scholarships and many other aspects. The website is still under developing, and thus
				we
				will be grateful if you could give us some <a href="/feedback">feedback</a>.
			</p>
			<p><i class="fa fa-arrows"></i></p>
			<?php echo $global_server_time; ?>
		</div>
	<?php else: ?>
		<div id="calendar"></div>
	<?php endif; ?>
</div>

<?php include "common/footer.php"; ?>

<script type="text/javascript">
	require(['jquery', 'moment', 'fullCalendar'], function ($)
	{
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: false
		});
	});
</script>