<?php

	$using_data_for = "server_online";

	$GLOBALS['all_orgs'] = array('Career', 'Scholarships', 'Advising', 'CPC', 'Student Union');
	$GLOBALS['all_orgs_developer'] = array('Career', 'Scholarships', 'Advising', 'CPC', 'Student Union', 'Developers');
	$GLOBALS['ele_per_page'] = 20;
	$GLOBALS['DEV'] = true;
	$GLOBALS['360_check'] = false;
	$GLOBALS['DB_info'] =	array('addr' => "", 
								'user' => "", 
								'password' => "", 
								'use_db' => "");
	$GLOBALS['allowed_tags'] = array('b', 'p', 'strong', 'em', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');
	$GLOBALS['file_exist_override'] = true;
	$GLOBALS['upload_base_dir'] = "/opt/upload/student/";

	if ($using_data_for == "local") {
		$GLOBALS['redirect_uri'] = 'http://127.0.0.1/dev/login.php';
		$GLOBALS['upload_base_dir'] = "./upload";
	}
	elseif ($using_data_for == "win") {
		$GLOBALS['redirect_uri'] = 'http://192.168.138.128/dev/login.php';
	}
	elseif ($using_data_for == "server_check") {
		$GLOBALS['redirect_uri'] = 'http://www.umji.online/dev/login.php';
		$GLOBALS['360_check'] = true;
		$GLOBALS['DEV'] = false;
	}
	elseif ($using_data_for == "server_check_full") {
		$GLOBALS['redirect_uri'] = 'http://www.umji.online/dev/login.php';
		$GLOBALS['360_check'] = true;
		$GLOBALS['DEV'] = false;
		$_SESSION["user_id"] = "5123709181";
	}
	elseif ($using_data_for == "server") {
		$GLOBALS['redirect_uri'] = 'http://ji.sjtu.edu.cn/student/login.php';
		$GLOBALS['DEV'] = true;
	}
	elseif ($using_data_for == "server_online") {
		$GLOBALS['redirect_uri'] = 'http://ji.sjtu.edu.cn/student/login.php';
		$GLOBALS['DEV'] = false;
	}
?>