<?php

require_once 'settings.php';
require_once 'function.php';

function gen_sidebar($org_name = "", $page = -1)
{
	?>
	<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
		<div class="list-group">
			<a href="<?php echo "org_description.php?org=" . $org_name ?>" class="list-group-item <?php if ($page == 0)
			{
				echo "active";
			} ?>">About us</a>
			<a href="<?php echo "org_member.php?org=" . $org_name ?>" class="list-group-item <?php if ($page == 1)
			{
				echo "active";
			} ?>">Members</a>
			<a href="<?php echo "org_calendar.php?org=" . $org_name ?>" class="list-group-item <?php if ($page == 2)
			{
				echo "active";
			} ?>">Calendar</a>
			<a href="<?php echo "org_events.php?org=" . $org_name ?>" class="list-group-item <?php if ($page == 3)
			{
				echo "active";
			} ?>">Events</a>
			<a href="<?php echo "org_contacts.php?org=" . $org_name ?>" class="list-group-item <?php if ($page == 5)
			{
				echo "active";
			} ?>">Contact us</a>
		</div>
	</div><!--/span-->
	<?php
}

function gen_dash_sidebar($page = -1)
{
	?>
	<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
		<div class="list-group">
			<a href="dash_index.php" class="list-group-item <?php if ($page == 0)
			{
				echo "active";
			} ?>">Personal Info</a>
			<a href="dash_events.php" class="list-group-item <?php if ($page == 1)
			{
				echo "active";
			} ?>">My Events</a>
			<a href="dash_career.php" class="list-group-item <?php if ($page == 2)
			{
				echo "active";
			} ?>">My Career</a>
			<a href="dash_scholarship.php" class="list-group-item <?php if ($page == 3)
			{
				echo "active";
			} ?>">My Scholarships</a>
			<a href="dash_feedback.php" class="list-group-item <?php if ($page == 4)
			{
				echo "active";
			} ?>">My Feedbacks</a>
			<a href="dash_security.php" class="list-group-item <?php if ($page == 5)
			{
				echo "active";
			} ?>">Security Settings</a>
		</div>
	</div><!--/span-->
	<?php
}

function gen_events_sidebar($page = "", $dbc)
{
	?>
	<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
		<div class="list-group">
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>" class="list-group-item <?php if ($page == "")
			{
				echo "active";
			} ?>">All Events</a>
			<?php
			$query = "SELECT * FROM Organization WHERE org_name<>'Developers' ";
			$data = mysqli_query($dbc, $query);
			while ($org_info = $data->fetch_array())
			{
				$my_org_name = $org_info['org_name']; ?>
				
				<a href="<?php echo $_SERVER['PHP_SELF'] . '?org=' . $my_org_name; ?>"
				   class="list-group-item <?php if ($page == $my_org_name)
				   {
					   echo "active";
				   } ?>">
					<?php echo $my_org_name; ?>
				</a>
			<?php }
			?>
			<a href="<?php echo $_SERVER['PHP_SELF'] . '?org=Others' ?>"
			   class="list-group-item <?php if ($page == 'Others')
			   {
				   echo "active";
			   } ?>">Others</a>
		</div>
	</div><!--/span-->
	<?php
}

function gen_scholarship_sidebar($page = "")
{
	?>
	<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
		<div class="list-group">
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>" class="list-group-item <?php if ($page == "")
			{
				echo "active";
			} ?>">All Scholarships</a>
			<a href="<?php echo $_SERVER['PHP_SELF'] . '?cat=undergraduates' ?>"
			   class="list-group-item <?php if ($page == 'undergraduates')
			   {
				   echo "active";
			   } ?>">Undergraduates</a>
			<a href="<?php echo $_SERVER['PHP_SELF'] . '?cat=graduates' ?>"
			   class="list-group-item <?php if ($page == 'graduates')
			   {
				   echo "active";
			   } ?>">Graduates</a>
			<a href="<?php echo $_SERVER['PHP_SELF'] . '?cat=My Scholarships' ?>"
			   class="list-group-item <?php if ($page == 'My Scholarships')
			   {
				   echo "active";
			   } ?>">My Scholarships</a>
			<a href="<?php echo $_SERVER['PHP_SELF'] . '?cat=feedback' ?>"
			   class="list-group-item <?php if ($page == 'feedback')
			   {
				   echo "active";
			   } ?>">Feedback</a>
		</div>
	</div><!--/span-->
	<?php
}

function gen_career_sidebar($page = "")
{
	?>
	<div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
		<div class="list-group">
			<a href="<?php echo $_SERVER['PHP_SELF'] ?>" class="list-group-item <?php if ($page == "")
			{
				echo "active";
			} ?>">All Career info</a>
			<a href="<?php echo $_SERVER['PHP_SELF'] . '?cat=fulltime' ?>"
			   class="list-group-item <?php if ($page == 'fulltime')
			   {
				   echo "active";
			   } ?>">Fulltime</a>
			<a href="<?php echo $_SERVER['PHP_SELF'] . '?cat=internship' ?>"
			   class="list-group-item <?php if ($page == 'internship')
			   {
				   echo "active";
			   } ?>">Internship</a>
			<a href="<?php echo $_SERVER['PHP_SELF'] . '?cat=My Career' ?>"
			   class="list-group-item <?php if ($page == 'My Career')
			   {
				   echo "active";
			   } ?>">My Career</a>
			<a href="<?php echo $_SERVER['PHP_SELF'] . '?cat=feedback' ?>"
			   class="list-group-item <?php if ($page == 'feedback')
			   {
				   echo "active";
			   } ?>">Feedback</a>
		</div>
	</div><!--/span-->
	<?php
}

function gen_navbar($page = "")
{
	?>
	<div class="navbar navbar-fixed-top navbar-default" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">UM-SJTU JI Online</a>
			</div>
			<div class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li class="dropdown <?php if ($page == "Organizations") echo "active" ?>">
						<a href="#" role="button" class="dropdown-toggle">Organizations<i class="caret"></i>
						</a>
						<ul class="dropdown-menu">
							<li style="margin-top: 3px">
								<a tabindex="-1" href="./org_description.php?org=CPC">CPC</a>
							</li>
							<li style="margin-top: 3px">
								<a tabindex="-1" href="./org_description.php?org=Student Union">Student Union</a>
							</li>
						</ul>
					</li>
					<li class="dropdown <?php if ($page == "Events") echo "active" ?>">
						<a href="events.php" role="button" class="dropdown-toggle">Events
						</a>
					</li>
					<li class="dropdown <?php if ($page == "Advising") echo "active" ?>">
						<a href="./org_description.php?org=Advising" role="button" class="dropdown-toggle">Advising
						</a>
					</li>
					<li class="dropdown <?php if ($page == "Career") echo "active" ?>">
						<a href="./career.php" role="button" class="dropdown-toggle">Career
						</a>
					</li>
					<li class="dropdown <?php if ($page == "Scholarships") echo "active" ?>">
						<a href="./scholarships.php" role="button" class="dropdown-toggle">Scholarships
						</a>
					</li>
					<li class="dropdown <?php if ($page == "Dashboard") echo "active" ?>">
						<a href="./dash_index.php" role="button" class="dropdown-toggle">Dashboard<i class="caret"></i>
						</a>
						<ul class="dropdown-menu">
							<li style="margin-top: 3px">
								<a tabindex="-1" href="dash_index.php">Personal Info</a>
							</li>
							<li style="margin-top: 3px">
								<a tabindex="-1" href="dash_events.php">My Events</a>
							</li>
							<li style="margin-top: 3px">
								<a tabindex="-1" href="dash_career.php">My Career</a>
							</li>
							<li style="margin-top: 3px">
								<a tabindex="-1" href="dash_scholarship.php">My Scholarships</a>
							</li>
							<li style="margin-top: 3px">
								<a tabindex="-1" href="dash_feedback.php">My Feedbacks</a>
							</li>
							<hr style="margin-top: 5px; margin-bottom: 5px">
							<li>
								<a tabindex="-1" href="dash_security.php">Security Settings</a>
							</li>
						</ul>
					</li>
				</ul>
				<ul class="nav navbar-nav pull-right">
					<li class="dropdown">
						<a href="http://www.jikaisa.net" role="button" class="dropdown-toggle">BBS(非官方)
						</a>
					</li>
					<li class="dropdown">
						<?php
						if (isset($_SESSION["username"]))
						{
							?>
							<a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"> <i
										class="icon-user"></i> <?php echo $_SESSION["username"] ?> <i class="caret"></i></a>
							<ul class="dropdown-menu">
								<li style="margin-top: 3px">
									<a tabindex="-1" href="admin_index.php">Admin Panel</a>
								</li>
								<li class="divider"></li>
								<li>
									<a tabindex="-1" href="logout.php">Logout</a>
								</li>
							</ul>
							<?php
						}
						else
						{
							?>
							<a href="login.php" role="button" class="dropdown-toggle">Sign in</a>
							<?php
						}
						?>
					</li>
				</ul>
			</div><!-- /.nav-collapse -->
		
		</div><!-- /.container -->
	</div><!-- /.navbar -->
	<?php
}

function gen_sidebar_admin($page = -1)
{
	?>
	<div class="span3" id="sidebar">
		<ul class="nav nav-list bs-docs-sidenav nav-collapse collapse">
			<li class="<?php if ($page == 0)
			{
				echo "active";
			} ?>">
				<a href="admin_index.php"><i class="icon-chevron-right"></i> Dashboard</a>
			</li>
			<li class="<?php if ($page == 1)
			{
				echo "active";
			} ?>">
				<a href="admin_org.php"><i class="icon-chevron-right"></i> Organization Admin</a>
			</li>
			<li class="<?php if ($page == 2)
			{
				echo "active";
			} ?>">
				<a href="admin_member.php"><i class="icon-chevron-right"></i> Member Admin</a>
			</li>
			<li class="<?php if ($page == 3)
			{
				echo "active";
			} ?>">
				<a href="admin_events.php"><i class="icon-chevron-right"></i> Events Admin</a>
			</li>
			<li class="<?php if ($page == 4)
			{
				echo "active";
			} ?>">
				<a href="admin_feedback.php"><i class="icon-chevron-right"></i> Feedbacks</a>
			</li>
			<li class="<?php if ($page == 5)
			{
				echo "active";
			} ?>">
				<a href="admin_career.php"><i class="icon-chevron-right"></i> Career</a>
			</li>
			<li class="<?php if ($page == 7)
			{
				echo "active";
			} ?>">
				<a href="admin_scholarship.php"><i class="icon-chevron-right"></i> Scholarships</a>
			</li>
		</ul>
	</div>
	<?php
}


function gen_navbar_admin($page = '')
{
	?>
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"> <span
							class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="#">Admin Panel</a>
				<div class="nav-collapse collapse">
					<ul class="nav pull-right">
						<li class="dropdown">
							<a href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"> <i
										class="icon-user"></i> <?php echo $_SESSION["username"] ?> <i class="caret"></i>
							
							</a>
							<ul class="dropdown-menu">
								<li>
									<a tabindex="-1" href="admin_index.php">Profile</a>
								</li>
								<li class="divider"></li>
								<li>
									<a tabindex="-1" href="logout.php">Logout</a>
								</li>
							</ul>
						</li>
					</ul>
					<ul class="nav">
						<li class="active">
							<a href="admin_index.php">Dashboard</a>
						</li>
						<li>
							<a href="index.php">View Site</a>
						</li>
					</ul>
				</div>
				<!--/.nav-collapse -->
			</div>
		</div>
	</div>
	<?php
}


function gen_Admin_pageEnd($value = '')
{
	?>
	<!--script src="vendors/jquery-1.9.1.js"></script-->
	<script src="js/jquery-1.12.3.min.js"></script>
	<script type="text/javascript" src="js/jquery.base64.js"></script>
	
	<script type="text/javascript" src="js/tableExport.js"></script>
	
	<script type="text/javascript" src="js/jspdf/libs/sprintf.js"></script>
	
	<script type="text/javascript" src="js/jspdf/jspdf.js"></script>
	<script type="text/javascript" src="js/jspdf/libs/base64.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
	<script src="vendors/datatables/js/jquery.dataTables.min.js"></script>
	<script src="assets/DT_bootstrap.js"></script>
	<script src="vendors/easypiechart/jquery.easy-pie-chart.js"></script>
	<script src="assets/scripts.js"></script>
	<script>
		$(function ()
		{
			// Easy pie charts
			$('.chart').easyPieChart({animate: 1000});
		});
	</script>
	<!--link href="vendors/datepicker.css" rel="stylesheet" media="screen"-->
	<link href="vendors/uniform.default.css" rel="stylesheet" media="screen">
	<link href="vendors/chosen.min.css" rel="stylesheet" media="screen">
	
	<link href="vendors/wysiwyg/bootstrap-wysihtml5.css" rel="stylesheet" media="screen">
	<script src="vendors/jquery.uniform.min.js"></script>
	<script src="vendors/chosen.jquery.min.js"></script>
	<!--script src="vendors/bootstrap-datepicker.js"></script-->
	
	<script src="vendors/wysiwyg/wysihtml5-0.3.0.js"></script>
	<script src="vendors/wysiwyg/bootstrap-wysihtml5.js"></script>
	
	<script src="vendors/wizard/jquery.bootstrap.wizard.min.js"></script>
	<!--script src="vendors/bootstrap-wysihtml5/lib/js/wysihtml5-0.3.0.js"></script-->
	<!--script src="vendors/bootstrap-wysihtml5/src/bootstrap-wysihtml5.js"></script-->
	<script src="vendors/ckeditor/ckeditor.js"></script>
	<script src="vendors/ckeditor/adapters/jquery.js"></script>
	<script type="text/javascript" src="vendors/tinymce/js/tinymce/tinymce.min.js"></script>
	<script src="assets/scripts.js"></script>
	<script type="text/javascript" src="vendors/jquery-validation/dist/jquery.validate.min.js"></script>
	<script src="assets/form-validation.js"></script>
	<link type="text/css" href="css/jquery-ui.css" rel="stylesheet"/>
	<script type="text/javascript" src="js/jquery-ui.min.js"></script>
	<link href="css/jquery-ui-timepicker-addon.css" type="text/css"/>
	<script src="js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
	<script type="text/javascript">
		jQuery(function ()
		{
			jQuery('.datetimepicker').datetimepicker({
				timeFormat: "HH:mm:00",
				dateFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true,
				stepMinute: 10
			});
			
		});
	</script>
	
	<script>
		
		jQuery(document).ready(function ()
		{
			FormValidation.init();
		});
		
		
		$(function ()
		{
			$(".datepicker").datepicker({
				dateFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true
			});
			$(".uniform_on").uniform();
			$(".chzn-select").chosen();
			$('.textarea').wysihtml5();
			
			$('#rootwizard').bootstrapWizard({
				onTabShow: function (tab, navigation, index)
				{
					var $total = navigation.find('li').length;
					var $current = index + 1;
					var $percent = ($current / $total) * 100;
					$('#rootwizard').find('.bar').css({width: $percent + '%'});
					// If it's the last tab then hide the last button and show the finish instead
					if ($current >= $total)
					{
						$('#rootwizard').find('.pager .next').hide();
						$('#rootwizard').find('.pager .finish').show();
						$('#rootwizard').find('.pager .finish').removeClass('disabled');
					}
					else
					{
						$('#rootwizard').find('.pager .next').show();
						$('#rootwizard').find('.pager .finish').hide();
					}
				}
			});
			$('#rootwizard .finish').click(function ()
			{
				alert('Finished!, Starting over!');
				$('#rootwizard').find("a[href*='tab1']").trigger('click');
			});
		});
		
		$().ready(function ()
		{
			$(".form-horizontal").validate();
		});
		
		$().ready(function ()
		{
			$(".form-inline").validate();
		});
	</script>
	<script>
		$(function ()
		{
			// Bootstrap
			$('#bootstrap-editor').wysihtml5();
			
			// Ckeditor standard
			$('textarea#ckeditor_standard').ckeditor({
				width: '98%', height: '150px', toolbar: [
					{name: 'document', items: ['Source', '-', 'NewPage', 'Preview', '-', 'Templates']}, // Defines toolbar group with name (used to create voice label) and items in 3 subgroups.
					['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],          // Defines toolbar group without name.
					{name: 'basicstyles', items: ['Bold', 'Italic']}
				]
			});
			$('textarea#ckeditor_full').ckeditor({width: '98%', height: '150px'});
		});
	</script>
	<?php
}


function gen_header($title = 'UM-SJTU JI Online')
{ ?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
	<meta name="description" content="Online Judge for VE475 Challenge 2">
	<meta name="author" content="Peng YUAN">
	
	<link rel="icon" href="../../favicon.ico">
	
	<title>Theme Template for Bootstrap</title>
	
	<!-- Bootstrap core CSS -->
	<link href="./css/bootstrap.min.css" rel="stylesheet">
	<!-- Bootstrap theme -->
	<link href="./css/bootstrap-theme.min.css" rel="stylesheet">
	
	<!-- Custom styles for this template -->
	<link href="./css/theme.css" rel="stylesheet">
<?php }

function gen_header_admin($title = 'UM-SJTU JI Online - Admin')
{
	?>
	<meta charset="utf-8">
	<title><?php echo $title ?></title>
	<!-- Bootstrap -->
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
	<link href="vendors/easypiechart/jquery.easy-pie-chart.css" rel="stylesheet" media="screen">
	<link href="assets/styles.css" rel="stylesheet" media="screen">
	<link href="assets/DT_bootstrap.css" rel="stylesheet" media="screen">
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script src="vendors/modernizr-2.6.2-respond-1.1.0.min.js"></script>
	<?php
}


function gen_footer_admin($title = 'UM-SJTU JI Student Affairs - Admin')
{
	?>
	<footer>
		<p>&copy; <?php echo $title; ?><?php check_by_360(); ?></p>
	</footer>
	<?php
}

function gen_footer($title = 'UM-SJTU JI Student Affairs')
{
	?>
	<footer>
		<p>&copy; <?php echo $title ?><?php check_by_360(); ?> | developed by <a
					href="http://ji.sjtu.edu.cn/student/org_member.php?org=Advising&memberID=5123709184">Peng</a> and
			Zhuangzhuang</p>
	</footer>
	<?php
}

function gen_pageEnd()
{ ?>
	<script src="vendors/jquery-1.9.1.min.js"></script>
	<script src="vendors/jquery.uniform.min.js"></script>
	<script src="vendors/chosen.jquery.min.js"></script>
	<script src="vendors/wizard/jquery.bootstrap.wizard.min.js"></script>
	<script src="./js/offcanvas.js"></script>
	<script src="./js/bootstrap.min.js"></script>
	<script src="js/jquery.min.js" type="text/javascript"></script>
	<!--link rel="stylesheet" type="text/css" href="http://www.mikesmithdev.com/shared/css/calendar.css"-->
	<link href='css/fullcalendar.css' rel='stylesheet'/>
	<link href='css/my.css' rel='stylesheet'/>
	<link href='css/fullcalendar.print.css' rel='stylesheet' media='print'/>
	<script src='js/moment.min.js'></script>
	<script src='js/fullcalendar.min.js'></script>
	
	<script type="text/javascript" src="vendors/jquery-validation/dist/jquery.validate.min.js"></script>
	<script src="assets/form-validation.js"></script>
	
	<link type="text/css" href="css/jquery-ui.css" rel="stylesheet"/>
	<script type="text/javascript" src="js/jquery-ui.min.js"></script>
	
	<link href="css/jquery-ui-timepicker-addon.css" type="text/css"/>
	<script src="js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
	
	<script type="text/javascript" src="js/jquery.jqpagination.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/jqpagination.css">
	<script src="vendors/ckeditor/ckeditor.js"></script>
	<script src="vendors/ckeditor/adapters/jquery.js"></script>
	<script>
		jQuery(document).ready(function ()
		{
			FormValidation.init();
		});
		
		$(function ()
		{
			$(".datepicker").datepicker({
				dateFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true
			});
			
			// Ckeditor standard
			$('textarea#ckeditor_standard').ckeditor({
				width: '98%', height: '150px', toolbar: [
					{name: 'document', items: ['Source', '-', 'NewPage', 'Preview', '-', 'Templates']}, // Defines toolbar group with name (used to create voice label) and items in 3 subgroups.
					['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],          // Defines toolbar group without name.
					{name: 'basicstyles', items: ['Bold', 'Italic']}
				]
			});
			$('textarea#ckeditor_full').ckeditor({width: '98%', height: '150px'});
		});
		
		$().ready(function ()
		{
			$(".form-horizontal").validate();
		});
		
		$().ready(function ()
		{
			$(".form-inline").validate();
		});
		
		var ele_per_page = <?php echo $GLOBALS['ele_per_page'] ?>;
		function loadpage(page)
		{
			
			$('.col-xs-12 .row').hide();
			//document.write($('.col-xs-12 .row'));
			// but show the one we want
			for (var i = page * ele_per_page; i < (page + 1) * ele_per_page; i++)
			{
				if (i == $('.col-xs-12 .row').length)
				{
					break;
				}
				$($('.col-xs-12 .row')[i]).show();
			}
		}
		
		// hide all but the first of our paragraphs
		loadpage(0);
		$('.pagination').jqPagination({
			max_page: Math.ceil($('.col-xs-12 .row').length / ele_per_page),
			paged: function (page)
			{
				loadpage(page - 1);
			}
		});
	</script>
<?php }

function gen_reg_event_form($event_id)
{
	?>
	<form class="form-horizontal" method="POST"
	      action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>">
		<div class="form-group">
			<label class="col-sm-2 control-label">Name</label>
			<div class="col-sm-6">
				<a name="reg_anchor"></a>
				<input type="text" disabled="" class="form-control" placeholder="FirstName LastName"
				       name="name" value="<?php if (isset($_SESSION['username']))
				{
					echo $_SESSION['username'];
				} ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">ID</label>
			<div class="col-sm-6">
				<input type="text" disabled="" class="form-control" placeholder="Student ID / User ID"
				       name="userID" value="<?php if (isset($_SESSION['user_id']))
				{
					echo $_SESSION['user_id'];
				} ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Phone</label>
			<div class="col-sm-6">
				<input type="text" class="form-control required" id="reg_phone" placeholder="eg. +86 123456789"
				       name="phone" value="<?php if (isset($_SESSION['userphone']))
				{
					echo $_SESSION['userphone'];
				} ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Email</label>
			<div class="col-sm-6">
				<input type="text" class="form-control required email" id="reg_email"
				       placeholder="eg. example@example.com"
				       name="email" value="<?php if (isset($_SESSION['useremail']))
				{
					echo $_SESSION['useremail'];
				} ?>">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input type="hidden" name="CSRFcheck" value="<?php echo $_SESSION['CSRFcheck'] ?>">
				<input type="hidden" name="event_id" value="<?php echo $event_id ?>">
				<button type="submit" class="btn btn-primary" name="submit">Join!</button>
				<button type="reset" class="btn btn-default" name="submit">Cancel</button>
			</div>
		</div>
	</form>
	<?php
}


function gen_reg_form($dbc, $key_id, $table_name, $id_name)
{
	$table_id_info['table_name'] = $table_name;
	$table_id_info['id_name'] = $id_name;
	?>
	<form class="form-horizontal" method="POST" enctype="multipart/form-data"
	      action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>">
		<fieldset>
			<div class="form-group">
				<label class="col-sm-2 control-label">Name</label>
				<div class="col-sm-6">
					<input type="text" disabled="" class="form-control" placeholder="FirstName LastName"
					       name="name" value="<?php if (isset($_SESSION['username']))
					{
						echo $_SESSION['username'];
					} ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">ID</label>
				<div class="col-sm-6">
					<input type="text" disabled="" class="form-control" placeholder="Student ID / User ID"
					       name="userID" value="<?php if (isset($_SESSION['user_id']))
					{
						echo $_SESSION['user_id'];
					} ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Phone</label>
				<div class="col-sm-6">
					<input type="text" class="form-control required" id="reg_phone" placeholder="eg. +86 123456789"
					       name="phone" value="<?php if (isset($_SESSION['userphone']))
					{
						echo $_SESSION['userphone'];
					} ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Email</label>
				<div class="col-sm-6">
					<input type="text" class="form-control required email" id="reg_email"
					       placeholder="eg. example@example.com"
					       name="email" value="<?php if (isset($_SESSION['useremail']))
					{
						echo $_SESSION['useremail'];
					} ?>">
				</div>
			</div>
			<div class="form-group">
				
				<label class="col-sm-2 control-label">Attachments</label>
				<div class="col-sm-8">
					<div style="padding-top: 6px" class="col-sm-12">
						<?php
						$query = "SELECT * FROM Attachments WHERE key_id='" . mysqli_real_escape_string($dbc, $key_id)
						         . "' COLLATE utf8_bin AND table_name='" . $table_id_info['table_name'] .
						         "' COLLATE utf8_bin AND upload_by_user_id='" .
						         mysqli_real_escape_string($dbc, $_SESSION['user_id'])
						         . "' COLLATE utf8_bin AND permission='org' COLLATE utf8_bin";
						$data = mysqli_query($dbc, $query);
						dev_echo(mysqli_error($dbc));
						while ($one_file = $data->fetch_array())
						{
							?>
							<p>
								<a href="<?php echo 'file.php?file=' . $one_file['file_link'] . '&CSRFcheck=' .
								                    $_SESSION['CSRFcheck'] ?>"><?php echo urldecode($one_file['attachment_name']); ?></a>
								|
								<a href="<?php echo $_SERVER['PHP_SELF'] . '?' . $table_id_info['id_name'] . '=' .
								                    $key_id . '&rmfile=' . $one_file['file_link'] . '&CSRFcheck=' .
								                    $_SESSION['CSRFcheck']; ?>">Remove</a>
							</p>
							<?php
						}
						?>
						<div style="padding-top: 5px" class="col-sm-6">
							<input class="input-file uniform_on" id="attachment" type="file" name="attachment">
						</div>
						<div style="padding-top: 5px" class="col-sm-6">
							<button type="submit" class="btn btn-default" name="upload">upload</button>
						</div>
					</div>
				</div>
			</div>
			<hr>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="hidden" name="CSRFcheck" value="<?php echo $_SESSION['CSRFcheck'] ?>">
					<input type="hidden" name="<?php echo $table_id_info['id_name']; ?>" value="<?php echo $key_id ?>">
					<button type="submit" class="btn btn-primary" name="submit">Register!</button>
					<button type="reset" class="btn btn-default" name="submit">Cancel</button>
				</div>
			
			</div>
		</fieldset>
	</form>
	<?php
}


function err_handle($err_msg, $succ_msg, $post_key)
{ // handle error msg
	if (isset($_POST[$post_key]) || isset($_GET[$post_key]))
	{
		if ($err_msg != "")
		{ ?>
			<div class="alert alert-danger">
				<button class="close" data-dismiss="alert">&times;</button>
				<strong>Error! </strong><?php echo $err_msg; ?>
			</div>
		<?php }
		else
		{ ?>
			<div class="alert alert-success">
				<button class="close" data-dismiss="alert">&times;</button>
				<strong>Success! </strong><?php echo $succ_msg; ?>
			</div>
		<?php }
	}
}

function gen_scholarship_details($dbc, $scholarship_id)
{
	$query = "SELECT * FROM Scholarships WHERE status='published' AND scholarship_id='" .
	         mysqli_real_escape_string($dbc, $scholarship_id) . "' COLLATE utf8_bin";
	$result = mysqli_query($dbc, $query);
	if ($result)
	{
		$scholarship_info = $result->fetch_array();
	}
	?>
	<h1><?php echo $scholarship_info['title']; ?></h1>
	<hr>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<h4><strong>Category: </strong><?php echo $scholarship_info['category']; ?></h4>
			<h4><strong>Deadline: </strong><?php echo $scholarship_info['deadline']; ?></h4>
			<h4><strong>Ammount: </strong><?php echo $scholarship_info['ammount']; ?></h4>
			<h4><strong>Contacts: </strong><a
						href="mailto:<?php echo $scholarship_info['contact_email']; ?>"><?php echo $scholarship_info['contact_email']; ?></a>
			</h4>
			<h4><strong>Requirements: </strong><?php echo $scholarship_info['requirements']; ?></h4>
			<h4><strong>Description: </strong><?php echo $scholarship_info['description']; ?></h4>
			<h4><strong>Attachments: </strong><?php
				$query = "SELECT * FROM Attachments WHERE key_id='" . mysqli_real_escape_string($dbc, $scholarship_id)
				         .
				         "' COLLATE utf8_bin AND table_name='Scholarships' COLLATE utf8_bin AND permission='login' COLLATE utf8_bin";
				$data = mysqli_query($dbc, $query);
				dev_echo(mysqli_error($dbc));
				while ($one_file = $data->fetch_array())
				{
					?>
					<a href="<?php echo 'file.php?file=' . $one_file['file_link'] . '&CSRFcheck=' .
					                    $_SESSION['CSRFcheck'] ?>"><?php echo urldecode($one_file['attachment_name']); ?></a> |
					<?php
				}
				
				?></h4>
		</div>
	</div>
	<?php
}

function gen_career_details($dbc, $career_id)
{
	$query = "SELECT * FROM Career WHERE status='published' AND career_id='" .
	         mysqli_real_escape_string($dbc, $career_id) . "' COLLATE utf8_bin";
	$result = mysqli_query($dbc, $query);
	if ($result)
	{
		$scholarship_info = $result->fetch_array();
	}
	?>
	<h1><?php echo $scholarship_info['title']; ?></h1>
	<hr>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<h4><strong>Category: </strong><?php echo $scholarship_info['category']; ?></h4>
			<h4><strong>Deadline: </strong><?php echo $scholarship_info['deadline']; ?></h4>
			<h4><strong>Company: </strong><?php echo $scholarship_info['company']; ?></h4>
			<h4><strong>Contacts: </strong><a
						href="mailto:<?php echo $scholarship_info['contact_email']; ?>"><?php echo $scholarship_info['contact_email']; ?></a>
			</h4>
			<h4><strong>Requirements: </strong><?php echo $scholarship_info['requirements']; ?></h4>
			<h4><strong>Description: </strong><?php echo $scholarship_info['description']; ?></h4>
			<h4><strong>Attachments: </strong><?php
				$query = "SELECT * FROM Attachments WHERE key_id='" . mysqli_real_escape_string($dbc, $career_id)
				         .
				         "' COLLATE utf8_bin AND table_name='Career' COLLATE utf8_bin AND permission='login' COLLATE utf8_bin";
				$data = mysqli_query($dbc, $query);
				dev_echo(mysqli_error($dbc));
				while ($one_file = $data->fetch_array())
				{
					?>
					<a href="<?php echo 'file.php?file=' . $one_file['file_link'] . '&CSRFcheck=' .
					                    $_SESSION['CSRFcheck'] ?>"><?php echo urldecode($one_file['attachment_name']); ?></a> |
					<?php
				}
				
				?></h4>
		</div>
	</div>
	<?php
}

function gen_feedback_form()
{ ?>
	<form class="form-horizontal" method="POST"
	      action="<?php echo $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']; ?>">
		<div class="form-group">
			<label class="col-sm-2 control-label">Content</label>
			<div class="col-sm-10">
            <textarea class="form-control required" style="height: 200px" id="feedback_content" name="content"
                      placeholder="Your idea/suggestion/problem/..."></textarea>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Name</label>
			<div class="col-sm-6">
				<input type="text" disabled="" class="form-control" placeholder="FirstName LastName"
				       name="name" value="<?php if (isset($_SESSION['username']))
				{
					echo $_SESSION['username'];
				} ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">ID</label>
			<div class="col-sm-6">
				<input type="text" disabled="" class="form-control" placeholder="Student ID / User ID"
				       name="userID" value="<?php if (isset($_SESSION['user_id']))
				{
					echo $_SESSION['user_id'];
				} ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">Email</label>
			<div class="col-sm-6">
				<input type="text" id="feedback_email" class="form-control required email"
				       placeholder="eg. example@example.com"
				       name="email" value="<?php if (isset($_SESSION['useremail']))
				{
					echo $_SESSION['useremail'];
				} ?>">
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<input type="hidden" name="CSRFcheck" value="<?php echo $_SESSION['CSRFcheck'] ?>">
				<button type="submit" class="btn btn-primary" name="feedback">Submit</button>
				<button type="reset" class="btn btn-default" name="submit">Cancel</button>
			</div>
		</div>
	</form>
	<?php
}

function gen_pagination()
{ ?>
	<div class="gigantic pagination" style="margin-top: 30px">
		<a href="#" class="first" data-action="first">&laquo;</a>
		<a href="#" class="previous" data-action="previous">&lsaquo;</a>
		<input type="text" readonly="readonly"/>
		<a href="#" class="next" data-action="next">&rsaquo;</a>
		<a href="#" class="last" data-action="last">&raquo;</a>
	</div>
<?php }


function gen_event_detail($dbc, $event_id)
{
	$query = "SELECT * FROM Events WHERE event_id='" . mysqli_real_escape_string($dbc, $event_id) .
	         "' COLLATE utf8_bin AND status='published'";
	$result = mysqli_query($dbc, $query);
	if ($result)
	{
		$event_info = $result->fetch_array();
	}
	?>
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<h1><?php echo $event_info['title']; ?></h1>
			<hr>
			<div class="row">
				<?php if ($event_info['photo_link'] != "")
				{ ?>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
						<img style="max-width: 600px;" src="<?php echo $event_info['photo_link'] ?>">
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
						<hr>
						<p><strong>From </strong><?php echo $event_info['time']; ?></p>
						<p><strong>To </strong><?php echo $event_info['end_time']; ?></p>
						<p><strong>Location: </strong><?php echo $event_info['location']; ?></p>
						<p><strong>Speaker: </strong><?php echo $event_info['speaker']; ?></p>
						<p><strong>Event Manager: </strong><?php echo $event_info['manager']; ?></p>
						<p><strong>Contact us: </strong><a
									href="mailto:<?php echo $event_info['contact_email']; ?>"><?php echo $event_info['contact_email']; ?></a>
						</p>
					</div>
				<?php }
				else
				{ ?>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<h4><strong>From: </strong><?php echo $event_info['time']; ?></h4>
						<h4><strong>To: </strong><?php echo $event_info['end_time']; ?></h4>
						<h4><strong>Location: </strong><?php echo $event_info['location']; ?></h4>
						<h4><strong>Speaker: </strong><?php echo $event_info['speaker']; ?></h4>
						<h4><strong>Event Manager: </strong><?php echo $event_info['manager']; ?></h4>
						<h4><strong>Contact us: </strong><a
									href="mailto:<?php echo $event_info['contact_email']; ?>"><?php echo $event_info['contact_email']; ?></a>
						</h4>
					</div>
				<?php } ?>
			
			
			</div>
			<hr>
			<p><?php echo $event_info['description'] ?></p>
			<p><strong>Attachments: </strong><?php
				$query = "SELECT * FROM Attachments WHERE key_id='" . mysqli_real_escape_string($dbc, $event_id)
				         .
				         "' COLLATE utf8_bin AND table_name='Events' COLLATE utf8_bin AND permission='login' COLLATE utf8_bin";
				$data = mysqli_query($dbc, $query);
				dev_echo(mysqli_error($dbc));
				while ($one_file = $data->fetch_array())
				{
					?>
					<a href="<?php echo 'file.php?file=' . $one_file['file_link'] . '&CSRFcheck=' .
					                    $_SESSION['CSRFcheck'] ?>"><?php echo urldecode($one_file['attachment_name']); ?></a>
					<?php
				}
				?></p>
			<hr>
		</div>
	</div>
	<?php
}

?>