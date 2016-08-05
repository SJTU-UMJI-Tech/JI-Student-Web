<?php include "common/header.php"; ?>

<script src="/vendors/fullcalendar/fullcalendar.js"></script>
<link rel="stylesheet" type="text/css" href="/vendors/fullcalendar/fullcalendar.css">

<div class="container">
	<?php if (!$this->Site_model->is_login()): ?>
		<div class="jumbotron">
			<h1 class="display-2">Welcome!</h1>
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
		<div id="calendar">
		
		</div>
	<?php endif; ?>
</div>

<script type="text/javascript">
	$(document).ready(function ()
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

<?php include "common/footer.php"; ?>

