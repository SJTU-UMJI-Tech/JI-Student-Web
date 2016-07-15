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

$page = "";
if (isset($_GET['cat'])) {
  $page = $_GET['cat'];
} else {
  $page = "";
}

$feedback_err_msg = handle_feedback($dbc, "Career");

?>

<html lang="en">
  <head>
    <?php gen_header(); ?>
  </head>

  <body>
    <?php gen_navbar("Career");?>

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">
        <?php gen_career_sidebar($page);?>
        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>
          <?php // handle error msg
            err_handle($reg_scho_err_msg, "Your information has been well received.","submit");
            err_handle($feedback_err_msg, "Your information has been well received.","feedback");
          ?>
          <?php
          	if (!isset($_GET['career_id'])) {
              $career_info = array();
              $tmp = 0;
              if ($page == "") {
                $query = "SELECT * FROM Career WHERE status='published'";
              } elseif ($page == "fulltime") {
                $query = "SELECT * FROM Career WHERE status='published' AND (category='fulltime' OR category='fulltime and internship')";
              } elseif ($page == "internship") {
                $query = "SELECT * FROM Career WHERE status='published' AND (category='internship' OR category='fulltime and internship')";
              } elseif ($page == "My Career") {
                $query = "SELECT * FROM Career WHERE status='published' AND career_id IN (SELECT career_id FROM Career_reg WHERE stuID='".$_SESSION['user_id']."' COLLATE utf8_bin)";
              } elseif ($page == "feedback") {
                $query = "SELECT * FROM Career WHERE category='none'";
                $tmp = 1;
                echo "<h1> Career Feedback</h1> <hr>";
                gen_feedback_form();
              } else {
                $query = "SELECT * FROM Career WHERE category='none'";
              }
              $result = mysqli_query($dbc,$query);
              echo mysqli_error($dbc);
              if ($result) {
                  while($career_info = $result->fetch_array())
                  {
                  	$tmp += 1;
                    ?>
                    <div class="row">

                      <div class="col-xs-12 col-sm-12 col-md-12">
                          <h2><strong><a href="<?php echo $_SERVER['PHP_SELF'] ?>?career_id=<?php echo $career_info['career_id']?>"><?php echo $career_info['title'];?></a></strong></h2>
                          <p><strong>Deadline: </strong><?php echo $career_info['deadline'];?></p>
                          <p><strong>Requirements: </strong><?php echo $career_info['requirements'];?></p>
                          <p><strong>Contacts: </strong><a href="mailto:<?php echo $career_info['contact_email'];?>"><?php echo $career_info['contact_email'];?></a></p>
                      </div>
                    </div>
                    <?php
                  }
                  if ($tmp == 0) {
                  	echo "<h2>No career info found.</h2>";
                  }
                  if ($page != "feedback") {
                    gen_pagination();
                  }
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