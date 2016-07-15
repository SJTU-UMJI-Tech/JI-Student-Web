<!DOCTYPE html>
<?php
session_start();
//若是会话没有被设置，查看是否设置了cookie
require_once 'function.php';
require_once 'component.php';
$dbc = db_connect();
init_dash_web($dbc);

$reg_scho_err_msg = "";
if (isset($_POST['submit']) && check_CSRF()) {
  $reg_scho_err_msg = reg_career($dbc);
}

if (isset($_POST['upload']) && check_CSRF()) {
  update_att_DB($dbc, "Career", $_GET['career_id'], 'org', false);
}

if (isset($_GET['rmfile']) && isset($_GET['career_id']) && check_CSRF()) {
    del_file($dbc, $_GET['rmfile']);
    header("Location: ".$_SERVER["PHP_SELF"]."?action=modify&career_id=".$_GET['career_id']."&error=".$career_err_msg);
}

if (isset($_GET['del_id']) && check_CSRF()) {
	if (!empty($_GET["del_id"])) {
        $query = "DELETE FROM Career_reg WHERE career_id=". mysqli_real_escape_string($dbc, $_GET["del_id"])
        ." AND stuID=\"". mysqli_real_escape_string($dbc, $_SESSION["user_id"])."\"";
        $data = mysqli_query($dbc,$query);
        //echo $query;
        echo mysqli_error($dbc);

        $query = "SELECT * FROM Attachments WHERE key_id=". mysqli_real_escape_string($dbc, $_GET["del_id"])
        ." AND upload_by_user_id=\"". mysqli_real_escape_string($dbc, $_SESSION["user_id"])
        ."\" COLLATE utf8_bin AND table_name='Career' COLLATE utf8_bin";
        $data = mysqli_query($dbc,$query);
        //echo $query;
        echo mysqli_error($dbc);
        while ($att_info = $data -> fetch_array()) {
          unlink($att_info['file_link']);
        }

        $query = "DELETE FROM Attachments WHERE key_id=". mysqli_real_escape_string($dbc, $_GET["del_id"])
        ." AND upload_by_user_id=\"". mysqli_real_escape_string($dbc, $_SESSION["user_id"])
        ."\" COLLATE utf8_bin AND table_name='Career' COLLATE utf8_bin";
        $data = mysqli_query($dbc,$query);
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
        <?php gen_dash_sidebar(2);?>
        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>

          <?php if (isset($_GET['del_id'])): ?>
          	<div class="alert alert-success">
                <button class="close" data-dismiss="alert">&times;</button>
                <strong>Success! </strong>Your unregistration is done.
            </div>
          <?php endif ?>

          <?php
          	if (!isset($_GET['career_id'])) {
              $scholarship_info = array();
              $query = "SELECT * FROM Career WHERE career_id IN (SELECT career_id FROM Career_reg WHERE stuID='".$_SESSION['user_id']."' COLLATE utf8_bin)";
              $result = mysqli_query($dbc,$query);
              echo mysqli_error($dbc);
              if ($result) {
              	$tmp = 0;
                  while($scholarship_info = $result->fetch_array())
                  {
                  	$tmp += 1;
                    ?>
                    <div class="row">

                      <div class="col-xs-12 col-sm-10 col-md-9">
                        <hr>
                          <h2><strong><a href="<?php echo $_SERVER['PHP_SELF'] ?>?career_id=<?php echo $scholarship_info['career_id']?>"><?php echo $scholarship_info['title'];?></a></strong></h2>
                          <p><strong>Deadline: </strong><?php echo $scholarship_info['deadline'];?></p>
                          <p><strong>Requirements: </strong><?php echo $scholarship_info['requirements'];?></p>
                          <p><strong>Contacts: </strong><a href="mailto:<?php echo $scholarship_info['contact_email'];?>"><?php echo $scholarship_info['contact_email'];?></a></p>
                      </div>
                      <div class="col-xs-12 col-sm-2 col-md-3">
                      	<hr>
                      	<a href="<?php echo $_SERVER['PHP_SELF'] ?>?career_id=<?php echo $scholarship_info['career_id']?>"><button style="margin-top: 5px" class="btn btn-primary">details</button></a>
                      	<a href="<?php echo $_SERVER['PHP_SELF'] ?>?del_id=<?php echo $scholarship_info['career_id']?>&CSRFcheck=<?php echo $_SESSION['CSRFcheck'] ?>"><button style="margin-top: 5px" class="btn btn-default">withdraw</button></a>
                      </div>
                    </div>
                    <?php
                  }
                  if ($tmp == 0) {
                  	echo "<h2>No career found.</h2>";
                  }
                  gen_pagination();
              }

          } else {
            ?>
            <div class="row">
              <div class="col-xs-12 col-sm-12">
                <?php gen_career_details($dbc, $_GET['career_id']); ?>
                <hr>
              </div>
              
            </div>
            <?php gen_reg_form($dbc, $_GET['career_id'], "Career", "career_id"); ?>
            <?php
          }
          ?>
          </div>
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