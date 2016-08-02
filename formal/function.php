<?php

require_once 'settings.php';

    if ($GLOBALS['DEV']) {
        ini_set('display_errors',1);            //错误信息
        ini_set('display_startup_errors',1);    //php启动错误信息
        error_reporting(-1);                    //打印出所有的 错误信息
    }
        
    function check_CSRF()
    {
        return (isset($_POST["CSRFcheck"]) && $_POST["CSRFcheck"] == $_SESSION["CSRFcheck"]) || (isset($_GET["CSRFcheck"]) && $_GET["CSRFcheck"] == $_SESSION["CSRFcheck"]);
    }

    function check_by_360()
    {
        if ($GLOBALS['360_check']) {
            echo ' | <a href="http://webscan.360.cn/index/checkwebsite/url/www.umji.online"><img border="0" src="http://img.webscan.360.cn/status/pai/hash/63323c6ab070a7b4d2104b09fa86487b"/></a>';
        }   
    }

	function init_web($dbc)
	{
        clean_xss($_GET);
        clean_xss($_POST);
		if (!isset($_SESSION['CSRFcheck'])) {
			$CSRFcheck = md5(uniqid(rand(), true));
			$_SESSION['CSRFcheck'] = $CSRFcheck;
		}
		if ($GLOBALS['DEV']) {
			ini_set('display_errors',1);            //错误信息
			ini_set('display_startup_errors',1);    //php启动错误信息
			error_reporting(-1);                    //打印出所有的 错误信息
		}
		if(!isset($_SESSION["user_id"])){
		    if(isset($_COOKIE["user_id"])){
		        //用cookie给session赋值
		        /*$_SESSION["user_id"]=$_COOKIE["user_id"];
		        $query = "SELECT * FROM All_users WHERE stuID=\"".mysqli_real_escape_string($dbc, $_SESSION["user_id"])."\" COLLATE utf8_bin ";
	        	$data = mysqli_query($dbc,$query);
	        	$result = $data->fetch_array();
	        	$_SESSION["username"]=$result['username'];
	        	$_SESSION["useremail"]=$result['email'];
                $_SESSION["userphone"]=$result['phone'];*/
		    }
		} else {
			$query = "SELECT * FROM All_users WHERE stuID=\"".mysqli_real_escape_string($dbc, $_SESSION["user_id"])."\" COLLATE utf8_bin ";
        	$data = mysqli_query($dbc,$query);
        	$result = $data->fetch_array();
        	$_SESSION["username"]=$result['username'];
        	$_SESSION["useremail"]=$result['email'];
            $_SESSION["userphone"]=$result['phone'];
		}
	}

	function init_dash_web($dbc)
	{
		//var_dump($_POST);
        clean_xss($_GET);
        clean_xss($_POST);
        //var_dump($_POST);
		if (!isset($_SESSION['CSRFcheck'])) {
			$CSRFcheck = md5(uniqid(rand(), true));
			$_SESSION['CSRFcheck'] = $CSRFcheck;
		}
		if ($GLOBALS['DEV']) {
			ini_set('display_errors',1);            //错误信息
			ini_set('display_startup_errors',1);    //php启动错误信息
			error_reporting(-1);                    //打印出所有的 错误信息
		}
		if(!isset($_SESSION["user_id"])){
		    if(isset($_COOKIE["user_id"])){
		        //用cookie给session赋值
		        /*$_SESSION["user_id"]=$_COOKIE["user_id"];
                $query = "SELECT * FROM All_users WHERE stuID=\"".mysqli_real_escape_string($dbc, $_SESSION["user_id"])."\" COLLATE utf8_bin ";
                $data = mysqli_query($dbc,$query);
                $result = $data->fetch_array();
                $_SESSION["username"]=$result['username'];
                $_SESSION["useremail"]=$result['email'];
                $_SESSION["userphone"]=$result['phone'];*/
		    } else {
		        $home_url = "login.php";
		        header("Location:".$home_url);
		        return;
		    }
		} else {
            $query = "SELECT * FROM All_users WHERE stuID=\"".mysqli_real_escape_string($dbc, $_SESSION["user_id"])."\" COLLATE utf8_bin ";
            $data = mysqli_query($dbc,$query);
            $result = $data->fetch_array();
            $_SESSION["username"]=$result['username'];
            $_SESSION["useremail"]=$result['email'];
            $_SESSION["userphone"]=$result['phone'];
        }
	}

	function db_connect()
	{
		$dbc = mysqli_connect($GLOBALS['DB_info']['addr'], $GLOBALS['DB_info']['user'],
            $GLOBALS['DB_info']['password'], $GLOBALS['DB_info']['use_db']);
        echo mysqli_error($dbc);
        $dbc->set_charset("utf8");
        return $dbc;
	}

	function resizeImage($im,$maxwidth,$maxheight,$name,$filetype)
	{
		$pic_width = imagesx($im);
		$pic_height = imagesy($im);

		if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
		{
		if($maxwidth && $pic_width>$maxwidth)
		{
		$widthratio = $maxwidth/$pic_width;
		$resizewidth_tag = true;
		}

		if($maxheight && $pic_height>$maxheight)
		{
		$heightratio = $maxheight/$pic_height;
		$resizeheight_tag = true;
		}

		if($resizewidth_tag && $resizeheight_tag)
		{
		if($widthratio<$heightratio)
		$ratio = $widthratio;
		else
		$ratio = $heightratio;
		}

		if($resizewidth_tag && !$resizeheight_tag)
		$ratio = $widthratio;
		if($resizeheight_tag && !$resizewidth_tag)
		$ratio = $heightratio;

		$newwidth = $pic_width * $ratio;
		$newheight = $pic_height * $ratio;

		if(function_exists("imagecopyresampled"))
		{
		$newim = imagecreatetruecolor($newwidth,$newheight);//PHP系统函数
		imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);//PHP系统函数
		}
		else
		{
		$newim = imagecreate($newwidth,$newheight);
		imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
		}

		$name = $name.$filetype;
		imagejpeg($newim,$name);
		imagedestroy($newim);
		}
		else
		{
		$name = $name.$filetype;
		imagejpeg($im,$name);
		}
	}
	//使用方法：
	/*
	$im=imagecreatefromjpeg("./20140416103023202.jpg");//参数是图片的存方路径
	$maxwidth="600";//设置图片的最大宽度
	$maxheight="400";//设置图片的最大高度
	$name="123";//图片的名称，随便取吧
	$filetype=".jpg";//图片类型
	resizeImage($im,$maxwidth,$maxheight,$name,$filetype);//调用上面的函数
	*/

	// upload photo
    function get_photo_addr($post_key='', $prefix='', $my_file_id='')
    {
        if (!empty($_FILES[$post_key]["tmp_name"])) 
        {
            if ((($_FILES[$post_key]["type"] == "image/jpeg") 
            || ($_FILES[$post_key]["type"] == "image/pjpeg")) 
            && ($_FILES[$post_key]["size"] < 5000000)) 
            { 
            if ($_FILES[$post_key]["error"] > 0) 
            { 
                echo "Return Code: " . $_FILES[$post_key]["error"] . "<br />"; 
            } 
            else 
                { 
                //echo "Upload: " . $_FILES[$post_key]["name"] . "<br />"; 
                //echo "Type: " . $_FILES[$post_key]["type"] . "<br />"; 
                //echo "Size: " . ($_FILES[$post_key]["size"] / 1024) . " Kb<br />"; 
                //echo "Temp file: " . $_FILES[$post_key]["tmp_name"] . "<br />"; 
                if (file_exists("upload/" . $prefix.$my_file_id.".jpg"))
                    { 
                    unlink("upload/" . $prefix.$my_file_id.".jpg"); 
                    } 
                    move_uploaded_file($_FILES[$post_key]["tmp_name"], 
                    "upload/" . $prefix.$my_file_id."E.jpg");
                    $im=imagecreatefromjpeg("upload/" . $prefix.$my_file_id."E.jpg");//参数是图片的存方路径
                    $maxwidth="600";//设置图片的最大宽度
                    $maxheight="600";//设置图片的最大高度
                    $name="upload/" . $prefix.$my_file_id;//图片的名称，随便取吧
                    $filetype=".jpg";//图片类型
                    resizeImage($im,$maxwidth,$maxheight,$name,$filetype);//调用上面的函数
                    //echo "Stored in: " . "upload/" . $prefix.$my_file_id.".jpg";
                    unlink("upload/" . $prefix.$my_file_id."E.jpg");  
                }
                $photo_addr["content"] = "upload/" . $prefix.$my_file_id.".jpg";
                $photo_addr["error"]="";
                $photo_addr["new_file"]=true;
            } 
            else 
            { 
                echo "Invalid file";
                $photo_addr["new_file"]=fales;
            	$photo_addr["content"] = "";
                $photo_addr["error"]="Upload fail, please check file type and make sure it is within the size limit.";
            }
        }else{
            $photo_addr["content"] = "";
            $photo_addr["error"]="";
            $photo_addr["new_file"]=false;
        }
        return $photo_addr;
    }

    // upload file
    function get_file_addr($post_key='', $prefix='', $my_file_id='', $size_limit=5000000, $flag=false)
    {	
        if (!empty($_FILES[$post_key]["tmp_name"])) 
        {
            if ((true || $_FILES[$post_key]["type"] == 'application/pdf' || 
                $_FILES[$post_key]["type"] == 'application/vnd.ms-excel' || 
                $_FILES[$post_key]["type"] == 'text/plain' || 
                $_FILES[$post_key]["type"] == 'application/msword' ||
                $_FILES[$post_key]["type"] == "image/pjpeg" || 
                $_FILES[$post_key]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" ||
                $_FILES[$post_key]["type"] == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' ||
                $_FILES[$post_key]["type"] == 'application/vnd.ms-excel.sheet.macroEnabled.12') 
            && ($_FILES[$post_key]["size"] < $size_limit)) 
            { 
                if ($_FILES[$post_key]["error"] > 0) 
                { 
                    dev_echo( "Return Code: " . $_FILES[$post_key]["error"] . "<br />"); 
                } 
                else 
                { 
                    dev_echo( "Upload: " . $_FILES[$post_key]["name"] . "<br />"); 
                    dev_echo( "Type: " . $_FILES[$post_key]["type"] . "<br />"); 
                    dev_echo( "Size: " . ($_FILES[$post_key]["size"] / 1024) . " Kb<br />"); 
                    dev_echo( "Temp file: " . $_FILES[$post_key]["tmp_name"] . "<br />");
                    $ext = ".".pathinfo($_FILES[$post_key]["name"], PATHINFO_EXTENSION);
                    if ($flag == false) {
                        $addr = $prefix.'_'.$my_file_id.'_'.$_SESSION['user_id'].'_'.time().'.file';//.$_FILES[$post_key]["name"];//$ext;
                    } else {
                        $addr = $prefix.'_'.$my_file_id.'_'.time().'.file';//$_FILES[$post_key]["name"];//$ext;
                    }
                    if (file_exists($addr))
                    { 
                        unlink($addr); 
                    }
                    $r = move_uploaded_file($_FILES[$post_key]["tmp_name"], $GLOBALS['upload_base_dir'].$addr);
                    dev_echo($r);
                    dev_echo( "Stored in: " .$GLOBALS['upload_base_dir']. $addr);
                }
                $file_addr["content"] = $addr;
                $file_addr["error"]="";
                $file_addr["new_file"]=true;
                $file_addr["name"]=urlencode($_FILES[$post_key]["name"]);//mb_convert_encoding($_FILES[$post_key]["name"], 'GBK');
                //var_dump(($_FILES[$post_key]));
            } 
            else 
            { 
                dev_echo("Invalid file"); 
                $file_addr["content"] = "";
                $file_addr["new_file"]=false;
                $file_addr["error"]="Upload fail, please check file type and make sure it is within the size limit.";
            }
        }else{
            $file_addr["content"] = "";
            $file_addr["error"]="";
            $file_addr["new_file"]=false;
        }
        return $file_addr;
    }


    function request_post($url = '', $post_data = array()) {
        if (empty($url) || empty($post_data)) {
            return false;
        }
        
        $o = "";
        foreach ( $post_data as $k => $v ) 
        { 
            $o.= $k."=" .$v. "&" ;
        }
        $post_data = substr($o,0,-1);

        $postUrl = $url;
        $curlPost = $post_data;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL, $postUrl); // 要访问的地址
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($ch, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost); // Post提交的数据包
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        
        $data = curl_exec($ch);//运行curl
        if (curl_errno($ch)) {
           echo 'Errno'.curl_error($ch);//捕抓异常
        }
        curl_close($ch);
        return $data;
    }

    function reg_event($dbc)
    {
        $reg_event_err_msg = "";
        $query = "SELECT * FROM Event_reg WHERE userID='".mysqli_real_escape_string($dbc, $_SESSION['user_id'])
        ."' COLLATE utf8_bin AND event_id='".mysqli_real_escape_string($dbc, $_POST['event_id'])."'";
        $result = mysqli_query($dbc,$query);
        //echo $query;
        echo mysqli_error($dbc);
        if ($result) {
            if ($result->num_rows > 0) {
                $reg_event_err_msg = "You are already registed.";
            } else {
                $query = "INSERT INTO Event_reg SET userID='".mysqli_real_escape_string($dbc, $_SESSION['user_id'])
                ."', event_id='".mysqli_real_escape_string($dbc, $_POST['event_id'])
                ."', name='".mysqli_real_escape_string($dbc, $_SESSION['username'])
                ."', phone='".mysqli_real_escape_string($dbc, $_POST['phone'])
                ."', email='".mysqli_real_escape_string($dbc, $_POST['email'])."'";
                $result = mysqli_query($dbc,$query);
                //echo $query;
                echo mysqli_error($dbc);

                $query = "UPDATE All_users SET email=\"". mysqli_real_escape_string($dbc, $_POST["email"])
                ."\", phone=\"". mysqli_real_escape_string($dbc, $_POST["phone"])
                ."\" WHERE stuID=\"".$_SESSION["user_id"]."\"";
                $data = mysqli_query($dbc,$query);
                $_SESSION["useremail"] = mysqli_real_escape_string($dbc, $_POST["email"]);
                $_SESSION["userphone"] = mysqli_real_escape_string($dbc, $_POST["phone"]);
                //echo $query;
                echo mysqli_error($dbc);
            }
        } else {
            $reg_event_err_msg = "Database query failed.";
        }
        return $reg_event_err_msg;
    }

    function reg_scholarship($dbc)
    {
        $reg_event_err_msg = "";
        $query = "SELECT * FROM Scholarship_reg WHERE stuID='".mysqli_real_escape_string($dbc, $_SESSION['user_id'])
        ."' COLLATE utf8_bin AND scholarship_id='".mysqli_real_escape_string($dbc, $_POST['scholarship_id'])."'";
        $result = mysqli_query($dbc,$query);
        //echo $query;
        echo mysqli_error($dbc);
        if ($result) {
            if ($result->num_rows > 0) {
                $reg_event_err_msg = "You are already registed.";
            } else {
                $query = "INSERT INTO Scholarship_reg SET stuID='".mysqli_real_escape_string($dbc, $_SESSION['user_id'])
                ."', scholarship_id='".mysqli_real_escape_string($dbc, $_POST['scholarship_id'])
                ."', username='".mysqli_real_escape_string($dbc, $_SESSION['username'])
                ."', phone='".mysqli_real_escape_string($dbc, $_POST['phone'])
                ."', email='".mysqli_real_escape_string($dbc, $_POST['email'])."'";
                $result = mysqli_query($dbc,$query);
                //echo $query;
                echo mysqli_error($dbc);

                $query = "UPDATE All_users SET email=\"". mysqli_real_escape_string($dbc, $_POST["email"])
                ."\", phone=\"". mysqli_real_escape_string($dbc, $_POST["phone"])
                ."\" WHERE stuID=\"".$_SESSION["user_id"]."\"";
                $data = mysqli_query($dbc,$query);
                $_SESSION["useremail"] = mysqli_real_escape_string($dbc, $_POST["email"]);
                $_SESSION["userphone"] = mysqli_real_escape_string($dbc, $_POST["phone"]);
                //echo $query;
                echo mysqli_error($dbc);
            }
        } else {
            $reg_event_err_msg = "Database query failed.";
        }
        return $reg_event_err_msg;
    }

    function reg_career($dbc)
    {
        $reg_event_err_msg = "";
        $query = "SELECT * FROM Career_reg WHERE stuID='".mysqli_real_escape_string($dbc, $_SESSION['user_id'])
        ."' COLLATE utf8_bin AND career_id='".mysqli_real_escape_string($dbc, $_POST['career_id'])."'";
        $result = mysqli_query($dbc,$query);
        //echo $query;
        echo mysqli_error($dbc);
        if ($result) {
            if ($result->num_rows > 0) {
                $reg_event_err_msg = "You are already registed.";
            } else {
                $query = "INSERT INTO Career_reg SET stuID='".mysqli_real_escape_string($dbc, $_SESSION['user_id'])
                ."', career_id='".mysqli_real_escape_string($dbc, $_POST['career_id'])
                ."', username='".mysqli_real_escape_string($dbc, $_SESSION['username'])
                ."', phone='".mysqli_real_escape_string($dbc, $_POST['phone'])
                ."', email='".mysqli_real_escape_string($dbc, $_POST['email'])."'";
                $result = mysqli_query($dbc,$query);
                //echo $query;
                echo mysqli_error($dbc);

                $query = "UPDATE All_users SET email=\"". mysqli_real_escape_string($dbc, $_POST["email"])
                ."\", phone=\"". mysqli_real_escape_string($dbc, $_POST["phone"])
                ."\" WHERE stuID=\"".$_SESSION["user_id"]."\"";
                $data = mysqli_query($dbc,$query);
                $_SESSION["useremail"] = mysqli_real_escape_string($dbc, $_POST["email"]);
                $_SESSION["userphone"] = mysqli_real_escape_string($dbc, $_POST["phone"]);
                //echo $query;
                echo mysqli_error($dbc);
            }
        } else {
            $reg_event_err_msg = "Database query failed.";
        }
        return $reg_event_err_msg;
    }

    function check_permission($dbc, $check_org)
    {
        $query = "SELECT * FROM Admin_users WHERE boss_stuID=\"".mysqli_real_escape_string($dbc, $_SESSION["user_id"])
        ."\" COLLATE utf8_bin AND org_name ='".mysqli_real_escape_string($dbc, $check_org)."' COLLATE utf8_bin ";
        $data = mysqli_query($dbc,$query);
        //echo $query;
        echo mysqli_error($dbc);
        if ($data) {
            if ($data -> num_rows > 0) {
                return true;
            }
        }
        return false;
    }

    function check_permission_org($dbc, $check_org)
    {
        $query = "SELECT * FROM Member WHERE stuID=\"".mysqli_real_escape_string($dbc, $_SESSION["user_id"])
        ."\" COLLATE utf8_bin AND belong_org='".mysqli_real_escape_string($dbc, $check_org)."' COLLATE utf8_bin";
        $data = mysqli_query($dbc,$query);
        //echo $query;
        echo mysqli_error($dbc);
        if ($data) {
            if ($data -> num_rows > 0) {
                return true;
            }
        }
        return false;
    }

    function dev_echo($value='')
    {
        if ($GLOBALS['DEV'] == true && $value != "") {
            echo "<p>".$value."</p>";
        }
    }

    function update_att_DB($dbc, $table_name, $attach_key_id, $permission, $is_public)
    {
        $attachment_info = get_file_addr('attachment', $table_name, $attach_key_id, 5000000, $is_public);
        if ($attachment_info['new_file']) {
            $query = "SELECT * FROM Attachments WHERE file_link='". mysqli_real_escape_string($dbc, $attachment_info['content'])
            ."' COLLATE utf8_bin";
            //$query = "SELECT * FROM Attachments";
            $data = mysqli_query($dbc, $query);
            dev_echo(mysqli_error($dbc));
            if ($data) {
                if ($data -> num_rows > 0) {
                    // update old file
                    $new_query = "UPDATE Attachments SET mod_time='".mysqli_real_escape_string($dbc, strftime("%Y-%m-%d %X", time()))
                    ."' WHERE file_link='".mysqli_real_escape_string($dbc, $attachment_info['content'])
                    ."' COLLATE utf8_bin";
                    mysqli_query($dbc, $new_query);
                    dev_echo(mysqli_error($dbc));
                } else {
                    // upload new file
                    dev_echo("new file");
                    $new_query = "INSERT INTO Attachments SET mod_time='".mysqli_real_escape_string($dbc, strftime("%Y-%m-%d %X", time()))
                    ."', file_link='".mysqli_real_escape_string($dbc, $attachment_info['content'])
                    ."', key_id=".mysqli_real_escape_string($dbc, $attach_key_id)
                    .", table_name='".mysqli_real_escape_string($dbc, $table_name)
                    ."', upload_by_user_id='".mysqli_real_escape_string($dbc, $_SESSION['user_id'])
                    ."', attachment_name='".mysqli_real_escape_string($dbc, $attachment_info["name"])
                    ."', permission='".mysqli_real_escape_string($dbc, $permission)
                    ."'";
                    mysqli_query($dbc, $new_query);
                    dev_echo(mysqli_error($dbc));
                }
                return "";
            }
        } else {
            return $attachment_info['error'];
        }
    }

    function del_file($dbc, $file_link='')
    {
        if ($file_link == "") {
            exit();
        }
        check_file_permission($dbc, $file_link);

        if (file_exists($GLOBALS['upload_base_dir'].$file_link))
        { 
            unlink($GLOBALS['upload_base_dir'].$file_link); 
        } 
        $new_query = "DELETE FROM Attachments WHERE file_link='".mysqli_real_escape_string($dbc, $file_link)
        ."' COLLATE utf8_bin";
        mysqli_query($dbc, $new_query);
        dev_echo(mysqli_error($dbc));
    }

    function check_file_permission($dbc, $file='')
    {
    	if(file_exists($GLOBALS['upload_base_dir'].$file) || $GLOBALS['file_exist_override']){
	    	$query = "SELECT * FROM Attachments WHERE file_link='".mysqli_real_escape_string($dbc, $file)
	    	."' COLLATE utf8_bin";
            //dev_echo($query);
	    	$data = mysqli_query($dbc,$query);
	    	dev_echo(mysqli_error($dbc));
			$att_info = $data -> fetch_array();

            $return_val = false;
            if ($att_info['table_name'] == "Events") {
                $query = "SELECT * FROM Events WHERE event_id='".$att_info['key_id']
                ."' COLLATE utf8_bin";
                //dev_echo($query);
                $data = mysqli_query($dbc,$query);
                dev_echo(mysqli_error($dbc));
                $event_info = $data -> fetch_array();

                $query = "SELECT * FROM Member WHERE belong_org='".$event_info['org_name']
                ."' COLLATE utf8_bin AND stuID='".$_SESSION['user_id']."' COLLATE utf8_bin";
                //dev_echo($query);
                $data = mysqli_query($dbc,$query);
                dev_echo(mysqli_error($dbc));
                if ($data->num_rows > 0) {
                    $return_val = $att_info['permission'] == 'login';
                }
            } else {
                $query = "SELECT * FROM Member WHERE belong_org='".$att_info['table_name']
                ."' COLLATE utf8_bin AND stuID='".$_SESSION['user_id']."' COLLATE utf8_bin";
                //dev_echo($query);
                $data = mysqli_query($dbc,$query);
                dev_echo(mysqli_error($dbc));
                if ($data->num_rows > 0) {
                    $return_val = $att_info['permission'] == 'login';
                }
            }
            
	        return $return_val || ($att_info['permission'] == 'org' && $att_info['upload_by_user_id'] == $_SESSION['user_id']);

	    }
    }

    function handle_feedback($dbc, $org_name)
    {

		function check_field()
		{
		  return !empty($_POST['email']) && !empty($_POST['content']);
		}
    	if (isset($_POST['feedback']) && check_CSRF()) {
		    if (check_field() == true) {
		      $query = "INSERT INTO Feedback SET from_stuID='".mysqli_real_escape_string($dbc, $_SESSION['user_id'])
		          ."', from_username='".mysqli_real_escape_string($dbc, $_SESSION['username'])
		          ."', to_org='".mysqli_real_escape_string($dbc, $org_name)
		          ."', status='".mysqli_real_escape_string($dbc, "not replied")
		          ."', content='".mysqli_real_escape_string($dbc, str_replace("\n","</br>",$_POST['content']))
		          ."', time='".mysqli_real_escape_string($dbc, strftime("%Y-%m-%d %X", time()))
		          ."', from_email='".mysqli_real_escape_string($dbc, $_POST['email'])."'";
		          $result = mysqli_query($dbc,$query);
		          //echo $query;
		          echo mysqli_error($dbc);

		          //construct email
		          //php send email
		          //send_one_email('notification', $feedback_info['from_email'], "Re:Feedback ticket ID No.".$feedback_info['ticket_id'], $email_content);

		    } else {
		      return "Information insufficient.";
		    }
		  return "";
		}
		return "";
    }

    function clean_xss(&$string, $low = False)
    {
        if (! is_array ( $string ))
        {
        	$string = htmlspecialchars_decode($string);
        	foreach ($GLOBALS['allowed_tags'] as $key => $value) {
        		$string = str_replace ('</'.$value.'>', '#$#$##'.$value.'#$##', $string );
        		$string = str_replace ('<'.$value.'>', '#$$#'.$value.'#$$#', $string );
        	}
            $allowed_marks = array('nbsp', 'amp', 'lt', 'gt');
            foreach ($allowed_marks as $key => $value) {
                $string = str_replace ('&'.$value.';', '#$$$'.$value.'#$$#', $string );
            }
            $string = trim ( $string );
            $string = strip_tags ( $string );
            $string = htmlspecialchars ( $string );
            if ($low)
            {
                return True;
            }
            $string = str_replace ( array ('"', "\\", "'", "..", "../", "./" /* , "/", "//" */ ), '', $string );
            $no = '/%0[0-8bcef]/';
            $string = preg_replace ( $no, '', $string );
            $no = '/%1[0-9a-f]/';
            $string = preg_replace ( $no, '', $string );
            $no = '/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S';
            $string = preg_replace ( $no, '', $string );
            foreach ($allowed_marks as $key => $value) {
                $string = str_replace ('#$$$'.$value.'#$$#', '&'.$value.';', $string );
            }
            foreach ($GLOBALS['allowed_tags'] as $key => $value) {
        		$string = str_replace ('#$$#'.$value.'#$$#', '<'.$value.'>', $string );
        		$string = str_replace ('#$#$##'.$value.'#$##', '</'.$value.'>', $string );
        	}
            return True;
        }
        $keys = array_keys ( $string );
        foreach ( $keys as $key )
        {
            clean_xss ( $string [$key] );
        }
    }
?>
