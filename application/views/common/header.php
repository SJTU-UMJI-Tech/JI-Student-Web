<?php
/**
 * A common header of the website.
 */
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link rel="shortcut icon" href="<?php echo ROOT_DIR; ?>/images/favicon.png">
	<title><?php echo $page_name; ?></title>
	
	<!-- Load the global libraries -->
	<?php if (ENVIRONMENT == 'production'): ?>
		<link rel="stylesheet" type="text/css" href="//cdn.bootcss.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="//cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.min.css">
	<?php else: ?>
		<link rel="stylesheet" type="text/css" href="<?php echo ROOT_DIR; ?>/css/bootstrap-4.0.0-alpha.3.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo ROOT_DIR; ?>/vendors/Font-Awesome-4.6.3/css/font-awesome.min.css">
	<?php endif; ?>
	<link rel="stylesheet" type="text/css" href="<?php echo ROOT_DIR; ?>/css/common.css">
	<base target="_self">
</head>

<body>

<nav class="navbar navbar-light navbar-fixed-top bg-faded" role="navigation">
	<div class="container">
		<div class="nav navbar-nav">
			<button class="navbar-toggler hidden-md-up active pull-xs-right" type="button" data-toggle="collapse"
			        data-target=".navbar-collapse" aria-controls=".navbar-collapse" aria-expanded="false"
			        aria-label="Toggle navigation">
				&#9776;
			</button>
			<a class="navbar-brand" href="<?php echo ROOT_DIR; ?>/">UM-SJTU JI Online</a>
		</div>
		<div class="collapse navbar-collapse navbar-toggleable-sm">
			<ul class="nav navbar-nav">
				<li class="nav-item hidden-md-up">
					<a class="nav-link" href="<?php echo ROOT_DIR; ?>/" style="color: transparent">1</a>
				</li>
				<li class="dropdown-divider hidden-md-up"></li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo ROOT_DIR; ?>/orgnization">Orgnizations</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo ROOT_DIR; ?>/CPC">CPC</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo ROOT_DIR; ?>/advising">Advising</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo ROOT_DIR; ?>/career">Career</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo ROOT_DIR; ?>/scholarships">Scholarships</a>
				</li>
			</ul>
			<ul class="nav navbar-nav pull-md-right">
				<li class="navbar-divider hidden-sm-down"></li>
				<li class="dropdown-divider hidden-md-up"></li>
				<?php if (!$this->Site_model->is_login()): ?>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo ROOT_DIR; ?>/user/login?uri=<?php echo $this->Site_model->get_relative_url(); ?>">Sign in</a>
					</li>
				<?php else: ?>
					<li class="nav-item active dropdown">
						<a id="nav-user" class="nav-link dropdown-toggle" data-hover="dropdown" data-delay="100"
						   href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fa fa-user" aria-hidden="true"></i>
							<?php echo $_SESSION['username']; ?>
						</a>
						<div class="dropdown-menu hidden-md-up" aria-labelledby="nav-user">
							<a class="dropdown-item" href="<?php echo ROOT_DIR; ?>/user/settings">Settings</a>
							<a class="dropdown-item" href="#">Notifications</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo ROOT_DIR; ?>/user/logout?uri=<?php echo $this->Site_model->get_relative_url(); ?>">Log out</a>
						</div>
						<div class="dropdown-menu hidden-sm-down dropdown-menu-right" aria-labelledby="nav-user">
							<a class="dropdown-item" href="<?php echo ROOT_DIR; ?>/user/settings">Settings</a>
							<a class="dropdown-item" href="#">Notifications</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?php echo ROOT_DIR; ?>/user/logout?uri=<?php echo $this->Site_model->get_relative_url(); ?>">Log out</a>
						</div>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	
	</div>
</nav>
