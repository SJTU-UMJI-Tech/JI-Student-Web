<?php 
	require_once 'function.php';
	require_once 'settings.php';
	$dbc = db_connect();
	echo mysqli_error($dbc);
	$tables = array('All_users', 'Member', 'Events', 'AC_log', 'HC_log', 'Organization', 'Feedback', 'Admin_users', 'Event_reg', 'Scholarships', 'Scholarship_reg', 'Career', 'Career_reg', 'Attachments');
	foreach ($tables as $key => $value) {
		mysqli_query($dbc, "DROP TABLE ".$value);
		echo mysqli_error($dbc);
	}
	
	/*
	mysqli_query($dbc, "CREATE DATABASE IF NOT EXISTS ACweb_db");
	echo mysqli_error($dbc);
	mysqli_query($dbc, "USE ACweb_db");
	echo mysqli_error($dbc);*/
	//echo "here!";
	$sql = "CREATE TABLE IF NOT EXISTS All_users
	(
	username varchar(20) NOT NULL,
	stuID varchar(15) NOT NULL PRIMARY KEY,
	password varchar(100) NOT NULL,
	email varchar(50) NOT NULL,
	phone varchar(20),
	dob varchar(20)
	)DEFAULT CHARACTER SET utf8";
	mysqli_query($dbc, $sql);
	echo mysqli_error($dbc);
	//echo "here!";
	$sql = "CREATE TABLE IF NOT EXISTS Member 
	(
	stuID varchar(25) NOT NULL,
	description text,
	photo_link tinytext,
	belong_org varchar(100) NOT NULL,
	title varchar(100),
	office_hour_time varchar(100),
	office_hour_location varchar(100),
	status varchar(50) NOT NULL,
	PRIMARY KEY (stuID, belong_org)
	)DEFAULT CHARACTER SET utf8";
	if(mysqli_query($dbc, $sql))
	{
		echo "<br/>Member table created<br><hr>";
	}
	else
	{
		echo "Error creating table: " . mysqli_error($dbc);
	}

	$sql = "CREATE TABLE IF NOT EXISTS Events 
	(
	event_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
	title varchar(100) NOT NULL,
	time varchar(50) NOT NULL,
	end_time varchar(50) NOT NULL,
	location varchar(100) NOT NULL,
	org_name varchar(100) NOT NULL,
	manager varchar(20),
	description text,
	short_description text,
	photo_link tinytext,
	tag tinytext,
	reg_link tinytext,
	contact_email varchar(50),
	speaker varchar(30),
	budget double,
	budget_file_link tinytext,
	status varchar(20) NOT NULL
	)DEFAULT CHARACTER SET utf8";
	if(mysqli_query($dbc, $sql))
	{
		echo "<br/>Events table created<br><hr>";
	}
	else
	{
		echo "Error creating table: " . mysqli_error($dbc);
	}

	$sql = "CREATE TABLE IF NOT EXISTS AC_log 
	(
	stuID varchar(15),
	time varchar(20),
	log_content text,
	photo_link tinytext,
	tag tinytext
	)DEFAULT CHARACTER SET utf8";
	if(mysqli_query($dbc, $sql))
	{
		echo "<br/>AC_log table created<br><hr>";
	}
	else
	{
		echo "Error creating table: " . mysqli_error($dbc);
	}

	$sql = "CREATE TABLE IF NOT EXISTS HC_log 
	(
	stuID varchar(15),
	time varchar(20),
	log_content text,
	photo_link tinytext,
	tag tinytext,
	case_name varchar(100),
	case_id varchar(30),
	guilty_level varchar(20)
	)DEFAULT CHARACTER SET utf8";
	if(mysqli_query($dbc, $sql))
	{
		echo "<br/>HC_log table created<br><hr>";
	}
	else
	{
		echo "Error creating table: " . mysqli_error($dbc);
	}

	$sql = "CREATE TABLE IF NOT EXISTS Organization 
	(
	description text,
	short_description tinytext,
	photo_link tinytext,
	org_name varchar(100) NOT NULL PRIMARY KEY,
	tag tinytext
	)DEFAULT CHARACTER SET utf8";
	if(mysqli_query($dbc, $sql))
	{
		echo "<br/>Organization table created<br><hr>";
	}
	else
	{
		echo "Error creating table: " . mysqli_error($dbc);
	}

	$sql = "CREATE TABLE IF NOT EXISTS Feedback 
	(
	ticket_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
	from_username varchar(20) NOT NULL,
	from_stuID varchar(15) NOT NULL,
	time varchar(20) NOT NULL,
	from_email varchar(50) NOT NULL,
	to_org varchar(100) NOT NULL,
	replied_with text,
	content text NOT NULL,
	status varchar(15)
	)DEFAULT CHARACTER SET utf8";
	if(mysqli_query($dbc, $sql))
	{
		echo "<br/>Feedback table created<br><hr>";
	}
	else
	{
		echo "Error creating table: " . mysqli_error($dbc);
	}

	$sql = "CREATE TABLE IF NOT EXISTS Admin_users 
	(
	boss_stuID varchar(15) NOT NULL,
	org_name varchar(100) NOT NULL,
	PRIMARY KEY (boss_stuID, org_name)
	)DEFAULT CHARACTER SET utf8";
	if(mysqli_query($dbc, $sql))
	{
		echo "<br/>Admin_users table created<br><hr>";
	}
	else
	{
		echo "Error creating table: " . mysqli_error($dbc);
	}

	$sql = "CREATE TABLE IF NOT EXISTS Event_reg 
	(
	event_id INT NOT NULL,
	name varchar(30) NOT NULL,
	userID varchar(30) NOT NULL,
	phone varchar(20),
	email varchar(100),
	PRIMARY KEY (event_id, userID)
	)DEFAULT CHARACTER SET utf8";
	if(mysqli_query($dbc, $sql))
	{
		echo "<br/>Event_reg table created<br><hr>";
	}
	else
	{
		echo "Error creating table: " . mysqli_error($dbc);
	}

	$sql = "CREATE TABLE IF NOT EXISTS Scholarships 
	(
	scholarship_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
	description text,
	title varchar(100) NOT NULL,
	category varchar(50) NOT NULL,
	mod_time varchar(20) NOT NULL,
	deadline varchar(20) NOT NULL,
	ammount varchar(50),
	contact_email varchar(50) NOT NULL,
	requirements text,
	status varchar(50) NOT NULL
	)DEFAULT CHARACTER SET utf8";
	if(mysqli_query($dbc, $sql))
	{
		echo "<br/>Scholarships table created<br><hr>";
	}
	else
	{
		echo "Error creating table: " . mysqli_error($dbc);
	}

	$sql = "CREATE TABLE IF NOT EXISTS Scholarship_reg 
	(
	scholarship_id INT NOT NULL,
	username varchar(20) NOT NULL,
	stuID varchar(15) NOT NULL,
	time varchar(20) NOT NULL,
	phone varchar(20),
	email varchar(50) NOT NULL,
	status varchar(15),
	PRIMARY KEY(scholarship_id, stuID)
	)DEFAULT CHARACTER SET utf8";
	if(mysqli_query($dbc, $sql))
	{
		echo "<br/>Scholarship_reg table created<br><hr>";
	}
	else
	{
		echo "Error creating table: " . mysqli_error($dbc);
	}


	$sql = "CREATE TABLE IF NOT EXISTS Career 
	(
	career_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
	description text,
	title varchar(100) NOT NULL,
	category varchar(50) NOT NULL,
	mod_time varchar(20) NOT NULL,
	deadline varchar(20),
	company varchar(150),
	contact_email varchar(50) NOT NULL,
	requirements text,
	status varchar(50) NOT NULL
	)DEFAULT CHARACTER SET utf8";
	if(mysqli_query($dbc, $sql))
	{
		echo "<br/>Career table created<br><hr>";
	}
	else
	{
		echo "Error creating table: " . mysqli_error($dbc);
	}

	$sql = "CREATE TABLE IF NOT EXISTS Career_reg 
	(
	career_id INT NOT NULL,
	username varchar(20) NOT NULL,
	stuID varchar(15) NOT NULL,
	time varchar(20) NOT NULL,
	phone varchar(20),
	email varchar(50) NOT NULL,
	status varchar(15),
	PRIMARY KEY(career_id, stuID)
	)DEFAULT CHARACTER SET utf8";
	if(mysqli_query($dbc, $sql))
	{
		echo "<br/>Career_reg table created<br><hr>";
	}
	else
	{
		echo "Error creating table: " . mysqli_error($dbc);
	}

	$sql = "CREATE TABLE IF NOT EXISTS Attachments 
	(
	key_id INT,
	table_name varchar(50) NOT NULL,
	attachment_name varchar(500) NOT NULL,
	upload_by_user_id varchar(30) NOT NULL,
	file_link varchar(150) NOT NULL PRIMARY KEY,
	mod_time varchar(20) NOT NULL,
	permission varchar(10) NOT NULL"	// login OR org
	.")DEFAULT CHARACTER SET utf8";
	if(mysqli_query($dbc, $sql))
	{
		echo "<br/>Attachments table created<br><hr>";
	}
	else
	{
		echo "Error creating table: " . mysqli_error($dbc);
	}

	/*for ($k=180; $k < 190; $k++) { 
		$var = sprintf("INSERT INTO All_users values('Frank Tester','5123709%u','123456','asdasd@asd.ccc','+86 88888','09/04/2015')",$k);
		mysqli_query($dbc, $var);
		echo mysqli_error($dbc);*/
		foreach ($GLOBALS['all_orgs_developer'] as $key) {
			$var = sprintf("INSERT INTO Member values('5123709184','','','%s','member','', '', 'show')",$key);
			mysqli_query($dbc, $var);
			echo mysqli_error($dbc);
		}/*
		$var = sprintf("INSERT INTO Event_reg values(1,'Some One','5123709%u', '+86 1236547','email@email.com')", $k);
		mysqli_query($dbc, $var);
		echo mysqli_error($dbc);
	}*/
	foreach ($GLOBALS['all_orgs_developer'] as $key) {
			$var = sprintf("INSERT INTO Organization values('','','','%s','')",$key);
			mysqli_query($dbc, $var);
			echo mysqli_error($dbc);
			$var = sprintf("INSERT INTO Admin_users values('5123709184', '%s')",$key);
			mysqli_query($dbc, $var);
			echo mysqli_error($dbc);
			//$var = sprintf("INSERT INTO Events values(NULL,'event title', '2016-02-02 08:20:00','2016-02-02 12:10:00' ,'location','%s','Pkkk','description description description description description description description description description description description description description description description description description description description description description description description description description description description description','short_description','','tag','reg_link','contact_email@email.com','speaker',600,'')",$key);
			//mysqli_query($dbc, $var);
			//echo mysqli_error($dbc);
		}
	
	show_table($dbc, 'All_users', 4);

	function show_table($db_connection, $table_name, $num_elements)
	{
		$query = "SELECT * FROM " . $table_name;
		$result=$db_connection->query($query);
		if ($result) {
			if($result->num_rows>0){                                               //判断结果集中行的数目是否大于0
				while($row =$result->fetch_array() ){                        //循环输出结果集中的记录
					for ($i=0; $i < $num_elements; $i++) { 
						echo ($row[$i])."<br>";
					}
					echo "<hr>";
				}
			}
		}else {
		         echo "query failed";
		}
	}
	
	$dbc->close(); 
 ?>
