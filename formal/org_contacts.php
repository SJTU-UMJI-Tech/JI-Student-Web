<!DOCTYPE html>
<?php
session_start();
//若是会话没有被设置，查看是否设置了cookie
require_once 'function.php';
require_once 'component.php';
include 'smtp_send.php';
$dbc = db_connect();
init_dash_web($dbc);

if (isset($_GET['org'])) {
  $org_name = $_GET['org'];
} else {
  $home_url = "index.php";
  header("Location:".$home_url);
}

$feedback_err_msg = handle_feedback($dbc, $org_name);
?>

<html lang="en">
  <head>
    <?php gen_header(); ?>
  </head>

  <body>
    <?php if ($_GET['org'] != 'Advising') {
      gen_navbar("Organizations");
    } else {
      gen_navbar("Advising");
    }?>

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">
        <?php gen_sidebar($org_name, 5);?>
        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>

          <?php // handle error msg
          err_handle($feedback_err_msg, "Your information has been well received.", "feedback");

              $org_info = array();

              $query = "SELECT * FROM Organization WHERE org_name='".$org_name."' COLLATE utf8_bin";
              $result = mysqli_query($dbc,$query);
              if ($result) {
                if($result->num_rows > 0){
                    $org_info = $result->fetch_array();
                    ?>
                    <div class="jumbotron">
                      <h1><?php echo $org_info['org_name']?></h1>
                      <p><?php echo $org_info['short_description']?></p>
                    </div>
                    <?php
                } else {
                  ?>
                    <div class="jumbotron">
                      <h1>Sorry... </h1>
                      <h3>We could not find such organization.</h3>
                    </div>
                  <?php
                }
              }
          ?>
            <?php gen_feedback_form(); ?>
            </div>

            </div><!--/span-->

        <hr>

      <?php gen_footer(); ?>

      </div><!--/row-->

      
    </div><!--/.container-->



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script-->
    <?php gen_pageEnd(); ?>
    <?php mysqli_close($dbc); ?>
  </body>
</html>
