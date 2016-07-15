
<!DOCTYPE html>
<?php
session_start();
//若是会话没有被设置，查看是否设置了cookie
require_once 'function.php';
require_once 'component.php';
$dbc = db_connect();
init_dash_web($dbc);
if (isset($_GET['org'])) {
  $org_name = $_GET['org'];
} else {
  $home_url = "index.php";
  header("Location:".$home_url);
}

$reg_event_err_msg = "";
if (isset($_POST['submit']) && check_CSRF()) {
  $reg_event_err_msg = reg_event($dbc);
}

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
        <?php gen_sidebar($org_name, 3);?>
        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>

          <?php // handle error msg
            err_handle($reg_event_err_msg, "Your information has been well received.", "submit");
          ?>

          <?php
            if (!isset($_GET['event_id'])) {
              $org_info = array();

              $query = "SELECT * FROM Organization WHERE org_name='".$org_name."' COLLATE utf8_bin AND status='published'";
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
          

          <?php
              $event_info = array();
              $query = "SELECT * FROM Events WHERE org_name='".$org_name."' COLLATE utf8_bin AND status='published'";
              $result = mysqli_query($dbc,$query);
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
                          <h2><strong><a href="org_events.php?org=<?php echo $org_name?>&event_id=<?php echo $event_info['event_id']?>#reg_anchor"><?php echo $event_info['title'];?></a></strong></h2>
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
                        <a href="org_events.php?org=<?php echo $org_name?>&event_id=<?php echo $event_info['event_id']?>#reg_anchor"><button class="btn btn-default">register</button></a>
                      </div>
                    </div>
                    <?php
                  }
                  if ($tmp == 0) {
                    echo "<h2>No events found.</h2>";
                  }
                  gen_pagination();
              }
          ?>

          <?php
          } else {
          	gen_event_detail($dbc, $_GET['event_id']);
            gen_reg_event_form($_GET['event_id']); ?>
            </div>
            <?php
          }
          ?>
        </div><!--/span-->

        <hr>

      
      </div><!--/row-->
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
