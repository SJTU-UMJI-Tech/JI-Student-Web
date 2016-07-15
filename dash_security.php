<!DOCTYPE html>
<?php
session_start();
//若是会话没有被设置，查看是否设置了cookie
require_once 'function.php';
require_once 'component.php';
$dbc = db_connect();
init_dash_web($dbc);
$err_msg = "";
if (isset($_POST['submit']) && check_CSRF()) {
	// update password
    if (!empty($_POST["password_update"])) {
    	$query = "SELECT password FROM All_users WHERE stuID='".$_SESSION["user_id"]."'";
    	$data = mysqli_query($dbc,$query);
	        //echo $query;
	    echo mysqli_error($dbc);
	    $true_pswd = $data -> fetch_array();
	    //var_dump($_POST["old_password"]);

	    if ($true_pswd[0] == "" || $true_pswd[0] == mysqli_real_escape_string($dbc,sha1($_POST["old_password"]))) {
	    	if (!empty($_POST["new_password"])) {
          if ($_POST["new_password"] == $_POST["new_password2"]) {
            $query = "UPDATE All_users SET password=\"". mysqli_real_escape_string($dbc, sha1($_POST["new_password"]))
            ."\" WHERE stuID=\"".$_SESSION["user_id"]."\" AND password='".mysqli_real_escape_string($dbc, $_POST["old_password"])."' COLLATE utf8_bin";
            $data = mysqli_query($dbc,$query);
            //echo $query;
            echo mysqli_error($dbc);
          } else {
            $err_msg = "Please input the <b>same</b> new password twice.";
          }
	    	} else {
	    		$err_msg = "Empty password is not allowed.";
	    	}
	    } else {
	    	$err_msg = "Old password invalid.";
	    }
	    //var_dump($err_msg);
    } 
}
?>

<html lang="en">
  <head>
    <?php gen_header(); ?>
  </head>

  <body>
    <?php gen_navbar("Dashboard");?>

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">
        <?php gen_dash_sidebar(5);?>
        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>
          <?php err_handle($err_msg, "Password changed.", "password_update"); ?>
          <form method="POST" class="form-horizontal" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>">
            <h2>Security Settings</h2>
            <hr>
            <div class="form-group">
              <label class="col-sm-3 control-label" for="typeahead">Old password </label>
              <div class="col-sm-6">
                <input type="password" class="form-control"  id="1" name="old_password">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label" for="typeahead">New password </label>
              <div class="col-sm-6">
                <input type="password" class="form-control required" id="2" name="new_password">
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-3 control-label" for="typeahead">New password again </label>
              <div class="col-sm-6">
                <input type="password" class="form-control required" id="3" name="new_password2">
              </div>
            </div>
            <div class="form-group">
            	<div class="col-sm-offset-3 col-sm-9">
				<input type="hidden" name="password_update" value="set">
        <input type="hidden" name="CSRFcheck" value="<?php echo $_SESSION['CSRFcheck'] ?>">
				<button type="submit" class="btn btn-primary" name="submit">Change password</button>
				<button type="reset" class="btn">Cancel</button>
				</div>
            </div>
        	</form>

		    </div><!--/span-->

        
      </div><!--/row-->

      <hr>

      <?php gen_footer(); ?>

    </div><!--/.container-->



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script-->
    <?php gen_pageEnd(); ?>
    <?php mysqli_close($dbc); ?>
  </body>
</html>