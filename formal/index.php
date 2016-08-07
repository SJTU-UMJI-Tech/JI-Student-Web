<!DOCTYPE html>
<?php
session_start();
require_once 'component.php';
require_once 'function.php';
$dbc = db_connect();
init_web($dbc);
?>

<html lang="en">
<head>
	<?php gen_header(); ?>
</head>

<body role="document">
<?php gen_navbar(); ?>
<div class="container">
	
	<div class="jumbotron">
		<h1>Welcome!</h1>
		<hr>
		<h3>UM-SJTU JI Student Online</h3>
		<p>This website is aiming at providing a collection of information to JI students, in student organizations,
			career development, scholarships and many other aspects. The website is still under developing, and thus we
			will be grateful if you could give us some <a href="feedback.php">feedback</a>. </p>
	</div>
	<hr>
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-content box-nomargin">
					<div id="calendar"></div>
					<div class="row">
						<?php foreach ($GLOBALS['all_orgs'] as $key => $value): ?>
							<div class="col-sm-2">
								<input type="checkbox" checked="checked" name="e<?php echo $key ?>"
								       id="e<?php echo $key ?>"/>
								<label class="chk_box" for="e<?php echo $key ?>"><?php echo $value ?></label>
							</div>
						<?php endforeach ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-10">
			<h2>Organizations</h2>
			<h4>This section will contain most of student organizations here, including there description and member
				information.</h4>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-10">
			<h2>Events</h2>
			<h4>The place where you could find all activities.</h4>
		</div>
		<div class="col-md-2" style="padding-top: 30px">
			<a href="events.php">
				<button class="btn btn-default">Take a look</button>
			</a>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-10">
			<h2>Career</h2>
			<h4>Career opportunities at JI, including information on fulltime recruitment and internships.</h4>
		</div>
		<div class="col-md-2" style="padding-top: 30px">
			<a href="career.php">
				<button class="btn btn-default">Take a look</button>
			</a>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-10">
			<h2>Scholarships</h2>
			<h4>Check and apply for availiable scholarships here.</h4>
		</div>
		<div class="col-md-2" style="padding-top: 30px">
			<a href="scholarships.php">
				<button class="btn btn-default">Take a look</button>
			</a>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-10">
			<h2>Dashboard</h2>
			<h4>Where you could find all your registed events, career, scholarships and feedbacks you provided.</h4>
		</div>
		<div class="col-md-2" style="padding-top: 30px">
			<a href="dash_index.php">
				<button class="btn btn-default">Take a look</button>
			</a>
		</div>
	</div>
	<?php
	$org_info = array();
	/*
		$query = "SELECT * FROM Organization";
		$result = mysqli_query($dbc,$query);
		if ($result) {
			if($result->num_rows > 0){
				while($org_info = $result->fetch_array()){
					?>
					<div class="jumbotron">
					  <h1><?php echo $org_info['org_name']?></h1>
					  <p><?php echo $org_info['short_description']?></p>
					  <hr>
							<p align="left">
								<a href="org_description.php?org=<?php echo $org_info['org_name']?>" class="btn btn-default btn-lg" role="button">Learn more &raquo;</a>
							</p>
					</div>
					<?php
				}
			}
		}*/
	?>
	
	<hr>
	<?php gen_footer(); ?>

</div><!--/.container-->
<?php gen_pageEnd(); ?>

<script>
	var curSource = new Array();
	
	<?php $idx = 0; foreach ($GLOBALS['all_orgs'] as $key => $value): ?>
	curSource[<?php echo $idx; $idx += 1; ?>] = "cal_data.php?org=<?php echo $value ?>&";
	<?php endforeach ?>
	
	var newSource = new Array();
	$(document).ready(function ()
	{
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: false,
			//theme:true,
			//eventLimit: true, // allow "more" link when too many events
			eventSources: [
				<?php $idx = 0; foreach ($GLOBALS['all_orgs'] as $key => $value): ?>
				{url: curSource[<?php echo $idx; $idx += 1; ?>]},
				<?php endforeach ?>
			]
		});
		
		$("<?php foreach ($GLOBALS['all_orgs'] as $key => $value)
		{
			echo '#e' . $key . ', ';
		}?>").change(function ()
		{
			//get current status of our filters into newSource
			<?php $idx = 0; foreach ($GLOBALS['all_orgs'] as $key => $value): ?>
			if ($('#e<?php echo $key; ?>').is(':checked') == true)
			{
				newSource[<?php echo $idx;?>] = "cal_data.php?org=<?php echo $value; ?>&";
			}
			else
			{
				newSource[<?php echo $idx; $idx++;?>] = ""
			}
			;
			<?php endforeach ?>
			//remove the old eventSources
			for (var i = 0; i < curSource.length; i++)
			{
				$('#calendar').fullCalendar('removeEventSource', curSource[i]);
			}
			;
			$('#calendar').fullCalendar('refetchEvents');
			
			//attach the new eventSources
			for (var i = 0; i < newSource.length; i++)
			{
				$('#calendar').fullCalendar('addEventSource', newSource[i]);
			}
			;
			$('#calendar').fullCalendar('refetchEvents');
			
			curSource = newSource;
			newSource = new Array();
		});
		
		
	});
</script>
<?php mysqli_close($dbc); ?>
</body>
</html>