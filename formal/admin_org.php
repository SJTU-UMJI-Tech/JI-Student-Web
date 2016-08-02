<!DOCTYPE html>
<?php

//应用会话内存储的变量值之前必须先开启会话
session_start();
//若是会话没有被设置，查看是否设置了cookie
include 'function.php';
include 'component.php';
$dbc = db_connect();
init_dash_web($dbc);

if(isset($_POST["submit"]) && check_CSRF()){
    if (!empty($_FILES["org_photo"]["tmp_name"])) {
        if ((($_FILES["org_photo"]["type"] == "image/jpeg") 
        || ($_FILES["org_photo"]["type"] == "image/pjpeg")) 
        && ($_FILES["org_photo"]["size"] < 5000000)) 
        { 
        if ($_FILES["org_photo"]["error"] > 0) 
        { 
            echo "Return Code: " . $_FILES["org_photo"]["error"] . "<br />"; 
        } 
        else 
            { 
            echo "Upload: " . $_FILES["org_photo"]["name"] . "<br />"; 
            echo "Type: " . $_FILES["org_photo"]["type"] . "<br />"; 
            echo "Size: " . ($_FILES["org_photo"]["size"] / 1024) . " Kb<br />"; 
            echo "Temp file: " . $_FILES["org_photo"]["tmp_name"] . "<br />"; 
            if (file_exists("upload/" .$_POST["org_name"].".jpg")) 
                { 
                unlink("upload/" .$_POST["org_name"].".jpg");
                } 
                move_uploaded_file($_FILES["org_photo"]["tmp_name"], 
                "upload/org_" .$_POST["org_name"]."E.jpg"); 
                    $im=imagecreatefromjpeg("upload/org_" .$_POST["org_name"]."E.jpg");//参数是图片的存方路径
                    $maxwidth="600";//设置图片的最大宽度
                    $maxheight="400";//设置图片的最大高度
                    $name="upload/org_" .$_POST["org_name"];//图片的名称，随便取吧
                    $filetype=".jpg";//图片类型
                    resizeImage($im,$maxwidth,$maxheight,$name,$filetype);//调用上面的函数
                unlink("upload/org_" .$_POST["org_name"]."E.jpg"); 
                echo "Stored in: " . "upload/org_" . $_POST["org_name"].".jpg"; 
            } 
        } 
        else 
        { 
            echo "Invalid file"; 
        }
        $photo_addr = "upload/org_" . $_POST["org_name"].".jpg";
        $query = "UPDATE Organization SET photo_link=\"". $photo_addr
        ."\" WHERE org_name=\"".$_POST["org_name"]."\"";
        $data = mysqli_query($dbc,$query);
        echo $query;
        echo mysqli_error($dbc);
    }else{
        $photo_addr = "";
    }

    // update member table
    if (!empty($_POST["org_update"])) {
        $query = "UPDATE Organization SET Description=\"".mysqli_real_escape_string($dbc, $_POST["org_des"])
        ."\", Tag=\"".mysqli_real_escape_string($dbc, $_POST["org_tag"])
        ."\" WHERE org_name=\"".mysqli_real_escape_string($dbc, $_POST["org_name"])."\"";
        $data = mysqli_query($dbc,$query);
        //echo $query;
        echo mysqli_error($dbc);
    }
}

?>
<html class="no-js">
    
    <head>
        <?php gen_header_admin();?>
    </head>
    
    <body>
        <?php gen_navbar_admin(); ?>
        
        <div class="container-fluid">
            <div class="row-fluid">
                <?php gen_sidebar_admin(1);?>
                
                <!--/span-->
                <div class="span9" id="content">
                    <?php
                        $query = "SELECT * FROM Organization WHERE org_name IN (SELECT org_name FROM Admin_users WHERE boss_stuID='".$_SESSION["user_id"]."' COLLATE utf8_bin ORDER BY org_name)";
                        $result = mysqli_query($dbc,$query);
                        echo mysqli_error($dbc);
                            if ($result) {
                                if ($result -> num_rows == 0) { ?>
                                    <div class="row-fluid">
                                        <div class="block">
                                            <div class="navbar navbar-inner block-header">
                                                <div class="muted pull-left">Organizations</div>
                                            </div>
                                            <div class="block-content collapse in">
                                                <div class="span12">
                                                    <legend>Sorry... You are not Admin of any organization.</legend>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                                while($row_org =$result->fetch_array())
                                {
                                ?>
                                <div class="row-fluid">
                                    <div class="block">
                                        <div class="navbar navbar-inner block-header">
                                            <div class="muted pull-left">Organizations</div>
                                        </div>
                                        <div class="block-content collapse in">
                                            <div class="span12">
                                    
                                            </hr>
                                            <form method="POST" class="form-horizontal" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>">
                                              <fieldset>
                                                <legend><?php echo $row_org['org_name']; ?></legend>

                                                    <input type="hidden" name="org_name" value="<?php echo $row_org['org_name'] ?>">
       
                                                <div class="control-group">
                                                  <label class="control-label" for="typeahead">Tag </label>
                                                  <div class="controls">
                                                    <input type="text" class="span6" id="typeahead" name="org_tag" value="<?php echo $row_org['tag'] ?>">
                                                  </div>
                                                </div>
                                                <div class="control-group">
                                                  <label class="control-label" for="fileInput">Photo</label>
                                                  <div class="controls">
                                                    <p style="color: gray; padding-top: 5px">ONLY jpg/jpeg format supported.</p>
                                                    <img src="<?php echo $row_org['photo_link'] ?>"><hr>
                                                    <input class="input-file uniform_on" id="fileInput" type="file" name="org_photo">
                                                  </div>
                                                </div>
                                                <div class="control-group">
                                                  <label class="control-label" for="textarea2">Description</label>
                                                  <div class="controls">
                                                    <textarea id="ckeditor_standard" style="width: 810px; height: 600px" name="org_des" ><?php echo $row_org['description'] ?></textarea>
                                                  </div>    
                                                </div>
                                                <div class="form-actions">
                                                  <input type="hidden" name="org_update" value="set">
                                                  <input type="hidden" name="CSRFcheck" value="<?php echo $_SESSION['CSRFcheck'] ?>">
                                                  <button type="submit" class="btn btn-primary" name="submit">Save changes</button>
                                                  <button type="reset" class="btn">Cancel</button>
                                                </div>
                                              </fieldset>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                    } ?>
                </div>
            </div>
            <hr>
        <?php gen_footer_admin();?>
        </div>
        <!--/.fluid-container-->
        
    <?php 
    gen_Admin_pageEnd();
    mysqli_close($dbc);
    ?>
    </body>

</html>