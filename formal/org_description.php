
<!DOCTYPE html>
<?php

session_start();
//若是会话没有被设置，查看是否设置了cookie
require_once 'function.php';
require_once 'component.php';
$dbc = db_connect();
init_web($dbc);
if (isset($_GET['org'])) {
  $org_name = $_GET['org'];  
} else {
  $home_url = "index.php";
  header("Location:".$home_url);
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
        <?php gen_sidebar($org_name, 0);?>
        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>

          <?php
              //echo "test";
              $org_info = array();
              //echo $org_name;
              $query = "SELECT * FROM Organization WHERE org_name='".$org_name."' COLLATE utf8_bin";
              $result = mysqli_query($dbc,$query);
              echo mysqli_error($dbc);
              //echo $query;
              if ($result) {
                if($result->num_rows > 0){
                    $org_info = $result->fetch_array();
                    ?>
                    <div class="col-xs-12 col-sm-12">
                      <h1><?php echo $org_info['org_name']?></h1>
                      <?php if ($org_info['photo_link'] != ""): ?>
                        <hr>
                        <img style="max-width: 260px; max-height: 180px;" src="<?php echo $org_info['photo_link']?>">
                      <?php endif ?>
                      <hr>
                      <p><?php echo $org_info['description']?></p>
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
          
          
        </div><!--/span-->

        
      </div><!--/row-->

      <hr>

      <?php
      gen_footer(); 
      mysqli_close($dbc);
      ?>

    </div><!--/.container-->



    <!-- Bootstrap core JavaScript
    ================================================== -->
    <?php gen_pageEnd(); ?>

  </body>
</html>
