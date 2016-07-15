<?php
session_start();
require 'FileDownload.class.php';
require_once 'function.php';
require_once 'component.php';
$dbc = db_connect();
init_dash_web($dbc);

if (isset($_GET['file']) && check_CSRF()) {
    $file = $_GET['file'];
    //$file = mb_convert_encoding($file, "utf-8");
    if(file_exists($GLOBALS['upload_base_dir'].$file) || $GLOBALS['file_exist_override']){
    	$query = "SELECT * FROM Attachments WHERE file_link='".mysqli_real_escape_string($dbc, $file)
    	."' COLLATE utf8_bin";
    	$data = mysqli_query($dbc,$query);
    	dev_echo(mysqli_error($dbc));
		$att_info = $data -> fetch_array();

        if (check_file_permission($dbc, $file)) {
            $obj = new FileDownload(); 
            $flag = $obj->download($GLOBALS['upload_base_dir'].$file, urldecode($att_info['attachment_name']), true); // 断点续传 
        } else {
            echo "[file] permission denied.";
        }
    } else {
    	echo "file not found.";
    }
}
?>