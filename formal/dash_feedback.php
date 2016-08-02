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
        $query = "DELETE FROM Feedback WHERE ticket_id=". mysqli_real_escape_string($dbc, $_GET["del_id"])
        ." AND from_stuID='". mysqli_real_escape_string($dbc, $_SESSION["user_id"])
        ."' AND status='not replied'";
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
        <?php gen_dash_sidebar(4);?>
        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>

          <?php
          	if (!isset($_GET['ticket_id'])) {
              $feedback_info = array();
              $query = "SELECT * FROM Feedback WHERE from_stuID='".$_SESSION['user_id']."' COLLATE utf8_bin";
              $result = mysqli_query($dbc,$query);
              echo mysqli_error($dbc);
              if ($result) {
              	$tmp = 0;
                  while($feedback_info = $result->fetch_array())
                  {
                  	$tmp += 1;
                  	$to_org = $feedback_info['to_org'];
                    ?>
                    <div class="row">
                      <div class="col-xs-12 col-sm-9 col-md-10">
                      	<hr>
                          <p><strong>To: </strong><?php echo $feedback_info['to_org'];?> 
                          <strong>At: </strong><?php echo $feedback_info['time'];?></p>
                          <p><?php echo mb_substr($feedback_info['content'], 0, 99)."...";?></p>
                      </div>
                      <div class="col-xs-12 col-sm-3 col-md-2">
                      	<hr>
                      	<p><?php echo $feedback_info['status'] ?></p>
                        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?ticket_id=<?php echo $feedback_info['ticket_id']?>">details</a>
                      </div>
                    </div>
                    <?php
                  }
                  if ($tmp == 0) {
                  	echo "<h2>No feedback found.</h2>";
                  }
                  gen_pagination();
              }

          } else {
            $query = "SELECT * FROM Feedback WHERE ticket_id='".mysqli_real_escape_string($dbc, $_GET['ticket_id'])."'
            AND from_stuID='".$_SESSION['user_id']."' COLLATE utf8_bin";
              $result = mysqli_query($dbc,$query);
              if ($result) {
                $feedback_info = $result->fetch_array();
              }
            ?>
            <div class="row">
              <div class="col-xs-12 col-sm-12">
                  <p><strong>Ticket ID: </strong><?php echo $feedback_info['ticket_id'];?></p>
                  <p><strong>From: </strong><?php echo $feedback_info['from_username'];?></p>
                  <p><strong>To: </strong><?php echo $feedback_info['to_org'];?></p>
                  <p><strong>At: </strong><?php echo $feedback_info['time'];?></p>
                  <p><strong>Status: </strong><?php echo $feedback_info['status'];?></p>
                  <p><strong>Content: </strong><?php echo $feedback_info['content'];?></p>
                  <p><strong>Reply: </strong><?php echo $feedback_info['replied_with'];?></p>
              </div>
              <div class="col-xs-12 col-sm-12">
                <hr>
                  <a href="<?php echo $_SERVER['PHP_SELF']; ?>"><button class="btn btn-primary">Back</button></a>
                <?php if ($feedback_info['status'] != "replied"): ?>
                  <a href="<?php echo $_SERVER['PHP_SELF'].'?del_id='.$feedback_info['ticket_id'].'&CSRFcheck='.$_SESSION['CSRFcheck']; ?>"><button class="btn btn-default">Remove</button></a>
                <?php endif ?>
              </div>
            </div>
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