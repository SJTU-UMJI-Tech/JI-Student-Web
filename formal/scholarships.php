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
  $reg_scho_err_msg = reg_scholarship($dbc);
}

if (isset($_POST['upload']) && check_CSRF()) {
  update_att_DB($dbc, "Scholarships", $_GET['scholarship_id'], 'org', false);
}

if (isset($_GET['rmfile']) && isset($_GET['scholarship_id']) && check_CSRF()) {
    del_file($dbc, $_GET['rmfile']);
    header("Location: ".$_SERVER["PHP_SELF"]."?action=modify&scholarship_id=".$_GET['scholarship_id']."&error=".$scholarship_err_msg);
}

$page = "";
if (isset($_GET['cat'])) {
  $page = $_GET['cat'];
} else {
  $page = "";
}

$feedback_err_msg = handle_feedback($dbc, "Scholarships");

?>

<html lang="en">
  <head>
    <?php gen_header(); ?>
  </head>

  <body>
    <?php gen_navbar("Scholarships");?>

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">
        <?php gen_scholarship_sidebar($page);?>
        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>
          <?php // handle error msg
            err_handle($reg_scho_err_msg, "Your information has been well received.","submit");
            err_handle($feedback_err_msg, "Your information has been well received.","feedback");
          ?>
          <?php
          	if (!isset($_GET['scholarship_id'])) {
              $scholarship_info = array();
              $tmp = 0;
              if ($page == "") {
                $query = "SELECT * FROM Scholarships WHERE status='published'";
              } elseif ($page == "undergraduates") {
                $query = "SELECT * FROM Scholarships WHERE status='published' AND (category='undergraduates' OR category='undergraduates and graduates')";
              } elseif ($page == "graduates") {
                $query = "SELECT * FROM Scholarships WHERE status='published' AND (category='graduates' OR category='undergraduates and graduates')";
              } elseif ($page == "My Scholarships") {
                $query = "SELECT * FROM Scholarships WHERE status='published' AND scholarship_id IN (SELECT scholarship_id FROM Scholarship_reg WHERE stuID='".$_SESSION['user_id']."' COLLATE utf8_bin)";
              } elseif ($page == "feedback") {
                $query = "SELECT * FROM Scholarships WHERE category='none'";
                $tmp = 1;
                echo "<h1> Scholarships Feedback</h1> <hr>";
                gen_feedback_form();
              } else {
                $query = "SELECT * FROM Scholarships WHERE category='none'";
              }
              $result = mysqli_query($dbc,$query);
              echo mysqli_error($dbc);
              if ($result) {
                  while($scholarship_info = $result->fetch_array())
                  {
                  	$tmp += 1;
                    ?>
                    <div class="row">

                      <div class="col-xs-12 col-sm-12 col-md-12">
                          <h2><strong><a href="<?php echo $_SERVER['PHP_SELF'] ?>?scholarship_id=<?php echo $scholarship_info['scholarship_id']?>"><?php echo $scholarship_info['title'];?></a></strong></h2>
                          <p><strong>Deadline: </strong><?php echo $scholarship_info['deadline'];?></p>
                          <p><strong>Requirements: </strong><?php echo $scholarship_info['requirements'];?></p>
                          <p><strong>Contacts: </strong><a href="mailto:<?php echo $scholarship_info['contact_email'];?>"><?php echo $scholarship_info['contact_email'];?></a></p>
                      </div>
                    </div>
                    <?php
                  }
                  if ($tmp == 0) {
                  	echo "<h2>No scholarhsip found.</h2>";
                  }
                  if ($page != "feedback") {
                    gen_pagination();
                  }
              }

          } else {
            
            ?>
            <div class="row">
              <div class="col-xs-12 col-sm-12">
                <?php gen_scholarship_details($dbc, $_GET['scholarship_id']); ?>
                <hr>
              </div>
              
            </div>
            <?php gen_reg_form($dbc, $_GET['scholarship_id'], "Scholarships", "scholarship_id"); ?>
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