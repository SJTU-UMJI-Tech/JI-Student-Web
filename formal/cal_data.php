<?php 
	require_once 'component.php';
	require_once 'function.php';
	$dbc = db_connect();
	echo '[';
	if (isset($_GET['org'])) {
	$query = "SELECT * FROM Events WHERE org_name='".mysqli_real_escape_string($dbc, $_GET['org'])."' AND status='published'";
	$result = mysqli_query($dbc,$query);
			if ($result) {
				while ($one_event = $result -> fetch_array()) {
				  echo "{";
				  echo "\"title\":\"".$one_event['title']."\",";
				  echo "\"start\":\"".$one_event['time']."\",";
				  echo "\"end\":\"".$one_event['end_time']."\",";
				  echo "\"url\":\"./org_events.php?org=".$one_event['org_name']."&event_id=".$one_event['event_id']."\"";
				  echo "},";
				}
			}
	}
	echo "{\"title\":\"0v0\",\"start\":\"1994-03-26 00:00:00\",\"end\":\"1995-01-16 00:21:00\"}]";
?>