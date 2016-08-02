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
	
	<title><?php echo $page_name; ?></title>
	<script src="//cdn.bootcss.com/jquery/3.1.0/jquery.min.js"></script>
	<noscript>Your browser does not support JavaScript!</noscript>
	
	<link rel="shortcut icon" href="/images/favicon.png">
	
	<link rel="stylesheet" type="text/css" href="//cdn.bootcss.com/bootstrap/4.0.0-alpha.3/css/bootstrap.min.css">
	
	<link rel="stylesheet" type="text/css" href="//cdn.bootcss.com/font-awesome/4.6.3/css/font-awesome.min.css">
	
	
	<script src="//cdn.bootcss.com/tether/1.3.3/js/tether.min.js"></script>
	
	<script src="//cdn.bootcss.com/bootstrap/4.0.0-alpha.3/js/bootstrap.min.js"></script>
	
	<link rel="stylesheet" type="text/css" href="/css/common.css">
	
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
		<ul class="nav navbar-nav pull-xs-right">
			<li class="nav-item active">
				<a class="nav-link" href="/user/login?uri=<?php echo $_SERVER["REQUEST_URI"];?>">Sign in</a>
			</li>
		</ul>
	
	
	</div>
</nav>
