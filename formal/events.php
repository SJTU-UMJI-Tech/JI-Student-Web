<!DOCTYPE html>
<?php
session_start();
//若是会话没有被设置，查看是否设置了cookie
require_once 'function.php';
require_once 'component.php';
$dbc = db_connect();
init_dash_web($dbc);

$reg_event_err_msg = "";
if (isset($_POST['submit']) && check_CSRF()) {
  $reg_event_err_msg = reg_event($dbc);
}

if (isset($_GET['org'])) {
  $page = $_GET['org'];
} else {
  $page = "";
}

?>

<html lang="en">
  <head>
    <?php gen_header(); ?>
  </head>

  <body>
    <?php gen_navbar("Events");?>

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">
        <?php gen_events_sidebar($page, $dbc);?>
        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>
          <?php // handle error msg
            err_handle($reg_event_err_msg, "Your information has been well received.","submit");
          ?>
          <?php
          	if (!isset($_GET['event_id'])) {
              $event_info = array();
              if (isset($_GET['org'])) {
                $query = "SELECT * FROM Events WHERE org_name='".mysqli_real_escape_string($dbc, $_GET['org'])."' COLLATE utf8_bin AND status='published'";
              } else {
                $query = "SELECT * FROM Events WHERE status='published' AND org_name<>'Developers' ";
              }
              $result = mysqli_query($dbc,$query);
              echo mysqli_error($dbc);
              if ($result) {
              	$tmp = 0;
                  while($event_info = $result->fetch_array())
                  {
                  	$tmp += 1;
                  	$org_name = $event_info['org_name'];
                    ?>
                    <div class="row">
                      
                      <?php if ($event_info['photo_link'] != ""): ?>
                          <div class="col-xs-12 col-sm-5 col-md-6">
                      <?php else: ?>
                        <div class="col-xs-12 col-sm-10 col-md-10">
                      <?php endif ?>
                      <hr>
                          <h2><strong><a href="<?php echo $_SERVER['PHP_SELF'] ?>?event_id=<?php echo $event_info['event_id']?>"><?php echo $event_info['title'];?></a></strong></h2>
                          <p><strong>Time: </strong><?php echo $event_info['time'];?></p>
                          <p><strong>Location: </strong><?php echo $event_info['location'];?></p>
                          <p><strong>Contact us: </strong><a href="mailto:<?php echo $event_info['contact_email'];?>"><?php echo $event_info['contact_email'];?></a></p>
                      </div>
                      <?php if ($event_info['photo_link'] != ""): ?>
                      <div class="col-xs-12 col-sm-5 col-md-4">
                        <hr>
                          <img style="max-width: 200px" src="<?php echo $event_info['photo_link']?>">
                      </div>
                      <?php endif ?>
                      <div class="col-xs-1 col-sm-2 col-md-2">
                        <hr>
                        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?event_id=<?php echo $event_info['event_id']?>"><button class="btn btn-default">register</button></a>
                      </div>
                    </div>
                    <?php
                  }
                  if ($tmp == 0) {
                  	echo "<h2>No events found.</h2>";
                  }
                  gen_pagination();
              }

          } else { 
            gen_event_detail($dbc, $_GET['event_id']);
            gen_reg_event_form($_GET['event_id']); ?>
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