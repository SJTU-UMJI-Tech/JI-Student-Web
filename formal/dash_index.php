<!DOCTYPE html>
<?php
session_start();
//若是会话没有被设置，查看是否设置了cookie
require_once 'function.php';
require_once 'component.php';
$dbc = db_connect();
init_dash_web($dbc);

if (isset($_POST['submit']) && check_CSRF()) {
	if (!empty($_POST["user_update"])) {
        $query = "UPDATE All_users SET email=\"". mysqli_real_escape_string($dbc, $_POST["mem_email"])
        ."\", phone=\"". mysqli_real_escape_string($dbc, $_POST["mem_phone"])
        ."\", dob=\"". mysqli_real_escape_string($dbc, $_POST["mem_dob"])
        ."\" WHERE stuID=\"".$_SESSION["user_id"]."\"";
        $data = mysqli_query($dbc,$query);
        $_SESSION["useremail"] = mysqli_real_escape_string($dbc, $_POST["mem_email"]);
        $_SESSION["userphone"] = mysqli_real_escape_string($dbc, $_POST["mem_phone"]);
        //echo $query;
        echo mysqli_error($dbc);
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
        <?php gen_dash_sidebar(0);?>
        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>

          <form method="POST" class="form-horizontal" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>">
		    <h1><?php echo $_SESSION["username"]; ?></h1>
		    <hr>
		        <?php
		            $query = "SELECT * FROM All_users WHERE stuID='".$_SESSION["user_id"]."'";
		            $result = mysqli_query($dbc,$query);
		            if ($result) {
		                $row_usr =$result->fetch_array();
		            }
		        ?>
		        <div class="form-group">
		          <label class="col-sm-2 control-label" for="typeahead">Name </label>
		          <div class="col-sm-6">
		            <input type="text" disabled="" class="form-control" name="mem_name" value="<?php echo $_SESSION["username"]; ?>">
		          </div>
		        </div>
		        <div class="form-group">
		          <label class="col-sm-2 control-label" for="typeahead">ID </label>
		          <div class="col-sm-6">
		            <input type="text" class="form-control" id="disabledInput" disabled="" value="<?php echo $_SESSION["user_id"]; ?>">
		          </div>
		        </div>
		        <div class="form-group">
		          <label class="col-sm-2 control-label" for="typeahead">Email </label>
		          <div class="col-sm-6">
		            <input type="text" class="form-control required email" id="email" name="mem_email" value="<?php echo $row_usr['email'] ?>">
		          </div>
		        </div>
		        <div class="form-group">
		          <label class="col-sm-2 control-label" for="typeahead">Phone </label>
		          <div class="col-sm-6">
		            <input type="text" class="form-control required" id="phone" name="mem_phone" value="<?php echo $row_usr['phone'] ?>">
		          </div>
		        </div>
		        <div class="form-group">
		          <label class="col-sm-2 control-label" for="date01">Birthday</label>
		          <div class="col-sm-6">
		            <input type="text" class="form-control datepicker" id="date01" value="<?php echo $row_usr['dob'] ?>" name="mem_dob">
		          </div>
		        </div>
		        <div class="form-group">
		        	<div class="col-sm-offset-2 col-sm-10">
		        	<input type="hidden" name="user_update" value="set">
		        	<input type="hidden" name="CSRFcheck" value="<?php echo $_SESSION['CSRFcheck'] ?>">
                    <button type="submit" class="btn btn-primary" name="submit">Save</button>
                    <button type="reset" class="btn btn-default">Cancel</button>
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