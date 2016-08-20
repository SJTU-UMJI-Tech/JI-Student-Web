<!DOCTYPE html>
<?php
session_start();
//若是会话没有被设置，查看是否设置了cookie
require_once 'function.php';
require_once 'component.php';
$dbc = db_connect();
init_dash_web($dbc);

if (isset($_GET['del_id']) && check_CSRF()) {
	if (!empty($_GET["del_id"])) {
        $query = "DELETE FROM Event_reg WHERE event_id=". mysqli_real_escape_string($dbc, $_GET["del_id"])
        ." AND userID=\"". mysqli_real_escape_string($dbc, $_SESSION["user_id"])."\"";
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
        <?php gen_dash_sidebar(1);?>
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
          	if (!isset($_GET['event_id'])) {
              $event_info = array();
              $query = "SELECT * FROM Events WHERE status='published' AND event_id IN (SELECT event_id FROM Event_reg WHERE userID='".$_SESSION['user_id']."' COLLATE utf8_bin)";
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
                      <?php if ($event_info['photo_link'] != "") {?>
                        <div class="col-xs-12 col-sm-5 col-md-6">
                      <?php } else { ?>
                        <div class="col-xs-12 col-sm-10 col-md-10">
                      <?php }?>
                      
                      
                      	<hr>
                          <h2><strong><a href="<?php echo $_SERVER['PHP_SELF'] ?>?event_id=<?php echo $event_info['event_id']?>"><?php echo $event_info['title'];?></a></strong></h2>
                          <p><strong>Time: </strong><?php echo $event_info['time'];?></p>
                          <p><strong>Location: </strong><?php echo $event_info['location'];?></p>
                          <p><strong>Contact us: </strong><a href="mailto:<?php echo $event_info['contact_email'];?>"><?php echo $event_info['contact_email'];?></a></p>
                      </div>
                      <?php if ($event_info['photo_link'] != "") { ?>
                      <div class="col-xs-12 col-sm-5 col-md-4">
                        <hr>
                        <img width="200" src="<?php echo $event_info['photo_link']?>">
                      </div>
                      <?php } ?>
                      <div class="col-xs-12 col-sm-2 col-md-2">
                      	<hr>
                      	<a href="<?php echo $_SERVER['PHP_SELF'] ?>?event_id=<?php echo $event_info['event_id']?>"><button style="margin-top: 5px" class="btn btn-primary">details</button></a>
                      	<a href="<?php echo $_SERVER['PHP_SELF'] ?>?del_id=<?php echo $event_info['event_id']?>&CSRFcheck=<?php echo $_SESSION['CSRFcheck'] ?>"><button style="margin-top: 5px" class="btn btn-default">withdraw</button></a>
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
            gen_event_detail($dbc, mysqli_real_escape_string($dbc, $_GET['event_id']));
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