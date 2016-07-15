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
  clean_xss($org_name);
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
        <?php gen_sidebar($org_name, 1);?>
        <div class="col-xs-12 col-sm-9">
          <p class="pull-right visible-xs">
            <button type="button" class="btn btn-primary btn-xs" data-toggle="offcanvas">Toggle nav</button>
          </p>

          <?php
            if (!isset($_GET['memberID'])) {
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
          

          <?php
              $mem_info = array();
              $query = "SELECT * FROM Member WHERE belong_org='".$org_name."' COLLATE utf8_bin AND status='show' COLLATE utf8_bin ORDER BY title DESC";
              $result = mysqli_query($dbc,$query);
              if ($result) {
                  while($mem_info = $result->fetch_array())
                  {
                    $new_query = "SELECT * FROM All_users WHERE stuID='".$mem_info['stuID']."' COLLATE utf8_bin";
                      $new_result = mysqli_query($dbc,$new_query);
                      if ($new_result) {
                        $person_info = $new_result->fetch_array();
                        ?>
                        <div class="row">
                  
                          <div class="col-xs-12 col-sm-6 col-md-4">
                            <hr>
                            <?php if ($mem_info['photo_link'] != ""): ?>
                              <img style="max-width: 200px" src="<?php echo $mem_info['photo_link']?>">
                            <?php endif ?>
                          </div>

                          <div class="col-xs-12 col-sm-6 col-md-8">
                            <hr>
                              <h2><a href="org_member.php?org=<?php echo $org_name?>&memberID=<?php echo $mem_info['stuID']?>"><?php echo $person_info['username'];?></a></h2>
                              <h4><i><?php echo $mem_info['title'];?></i></h4>
                              <p><strong>Email: </strong><a href="mailto:<?php echo $person_info['email'];?>"><?php echo $person_info['email'];?></a></p>
                              <p><strong>Phone: </strong><?php echo $person_info['phone'];?></p>
                          </div>
                        </div>
                    <?php
                    }
                  }
                  gen_pagination();
              }
          ?>

          <?php
          } else {
            $query = "SELECT * FROM Member WHERE stuID='".$_GET['memberID']."' COLLATE utf8_bin AND status='show' COLLATE utf8_bin AND belong_org='".$org_name."' COLLATE utf8_bin";
              $result = mysqli_query($dbc,$query);
              if ($result) {
                $member_info = $result->fetch_array();
              }
            $new_query = "SELECT * FROM All_users WHERE stuID='".$_GET['memberID']."' COLLATE utf8_bin";
              $new_result = mysqli_query($dbc,$new_query);
              if ($new_result) {
                $person_info = $new_result->fetch_array();
              }
            ?>
            <div class="row">
              
              <div class="col-xs-12 col-sm-6 col-md-4">
                <hr>
                <?php if ($member_info['photo_link'] != ""): ?>
                <p style="text-align:center"><img src="<?php echo $member_info['photo_link']?>"></p>
                <?php endif ?>
              </div>

              <div class="col-xs-12 col-sm-6 col-md-8">
                <hr>
                  <h2><?php echo $person_info['username'];?></h2>
                  <h4><i><?php echo $member_info['title'];?></i></h4>
                  <p><strong>Email: </strong><a href="mailto:<?php echo $person_info['email'];?>"><?php echo $person_info['email'];?></a></p>
                  <p><strong>Phone: </strong><?php echo $person_info['phone'];?></p>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-xs-12 col-sm-12">
                <p><?php echo $member_info['description']?></p>
              </div>
            </div>
            <?php
          }
          ?>
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
