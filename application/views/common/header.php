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
	<link rel="shortcut icon" href="/images/favicon.png">
	<title><?php echo $page_name; ?></title>
	
	<!-- Load the global libraries -->
	<?php if (ENVIRONMENT == 'production'): ?>
		<script src="//cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
		<noscript>Your browser does not support JavaScript!</noscript>
	<link rel="stylesheet" type="text/css" href="//cdn.bootcss.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="//cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.min.css">
		<script src="//cdn.bootcss.com/tether/1.3.3/js/tether.min.js"></script>
		<script src="//cdn.bootcss.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js"></script>
		<script src="//cdn.bootcss.com/bootstrap-hover-dropdown/2.2.1/bootstrap-hover-dropdown.min.js"></script>
	<?php else: ?>
		<script src="/vendors/jquery-3.1.0/dist/jquery.js"></script>
		<noscript>Your browser does not support JavaScript!</noscript>
	<link rel="stylesheet" type="text/css" href="/vendors/bootstrap-4.0.0-alpha.3/dist/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="/vendors/Font-Awesome-4.6.3/css/font-awesome.css">
		<script src="/vendors/tether-1.3.3/dist/js/tether.js"></script>
		<script src="/vendors/bootstrap-4.0.0-alpha.3/dist/js/bootstrap.js"></script>
		<script src="/vendors/bootstrap-hover-dropdown-2.2.1/bootstrap-hover-dropdown.js"></script>
	<?php endif; ?>
	
	
	<link rel="stylesheet" type="text/css" href="/css/common.css">
	<script src="/js/common.js"></script>
	
	<base target="_self">
</head>

<body>

<nav class="navbar navbar-light navbar-fixed-top bg-faded" role="navigation">
	<div class="container">
		<a class="navbar-brand" href="/">UM-SJTU JI Online</a>
		<ul class="nav navbar-nav">
			<li class="nav-item active">
				<a class="nav-link" href="/events">Events</a>
			</li>
		</ul>
		<?php if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == ''): ?>
			<ul class="nav navbar-nav pull-xs-right">
				<li class="nav-item active">
					<a class="nav-link" href="/user/login?uri=<?php echo $_SERVER["REQUEST_URI"]; ?>">Sign in</a>
				</li>
			</ul>
		<?php else: ?>
			<ul class="nav navbar-nav pull-xs-right">
				<li class="nav-item dropdown active">
					<a class="nav-link dropdown-toggle" data-hover="dropdown" data-delay="100"
					   href="#"><?php echo $_SESSION['username']; ?></a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="#">Action</a>
						<a class="dropdown-item" href="#">Another action</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="/user/logout">Log out</a>
					</div>
				</li>
			</ul>
		<?php endif; ?>
	
	
	</div>
</nav>
