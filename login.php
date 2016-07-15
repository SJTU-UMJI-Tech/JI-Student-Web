<!DOCTYPE html>
<?php
//插入连接数据库的相干信息
//开启一个会话
session_start();
$error_msg = "";
require_once 'function.php';
require_once 'settings.php';
//若是用户未登录，即未设置$_SESSION["user_id"]时，履行以下代码
$dbc = db_connect();
$home_url = "index.php";
$redirect_uri = $GLOBALS['redirect_uri'];
if(!isset($_SESSION["user_id"])){
  if (isset($_GET['code'])) {
    $auth_code = $_GET['code'];

    $url = 'https://jaccount.sjtu.edu.cn/oauth2/token';
    $post_data['grant_type']    = 'authorization_code';
    $post_data['code']          = $auth_code;
    $post_data['redirect_uri']  = $redirect_uri;
    $post_data['client_id']     = 'jaji20150623';
    $post_data['client_secret'] = 'C8ECB65DE3584F19A1AB31ADD98052F2EC01A1A707EA8301';
    //$post_data = array();
    $token_json = request_post($url, $post_data);       
    //var_dump($token_json);

    $token_info = json_decode($token_json);

    $url = "https://api.sjtu.edu.cn/v1/me/profile?access_token=".$token_info->access_token;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
    //curl_setopt($ch, CURLOPT_POST, 1); // 发送一个常规的Post请求
    //curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost); // Post提交的数据包
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
    curl_setopt($ch, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

    $usr_json = curl_exec($ch);
    curl_close($ch);
    var_dump($usr_json);

    $usr_info = json_decode($usr_json);
    $_SESSION["user_id"]=$usr_info->entities[0]->code;
    $_SESSION["username"]=$usr_info->entities[0]->name;
    //setcookie("user_id",$_SESSION["user_id"],time()+(60*30));
    //setcookie("username",$_SESSION["username"],time()+(60*30));

    $query = "SELECT * FROM All_users WHERE stuID='". mysqli_real_escape_string($dbc, $_SESSION["user_id"])."'";
    $data = mysqli_query($dbc,$query);
    //echo $query;
    echo mysqli_error($dbc);

    if ($info = $data -> fetch_array()) {
      $query = "UPDATE All_users SET username=\"". mysqli_real_escape_string($dbc, $_SESSION["username"])
      ."\" WHERE stuID=\"".$_SESSION["user_id"]."\"";
      $data = mysqli_query($dbc,$query);
      //echo $query;
      echo mysqli_error($dbc);
    } else {
      $query = "INSERT INTO All_users SET username='". mysqli_real_escape_string($dbc, $_SESSION["username"])
      ."', stuID='".mysqli_real_escape_string($dbc, $_SESSION["user_id"])
      ."', email='".mysqli_real_escape_string($dbc, $usr_info->entities[0]->email)
      ."', password='', dob='".mysqli_real_escape_string($dbc, "")
      ."', phone='".mysqli_real_escape_string($dbc, $usr_info->entities[0]->mobile)."'";
      $data = mysqli_query($dbc,$query);
      //echo $query;
      echo mysqli_error($dbc);
    }

    header("Location: ".$home_url);
  }

  if(isset($_POST["submit"])){//用户提交登录表单时履行如下代码
    if (strcasecmp($_POST["check"],$_SESSION["check"])!=0) {
      $error_msg = "wrong CAPTCHA!";
    } else {
      $user_ID = mysqli_real_escape_string($dbc,trim($_POST["userID"]));
      $user_password = mysqli_real_escape_string($dbc,sha1($_POST["password"]));
      if(!empty($user_ID)&&!empty($user_password)){
          //MySql中的SHA()函数用于对字符串进行单向加密
          $query = "SELECT stuID, username FROM All_users WHERE stuID = \"".$user_ID."\" COLLATE utf8_bin AND password = \"".$user_password."\" COLLATE utf8_bin";
          $data = mysqli_query($dbc,$query);
          //用用户名和暗码进行查询，若查到的记录正好为一条，则设置SESSION和COOKIE，同时进行页面重定向
          if(mysqli_num_rows($data)>=1){ // TODO::
              $row = mysqli_fetch_array($data);
              $_SESSION["user_id"]=$row["stuID"];
              $_SESSION["username"]=$row["username"];
              //setcookie("user_id",$row["stuID"],time()+(60*60*24*30));
              //setcookie("username",$row["username"],time()+(60*60*24*30));
              header("Location: ".$home_url);
          }else{//若查到的记录不合错误，则设置错误信息
              $error_msg = "Sorry, you must enter a valid ID and password to log in.";
          }
      }else{
          $error_msg = "Sorry, you must enter a valid ID and password to log in.";
      }
    }
    $error_msg="<div class=\"alert alert-error\">".$error_msg."</div>";
  }
}else{//若是用户已经登录，则直接跳转到已经登录页面
    header("Location: ".$home_url);
}
mysqli_close($dbc);
?>
<html>
  <head>
    <title>Admin Login</title>

    <!-- Bootstrap -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">
    <link href="assets/styles.css" rel="stylesheet" media="screen">
     <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script src="vendors/modernizr-2.6.2-respond-1.1.0.min.js"></script>
  </head>

  <body id="login">
    <div class="container">

      <form class="form-signin" method = "post" action="<?php echo $_SERVER["PHP_SELF"];?>">
        <h2 class="form-signin-heading">Please sign in</h2>
        <?php
        if(!isset($_SESSION["user_id"])) {
          echo  $error_msg;
        ?>
        <input type="text" id="userID" name="userID" class="input-block-level required>" placeholder="User ID/Student ID" value="<?php if(!empty($user_ID)) echo $user_ID; ?>" />
        <input type="password" id="password" name="password" class="input-block-level required" placeholder="Password">
        <div class=""><input type="text" class="required" name="check" placeholder="Captcha"><a href="login.php"><img align="right" src="captcha.php"></img></a></div>
        <button class="btn btn-large btn-primary" type="submit" name="submit">Sign in</button>
        <hr>
        <a href="https://jaccount.sjtu.edu.cn/oauth2/authorize?response_type=code&client_id=jaji20150623&redirect_uri=<?php echo $redirect_uri ?>">JAccount Login</a>

      </form>
    </div> <!-- /container -->
    

    <script src="vendors/jquery-1.9.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
  </body>
  <?php
  }
  ?>
</html>