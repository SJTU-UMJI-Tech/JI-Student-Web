<!DOCTYPE html>
<?php

//应用会话内存储的变量值之前必须先开启会话
session_start();
//若是会话没有被设置，查看是否设置了cookie
require_once 'function.php';
require_once 'component.php';
$dbc = db_connect();
init_dash_web($dbc);

$career_db_field = array('career_id','description','title','mod_time','category','deadline','company','contact_email','requirements', "status");
//change member permission
function check_field_post()
{
    return isset($_POST['title']) && isset($_POST['category']) && isset($_POST['contact_email']);
}

$career_err_msg = "";
if(isset($_POST["submit"]) && check_CSRF()){
    // add new user
    if (!empty($_POST["change_type"])) {
        $my_career_id=-1;
        if ($_POST["change_type"] == "add") {
            if (check_field_post()) {
                if (check_permission_org($dbc, 'Career')) {
                    $query = "INSERT INTO Career SET description=\"". mysqli_real_escape_string($dbc, $_POST["description"])
                    ."\", title=\"". mysqli_real_escape_string($dbc, $_POST["title"])
                    ."\", deadline=\"". mysqli_real_escape_string($dbc, $_POST["deadline"])
                    ."\", company=\"". mysqli_real_escape_string($dbc, $_POST["company"])
                    ."\", category=\"". mysqli_real_escape_string($dbc, $_POST["category"])
                    ."\", contact_email=\"". mysqli_real_escape_string($dbc, $_POST["contact_email"])
                    ."\", requirements=\"". mysqli_real_escape_string($dbc, $_POST["requirements"])
                    ."\", status='unpublished', mod_time=\"". mysqli_real_escape_string($dbc, strftime("%Y-%m-%d %X", time()))
                    ."\"";
                    $data = mysqli_query($dbc,$query);
                    //echo $query;
                    dev_echo(mysqli_error($dbc));

                    $query = "SELECT career_id FROM Career WHERE title='".mysqli_real_escape_string($dbc, $_POST["title"])
                    ."' COLLATE utf8_bin AND deadline='".mysqli_real_escape_string($dbc, $_POST["deadline"])
                    ."' COLLATE utf8_bin AND description='".mysqli_real_escape_string($dbc, $_POST["description"])
                    ."' COLLATE utf8_bin AND category='".mysqli_real_escape_string($dbc, $_POST["category"])
                    ."' COLLATE utf8_bin AND company='".mysqli_real_escape_string($dbc, $_POST["company"])
                    ."' COLLATE utf8_bin";
                    $data = mysqli_query($dbc,$query);
                    dev_echo(mysqli_error($dbc));
                    if ($data) {
                        if ($data -> num_rows > 0) {
                            $tmp = $data -> fetch_array();
                            $my_career_id = $tmp[0];
                        }
                    }

                    update_att_DB($dbc, "Career", $my_career_id, 'login', true);

                } else {
                    $career_err_msg = "You do not have such permission.";
                }
            } else {
                $career_err_msg = "Parameter insufficient.";
            } 
        } else if ($_POST["change_type"] == "modify") {
            if (isset($_POST['career_id'])) {
                if (check_permission_org($dbc, 'Career')) {
                    $my_career_id = $_POST['career_id'];
                    $query = "UPDATE Career SET description=\"". mysqli_real_escape_string($dbc, $_POST["description"])
                    ."\", title=\"". mysqli_real_escape_string($dbc, $_POST["title"])
                    ."\", deadline=\"". mysqli_real_escape_string($dbc, $_POST["deadline"])
                    ."\", company=\"". mysqli_real_escape_string($dbc, $_POST["company"])
                    ."\", category=\"". mysqli_real_escape_string($dbc, $_POST["category"])
                    ."\", contact_email=\"". mysqli_real_escape_string($dbc, $_POST["contact_email"])
                    ."\", requirements=\"". mysqli_real_escape_string($dbc, $_POST["requirements"])
                    ."\", status='unpublished', mod_time=\"". mysqli_real_escape_string($dbc, strftime("%Y-%m-%d %X", time()))
                    ."\" WHERE career_id='".mysqli_real_escape_string($dbc, $_POST["career_id"])."'";
                    $data = mysqli_query($dbc,$query);
                    //echo $query;
                    dev_echo(mysqli_error($dbc));

                    update_att_DB($dbc, "Career", $my_career_id, 'login', true);
                } else {
                    $career_err_msg = "You do not have such permission.";
                }
            } else {
                $career_err_msg = "Parameter insufficient.";
            } 
        }
        if ($_POST['submit']!="my_return") {
            header("Location: ".$_SERVER["PHP_SELF"]."?action=modify&career_id=".$my_career_id."&error=".$career_err_msg);
        }
        dev_echo("save and return");
    }
}

if (isset($_GET['rmfile']) && isset($_GET['career_id']) && check_CSRF()) {
    del_file($dbc, $_GET['rmfile']);
    header("Location: ".$_SERVER["PHP_SELF"]."?action=modify&career_id=".$_GET['career_id']."&error=".$career_err_msg);
}

//change member permission
function check_field()
{
    return isset($_GET['career_id']);
}

$career_info_pre = array();
$add_or_modify = 0;
$modify_career_err_msg = "";
if (isset($_GET['action'])) {
    $my_action = $_GET['action'];
    if ($my_action == "add") {
            if (check_permission_org($dbc, "Career")) {
            $add_or_modify = 1;
            foreach ($career_db_field as $key) {
                $career_info_pre[$key]="";
            }
                $career_info_pre['status']="unpublished";
            } else {
                $modify_career_err_msg = "You do not have such permission.";
            }
    } else if ($my_action == "modify") {
        if (check_field() == true) {
            if (isset($_GET["error"])) {
                $modify_career_err_msg = $_GET["error"];
            }
            if (check_permission_org($dbc, "Career")) {
                $add_or_modify = 1;
                $query = "SELECT * FROM Career WHERE career_id='".mysqli_real_escape_string($dbc, $_GET["career_id"])
                ."' COLLATE utf8_bin ";
                $data = mysqli_query($dbc,$query);
                //echo $query;
                dev_echo(mysqli_error($dbc));
                if ($data) {
                    if ($data -> num_rows == 0) {
                        $modify_career_err_msg = "No such scholarship.";
                    } else {
                        $career_info_pre = $data->fetch_array();
                    }
                }
            } else {
                $modify_career_err_msg = "You do not have such permission.";
            }
        } else {
            $modify_career_err_msg = "Parameter insufficient.";
        }
    } else if ($my_action == "delete" && check_CSRF()) {
        if (check_field() == true) {
            if (check_permission_org($dbc, "Career")) {
                $query = "DELETE FROM Career WHERE career_id=".mysqli_real_escape_string($dbc, $_GET["career_id"]);
                $data = mysqli_query($dbc,$query);
                //echo $query;
                dev_echo(mysqli_error($dbc));

                $query = "SELECT * FROM Attachments WHERE key_id=".mysqli_real_escape_string($dbc, $_GET["career_id"])
                ." AND table_name='Career' COLLATE utf8_bin";
                $data = mysqli_query($dbc,$query);
                //echo $query;
                dev_echo(mysqli_error($dbc));
                while ($att_info = $data -> fetch_array()) {
                    unlink($att_info['file_link']);
                }
                $query = "DELETE FROM Attachments WHERE key_id=".mysqli_real_escape_string($dbc, $_GET["career_id"])
                ." AND table_name='Career' COLLATE utf8_bin";
                $data = mysqli_query($dbc,$query);
                //echo $query;
                dev_echo(mysqli_error($dbc));

            } else {
                $modify_career_err_msg = "You do not have such permission.";
            }
        } else {
            $modify_career_err_msg = "Parameter insufficient.";
        }
    } else if (($my_action == "publish" || $my_action == "unpublish")&& check_CSRF()) {
        if (check_field() == true) {
            if (check_permission($dbc, "Career")) {
                if ($my_action == "publish") {
                    $query = "UPDATE Career SET status='published' WHERE career_id=".mysqli_real_escape_string($dbc, $_GET["career_id"]);
                } else {
                    $query = "UPDATE Career SET status='unpublished' WHERE career_id=".mysqli_real_escape_string($dbc, $_GET["career_id"]);
                }
                $data = mysqli_query($dbc,$query);
                //echo $query;
                echo mysqli_error($dbc);
            } else {
                $modify_event_err_msg = "You do not have such permission.";
            }
        } else {
            $modify_event_err_msg = "Parameter insufficient.";
        }
    } else {
        $modify_career_err_msg = "Unknown aciton.";
    }
}
?>

<html class="no-js">
    
    <head>
        <?php gen_header_admin();?>
    </head>
    
    <body>
        <?php gen_navbar_admin();?>
        <div class="container-fluid">
            <div class="row-fluid">
                <?php gen_sidebar_admin(5);?>
                
                <!--/span-->
                <div class="span9" id="content">
                
                <?php // handle error msg
                err_handle($career_err_msg, "Data saved.", "submit");
                err_handle($modify_career_err_msg, "Action done.", "action");
                ?>

                <!-- add event section-->
                <?php if ($add_or_modify == 1) {
                ?>
                <div class="row-fluid">
                <!-- block -->
                        <div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php if ($_GET['action']=="add"){echo "Add";}else{echo "Modify";} ?> Career</div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <legend><?php if ($_GET['action']=="add"){echo "Add";}else{echo "Modify";} ?> Career</legend>
                                   <form method="POST" class="form-horizontal" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>">
                                    <fieldset>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Career Title</label>
                                          <div class="controls">
                                            <input type="text" class="span6 required" id="title" name="title" 
                                            placeholder="Title of the scholarship" value="<?php echo $career_info_pre['title'] ?>">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Category</label>
                                          <div class="controls">
                                            <select class="span6" name="category">
                                              <option value="fulltime">fulltime</option>
                                              <option value="internship">internship</option>.
                                              <option value="fulltime and internship">fulltime and internship</option>
                                            </select>
                                          </div>
                                        </div>
                                        
                                        <div class="control-group">
                                          <label class="control-label" for="date01">Deadline</label>
                                          <div class="controls">
                                            <input type="text" class="input-xlarge datetimepicker required"
                                            placeholder="yyyy-mm-dd HH:MM:SS" name="deadline" id="deadline" value="<?php echo $career_info_pre['deadline'] ?>">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Company</label>
                                          <div class="controls">
                                            <input type="text" class="span6" name="company" 
                                            placeholder="eg. Microsoft" value="<?php echo $career_info_pre['company'] ?>">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Contact Email </label>
                                          <div class="controls">
                                            <input type="text" class="span6 required email" id="email" name="contact_email" 
                                            placeholder="eg. example@example.com" value="<?php echo $career_info_pre['contact_email'] ?>">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="textarea2">Requirements</label>
                                          <div class="controls">
                                            <textarea id="ckeditor_standard" name="requirements" ><?php echo $career_info_pre['requirements'] ?></textarea>
                                          </div>    
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="textarea2">Description</label>
                                          <div class="controls">
                                            <textarea id="ckeditor_standard" name="description" ><?php echo $career_info_pre['description'] ?></textarea>
                                          </div>    
                                        </div>
                                        <div class="control-group">
                                          
                                          <label class="control-label" for="fileInput">Attachments</label>
                                          <div class="controls">
                                            <div style="padding-top: 5px">
                                                <?php 
                                                    $query = "SELECT * FROM Attachments WHERE key_id='".mysqli_real_escape_string($dbc, $career_info_pre['career_id'])
                                                    ."' COLLATE utf8_bin AND table_name='Career' COLLATE utf8_bin AND permission='login' COLLATE utf8_bin";
                                                    $data = mysqli_query($dbc,$query);
                                                    dev_echo(mysqli_error($dbc));
                                                    while ($one_file = $data -> fetch_array()) {
                                                    ?>
                                                    <p>
                                                    <a href="<?php echo 'file.php?file='.$one_file['file_link'].'&CSRFcheck='.$_SESSION['CSRFcheck'] ?>"><?php echo urldecode($one_file['attachment_name']); ?></a> | 
                                                    <a href="<?php echo $_SERVER['PHP_SELF'].'?career_id='.$career_info_pre['career_id'].'&rmfile='.$one_file['file_link'].'&CSRFcheck='.$_SESSION['CSRFcheck']; ?>">Remove</a>
                                                    </p>
                                                    <?php
                                                    }
                                                ?>
                                                <hr>
                                            </div>
                                            <input class="input-file uniform_on" id="fileInput" type="file" name="attachment">
                                            <button type="submit" class="btn btn-default" name="submit">upload</button>
                                          </div>
                                        </div>

                                        <div class="control-group">
                                          <label class="control-label" for="textarea2">Status</label>
                                          <div class="controls">
                                            <input type="text" id="" name="" disabled="" 
                                            placeholder="" value="<?php echo $career_info_pre['status'] ?>">                                          </div>    
                                        </div>

                                        <div class="form-actions">
                                          <input type="hidden" name="CSRFcheck" value="<?php echo $_SESSION['CSRFcheck']; ?>">
                                          <input type="hidden" name="change_type" value="<?php if($career_info_pre['title']==""){echo "add";}else{echo "modify";} ?>">
                                          <input type="hidden" name="career_id" value="<?php echo $career_info_pre['career_id'] ?>">
                                          <button type="submit" class="btn btn-primary" name="submit">Save</button>
                                          <button type="submit" class="btn btn-primary" name="submit" value="my_return">Save and return</button>
                                          <a href="<?php echo $_SERVER["PHP_SELF"];?>"><button class="btn">Cancel</button></a>
                                        </div>
                                        </fieldset>
                                    </form>
                                    <hr>
                                    <legend>Applicants</legend>

                                    <div class="table-toolbar">
                                      <div class="btn-group pull-right">
                                         <button data-toggle="dropdown" class="btn dropdown-toggle">Tools <span class="caret"></span></button>
                                         <ul class="dropdown-menu">
                                            <li><a href="javascript:$('#table1').tableExport({type: 'pdf', escape: 'false'});">Save as PDF</a></li>
                                            <li><a href="javascript:$('#table1').tableExport({type: 'excel', escape: 'false'});">Export to Excel</a></li>
                                         </ul>
                                      </div>
                                   </div>
                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered example_table" id="table1">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>ID</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Files</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            static $odd_even = 0;
                                            $he_is_admin = 0;
                                            $reg_info = array();
                                            $new_query = "SELECT * FROM Career_reg WHERE career_id='".$career_info_pre['career_id']."' COLLATE utf8_bin ORDER BY stuID";
                                            $new_result = mysqli_query($dbc,$new_query);
                                            //echo $new_query;
                                            dev_echo(mysqli_error($dbc));
                                            if ($new_result) {
                                              while($reg_info = $new_result->fetch_array())
                                              { ?>

                                                    <tr class="<?php if($odd_even==0) {echo "odd gradeX";}else{echo "even gradeC";}?>">
                                                        <td class="center"><?php echo $reg_info['username'];?></td>
                                                        <td class="center"><?php echo $reg_info['stuID'];?></td>
                                                        <td class="center"><?php echo $reg_info['phone'];?></td>
                                                        <td class="center"><a href="mailto:<?php echo $reg_info['email'];?>"><?php echo $reg_info['email'];?></a></td>
                                                        <td class="center">
                                                            <?php 
                                                                $query = "SELECT * FROM Attachments WHERE key_id='".mysqli_real_escape_string($dbc, $career_info_pre['career_id'])
                                                                ."' COLLATE utf8_bin AND table_name='Career' COLLATE utf8_bin AND permission='org' COLLATE utf8_bin"
                                                                ." AND upload_by_user_id='".$reg_info['stuID']."'";
                                                                $data = mysqli_query($dbc,$query);
                                                                dev_echo(mysqli_error($dbc));
                                                                while ($one_file = $data -> fetch_array()) {
                                                                ?>
                                                                    <a href="<?php echo 'file.php?file='.$one_file['file_link'].'&CSRFcheck='.$_SESSION['CSRFcheck'] ?>"><?php echo urldecode($one_file['attachment_name']); ?></a> | 
                                                                <?php
                                                                }

                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }?>
                                            
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                        <!-- /block -->
                    </div>
                    <?php 
                } ?>

                    <div class="row-fluid"> 
                        <!-- block -->
                        <div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left">Career</div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <legend>Career</legend>
                                   <div class="table-toolbar">
                                      <div class="btn-group">
                                        <a href="<?php echo $_SERVER["PHP_SELF"]."?action=add";?>">
                                            <button type="submit" name="submit" class="btn btn-success">Add Career info<i class="icon-plus icon-white"></i></button>
                                        </a>
                                      </div>
                                      <div class="btn-group pull-right">
                                         <button data-toggle="dropdown" class="btn dropdown-toggle">Tools <span class="caret"></span></button>
                                         <ul class="dropdown-menu">
                                            <li><a href="javascript:$('#table2').tableExport({type: 'pdf', escape: 'false'});">Save as PDF</a></li>
                                            <li><a href="javascript:$('#table2').tableExport({type: 'excel', escape: 'false'});">Export to Excel</a></li>
                                         </ul>
                                      </div>
                                   </div>
                                    
                                    <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered example_table" id="table2">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Deadline</th>
                                                <th>Contact</th>
                                                <th>Company</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            static $odd_even = 0;
                                            $he_is_admin = 0;
                                            $career_info = array();
                                            $new_query = "SELECT * FROM Career ORDER BY mod_time";
                                            $new_result = mysqli_query($dbc,$new_query);
                                            if ($new_result) {
                                              while($career_info = $new_result->fetch_array())
                                              { ?>

                                                    <tr class="<?php if($odd_even==0) {echo "odd gradeX";}else{echo "even gradeC";}?>">
                                                        <td class="center"><a href="<?php echo $_SERVER["PHP_SELF"]."?action=modify&career_id=".$career_info['career_id'];?>"><?php echo $career_info['title'];?></a></td>
                                                        <td class="center"><?php echo $career_info['deadline'];?></td>
                                                        <td class="center"><?php echo $career_info['contact_email'];?></td>
                                                        <td class="center"><?php echo $career_info['company'];?></td>
                                                        <td class="center">
                                                            <a href="<?php echo $_SERVER["PHP_SELF"]."?action=modify&career_id=".$career_info['career_id'];?>">Details</a> /
                                                            <a href="<?php echo $_SERVER["PHP_SELF"]."?action=delete&career_id=".$career_info['career_id']."&CSRFcheck=".$_SESSION['CSRFcheck'];?>">Delete</a>
                                                            <?php
                                                            if (check_permission($dbc, 'Career')) { ?>
                                                               / <a href="<?php echo $_SERVER["PHP_SELF"]."?action="?><?php if($career_info['status'] == "published") {echo "unpublish";} else {echo "publish";}?><?php echo "&career_id=".$career_info['career_id']."&CSRFcheck=".$_SESSION['CSRFcheck'];?>">
                                                                <?php if($career_info['status'] == "published") {echo "Unpublish";} else {echo "Publish";}?>
                                                                </a>
                                                            <?php }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }?>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- /block -->
                    </div>
            </div>
        </div>
        <hr>
        <?php gen_footer_admin();?>
        <!--/.fluid-container-->
    <?php 
    gen_Admin_pageEnd();
    mysqli_close($dbc);
    ?>
    </body>

</html>