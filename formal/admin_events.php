<!DOCTYPE html>
<?php

//应用会话内存储的变量值之前必须先开启会话
session_start();
//若是会话没有被设置，查看是否设置了cookie
include 'function.php';
include 'component.php';
$dbc = db_connect();
init_dash_web($dbc);

$event_db_field=array("event_id", "title","time","status", "end_time","location","org_name","manager","description","short_description","photo_link","tag","reg_link","contact_email","speaker","budget","budget_file_link");

//change member permission
function check_field_post()
{
    return isset($_POST['event_title']) && isset($_POST['event_time']) && isset($_POST['event_location']) && isset($_POST['org_name']);
}

$event_err_msg = "";
if(isset($_POST["submit"]) && check_CSRF()){
    // add new user
    if (!empty($_POST["change_type"])) {
        function tmp() {if(($_POST["event_budget"]!="")){return ($_POST["event_budget"]);}else{return "0";}}
        $my_event_id=-1;
        if ($_POST["change_type"] == "add") {
            if (check_field_post()) {
                //var_dump($_POST);
                if (check_permission_org($dbc, $_POST['org_name'])) {
                    $query = "SELECT * FROM Events WHERE title='".mysqli_real_escape_string($dbc, $_POST["event_title"])
                    ."' COLLATE utf8_bin AND org_name='".mysqli_real_escape_string($dbc, $_POST["org_name"])
                    ."' COLLATE utf8_bin AND location='".mysqli_real_escape_string($dbc, $_POST["event_location"])
                    ."' COLLATE utf8_bin AND time='".mysqli_real_escape_string($dbc, $_POST["event_time"])
                    ."' COLLATE utf8_bin";
                    $data = mysqli_query($dbc,$query);
                    echo mysqli_error($dbc);
                    if ($data) {
                        if ($data->num_rows > 0) {
                            $event_err_msg="This event already exists.";
                        } else {
                            $query = "INSERT INTO Events SET time=\"". mysqli_real_escape_string($dbc, $_POST["event_time"])
                            ."\", location=\"". mysqli_real_escape_string($dbc, $_POST["event_location"])
                            ."\", org_name=\"". mysqli_real_escape_string($dbc, $_POST["org_name"])
                            ."\", title=\"". mysqli_real_escape_string($dbc, $_POST["event_title"])
                            ."\", description=\"". mysqli_real_escape_string($dbc, $_POST["event_des"])
                            ."\", short_description=\"". mysqli_real_escape_string($dbc, $_POST["event_short_des"])
                            ."\", tag=\"". mysqli_real_escape_string($dbc, $_POST["event_tag"])
                            ."\", manager=\"". mysqli_real_escape_string($dbc, $_POST["event_maneger"])
                            ."\", contact_email=\"". mysqli_real_escape_string($dbc, $_POST["event_contact_email"])
                            ."\", status=\"unpublished\", budget=". mysqli_real_escape_string($dbc, tmp())
                            .", speaker=\"". mysqli_real_escape_string($dbc, $_POST["event_speaker"])
                            ."\", end_time=\"". mysqli_real_escape_string($dbc, $_POST["event_end_time"])
                            ."\"";
                            $data = mysqli_query($dbc,$query);
                            //echo $query;
                            echo mysqli_error($dbc);

                            $query = "SELECT event_id FROM Events WHERE title='".mysqli_real_escape_string($dbc, $_POST["event_title"])
                            ."' COLLATE utf8_bin AND org_name='".mysqli_real_escape_string($dbc, $_POST["org_name"])
                            ."' COLLATE utf8_bin AND location='".mysqli_real_escape_string($dbc, $_POST["event_location"])
                            ."' COLLATE utf8_bin AND time='".mysqli_real_escape_string($dbc, $_POST["event_time"])
                            ."' COLLATE utf8_bin";
                            $data = mysqli_query($dbc,$query);
                            echo mysqli_error($dbc);
                            if ($data) {
                                if ($data -> num_rows >0) {
                                    $tmp = $data -> fetch_array();
                                    $my_event_id = $tmp[0];
                                }
                            }
                            $budget_file_link = get_file_addr("budget_file", "budget_event_", $my_event_id);
                            $photo_link = get_photo_addr("event_photo", "event_", $my_event_id);
                            if ($photo_link["new_file"]) {
                                $query = "UPDATE Events SET photo_link=\"". mysqli_real_escape_string($dbc, $photo_link["content"])
                                ."\" WHERE event_id='".mysqli_real_escape_string($dbc, $my_event_id)."'";
                                $data = mysqli_query($dbc,$query);
                                //echo $query;
                                echo mysqli_error($dbc);
                            }
                            if ($budget_file_link["new_file"]) {
                                $query = "UPDATE Events SET budget_file_link=\"". mysqli_real_escape_string($dbc, $budget_file_link["content"])
                                ."\" WHERE event_id='".mysqli_real_escape_string($dbc, $my_event_id)."'";
                                $data = mysqli_query($dbc,$query);
                                //echo $query;
                                echo mysqli_error($dbc);
                            }
                            $event_err_msg .= $budget_file_link["error"];
                            $event_err_msg .= $photo_link["error"];

                            update_att_DB($dbc, "Events", $my_event_id, 'login', true);

                        }
                    }
                } else {
                    $event_err_msg = "You do not have such permission.";
                }
            } else {
                $event_err_msg = "Parameter insufficient.";
            } 
        } else if ($_POST["change_type"] == "modify") {
            if (isset($_POST['event_id'])) {
                if (check_permission_id($dbc, $_POST['event_id'])) {
                    $my_event_id = $_POST['event_id'];
                    $budget_file_link = get_file_addr("budget_file", "budget_event_", $my_event_id);
                    $photo_link = get_photo_addr("event_photo", "event_", $_POST['event_id']);
                    $query = "UPDATE Events SET time=\"". mysqli_real_escape_string($dbc, $_POST["event_time"])
                    ."\", location=\"". mysqli_real_escape_string($dbc, $_POST["event_location"])
                    ."\", org_name=\"". mysqli_real_escape_string($dbc, $_POST["org_name"])
                    ."\", title=\"". mysqli_real_escape_string($dbc, $_POST["event_title"])
                    ."\", description=\"". mysqli_real_escape_string($dbc, $_POST["event_des"])
                    ."\", short_description=\"". mysqli_real_escape_string($dbc, $_POST["event_short_des"])
                    ."\", tag=\"". mysqli_real_escape_string($dbc, $_POST["event_tag"])
                    ."\", manager=\"". mysqli_real_escape_string($dbc, $_POST["event_maneger"])
                    ."\", contact_email=\"". mysqli_real_escape_string($dbc, $_POST["event_contact_email"])
                    ."\", status=\"unpublished\", budget=". mysqli_real_escape_string($dbc, tmp())
                    .", speaker=\"". mysqli_real_escape_string($dbc, $_POST["event_speaker"])
                    ."\", end_time=\"". mysqli_real_escape_string($dbc, $_POST["event_end_time"])
                    ."\" WHERE event_id='".mysqli_real_escape_string($dbc, $_POST["event_id"])."'";
                    $data = mysqli_query($dbc,$query);
                    //echo $query;
                    echo mysqli_error($dbc);
                    if ($photo_link["new_file"]) {
                        $query = "UPDATE Events SET photo_link=\"". mysqli_real_escape_string($dbc, $photo_link["content"])
                        ."\" WHERE event_id='".mysqli_real_escape_string($dbc, $_POST["event_id"])."'";
                        $data = mysqli_query($dbc,$query);
                        //echo $query;
                        echo mysqli_error($dbc);
                    }
                    if ($budget_file_link["new_file"]) {
                        $query = "UPDATE Events SET budget_file_link=\"". mysqli_real_escape_string($dbc, $budget_file_link["content"])
                        ."\" WHERE event_id='".mysqli_real_escape_string($dbc, $_POST["event_id"])."'";
                        $data = mysqli_query($dbc,$query);
                        //echo $query;
                        echo mysqli_error($dbc);
                    }
                    $event_err_msg .= $budget_file_link["error"];
                    $event_err_msg .= $photo_link["error"];

                    update_att_DB($dbc, "Events", $my_event_id, 'login', true);

                } else {
                    $event_err_msg = "You do not have such permission.";
                }
            } else {
                $event_err_msg = "Parameter insufficient.";
            } 
        }
        if ($_POST['submit']!="my_return") {
            header("Location: ".$_SERVER["PHP_SELF"]."?action=modify&event_id=".$my_event_id."&error=".$event_err_msg);
        }
        //echo "save and return";
    }
}

if (isset($_GET['removephoto']) && !empty($_GET['event_id'])) {
    if (check_permission_id($dbc, $_GET['event_id'])) {
        $my_event_id = $_GET['event_id'];
        $query = "SELECT photo_link FROM Events WHERE event_id='".mysqli_real_escape_string($dbc, $_GET["event_id"])."'";
        $data = mysqli_query($dbc,$query);
        dev_echo(mysqli_error($dbc));
        //echo $query;
        $info = $data -> fetch_array();
        unlink($info[0]);
        
        $query = "UPDATE Events SET photo_link='' WHERE event_id='".mysqli_real_escape_string($dbc, $_GET["event_id"])."'";
        $data = mysqli_query($dbc,$query);
        dev_echo(mysqli_error($dbc));
        //echo $query;
    } else {
        $event_err_msg = "You do not have such permission.";
    }
    header("Location: ".$_SERVER["PHP_SELF"]."?action=modify&event_id=".$my_event_id."&error=".$event_err_msg);
}

if (isset($_GET['rmfile']) && isset($_GET['event_id']) && check_CSRF()) {
    del_file($dbc, $_GET['rmfile']);
    header("Location: ".$_SERVER["PHP_SELF"]."?action=modify&event_id=".$_GET['event_id']."&error=".$event_err_msg);
}

//change member permission
function check_field()
{
    return isset($_GET['event_id']);
}

function check_permission_id($dbc, $check_id)
{
    $query = "SELECT * FROM Member WHERE stuID=\"".mysqli_real_escape_string($dbc, $_SESSION["user_id"])
    ."\" COLLATE utf8_bin AND belong_org IN (SELECT org_name AS belong_org FROM Events WHERE event_id=".mysqli_real_escape_string($dbc, $check_id).")";
    $data = mysqli_query($dbc,$query);
    //echo $query;
    echo mysqli_error($dbc);
    if ($data) {
        if ($data -> num_rows > 0) {
            return true;
        }
    }
    return false;
}

$event_info_pre = array();
$add_or_modify = 0;
$modify_event_err_msg = "";
if (isset($_GET['action'])) {
    $my_action = $_GET['action'];
    if ($my_action == "add_event") {
        if (isset($_GET['org'])) {
            if (check_permission_org($dbc, $_GET['org']) == true) {
            $add_or_modify = 1;
            foreach ($event_db_field as $key) {
                $event_info_pre[$key]="";
            }
            $event_info_pre['status']="unpublished";
            $event_info_pre["org_name"]=$_GET['org'];
            } else {
                $modify_event_err_msg = "You do not have such permission.";
            }
        } else {
            $modify_event_err_msg = "Parameter insufficient.";
        }
    } else if ($my_action == "modify") {
        if (check_field() == true) {
            if (isset($_GET["error"])) {
                $modify_event_err_msg = $_GET["error"];
            }
            if (check_permission_id($dbc, $_GET['event_id']) == true) {
                $add_or_modify = 1;
                $query = "SELECT * FROM Events WHERE event_id=".mysqli_real_escape_string($dbc, $_GET["event_id"]);
                $data = mysqli_query($dbc,$query);
                //echo $query;
                echo mysqli_error($dbc);
                if ($data) {
                    if ($data -> num_rows == 0) {
                        $modify_event_err_msg = "No such event.";
                    } else {
                        $event_info_pre = $data->fetch_array();
                    }
                }
            } else {
                $modify_event_err_msg = "You do not have such permission.";
            }
        } else {
            $modify_event_err_msg = "Parameter insufficient.";
        }
    } else if ($my_action == "delete" && check_CSRF()) {
        if (check_field() == true) {
            if (check_permission_id($dbc, $_GET['event_id']) == true) {
                $query = "DELETE FROM Events WHERE event_id=".mysqli_real_escape_string($dbc, $_GET["event_id"]);
                $data = mysqli_query($dbc,$query);
                //echo $query;
                echo mysqli_error($dbc);
            } else {
                $modify_event_err_msg = "You do not have such permission.";
            }
        } else {
            $modify_event_err_msg = "Parameter insufficient.";
        }
    } else if ($my_action == "publish" && check_CSRF()) {
        if (check_field() == true) {
            if (check_permission_id($dbc, $_GET['event_id']) == true) {
                $query = "UPDATE Events SET status='published' WHERE event_id=".mysqli_real_escape_string($dbc, $_GET["event_id"])
                ." AND org_name IN (SELECT org_name FROM Admin_users WHERE boss_stuID='".$_SESSION['user_id']."' COLLATE utf8_bin)";
                $data = mysqli_query($dbc,$query);
                //echo $query;
                echo mysqli_error($dbc);
            } else {
                $modify_event_err_msg = "You do not have such permission.";
            }
        } else {
            $modify_event_err_msg = "Parameter insufficient.";
        }
    } else if ($my_action == "unpublish" && check_CSRF()) {
        if (check_field() == true) {
            if (check_permission_id($dbc, $_GET['event_id']) == true) {
                $query = "UPDATE Events SET status='unpublished' WHERE event_id=".mysqli_real_escape_string($dbc, $_GET["event_id"])
                ." AND org_name IN (SELECT org_name FROM Admin_users WHERE boss_stuID='".$_SESSION['user_id']."' COLLATE utf8_bin)";
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
        $modify_event_err_msg = "Unknown aciton.";
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
                <?php gen_sidebar_admin(3);?>
                
                <!--/span-->
                <div class="span9" id="content">

                <?php // handle error msg
                err_handle($event_err_msg, "Data saved.", "submit");
                err_handle($modify_event_err_msg, "Action done.", "action");
                ?>

                <!-- add event section-->
                <?php if ($add_or_modify == 1) {
                ?>
                <div class="row-fluid">
                <!-- block -->
                        <div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left"><?php if ($_GET['action']=="add_event"){echo "Add";}else{echo "Modify";} ?> Event</div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <legend><?php echo $event_info_pre["org_name"]?></legend>
                                   <form method="POST" class="form-horizontal" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>">
                                    <fieldset>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Event Title </label>
                                          <div class="controls">
                                            <input type="text" class="span6 required" id="title" name="event_title" 
                                            placeholder="Title of your event" value="<?php echo $event_info_pre['title'] ?>">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="date01">Begin</label>
                                          <div class="controls">
                                            <input type="text" class="input-xlarge datetimepicker required"
                                            placeholder="yyyy-mm-dd HH:MM:SS" id="begin_time" name="event_time" value="<?php echo $event_info_pre['time'] ?>">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="date01">End</label>
                                          <div class="controls">
                                            <input type="text" class="input-xlarge datetimepicker" 
                                            placeholder="yyyy-mm-dd HH:MM:SS" name="event_end_time" value="<?php echo $event_info_pre['end_time'] ?>">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Location </label>
                                          <div class="controls">
                                            <input type="text" class="span6 required" id="location" name="event_location" 
                                            placeholder="eg. Room 415, JI Building" value="<?php echo $event_info_pre['location'] ?>">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Guest Speaker </label>
                                          <div class="controls">
                                            <input type="text" class="input-xlarge" name="event_speaker" 
                                            placeholder="FirstName LastName" value="<?php echo $event_info_pre['speaker'] ?>">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Event Manager </label>
                                          <div class="controls">
                                            <input type="text" class="input-xlarge required" id="manager" name="event_maneger" 
                                            placeholder="FirstName LastName" value="<?php echo $event_info_pre['manager'] ?>">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Event Manager's Email </label>
                                          <div class="controls">
                                            <input type="text" class="span6 required email" id="manager_email" name="event_contact_email" 
                                            placeholder="eg. example@example.com" value="<?php echo $event_info_pre['contact_email'] ?>">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Event Tag </label>
                                          <div class="controls">
                                            <input type="text" class="span6" name="event_tag" 
                                            placeholder="Some tags" value="<?php echo $event_info_pre['tag'] ?>">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="fileInput">Photo</label>
                                          <div class="controls">
                                            <?php if ($event_info_pre['photo_link'] != ""): ?>
                                            <img src="<?php echo $event_info_pre['photo_link'] ?>">
                                            |
                                            <a href="<?php echo $_SERVER['PHP_SELF'].'?event_id='.$event_info_pre['event_id'].'&removephoto=true' ?>">Remove</a>
                                            <?php endif ?>
                                            <hr>
                                            <input class="input-file uniform_on" id="fileInput" type="file" name="event_photo">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="textarea2">Short Description</label>
                                          <div class="controls">
                                            <textarea id="ckeditor_standard" name="event_short_des" ><?php echo $event_info_pre['short_description'] ?></textarea>
                                          </div>    
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="textarea2">Description</label>
                                          <div class="controls">
                                            <textarea id="ckeditor_standard" name="event_des" ><?php echo $event_info_pre['description'] ?></textarea>
                                          </div>    
                                        </div>

                                        <div class="control-group">
                                          <label class="control-label" for="fileInput">Attachments</label>
                                          <div class="controls">
                                            <div style="padding-top: 5px">
                                                <?php 
                                                    $query = "SELECT * FROM Attachments WHERE key_id='".mysqli_real_escape_string($dbc, $event_info_pre['event_id'])
                                                    ."' COLLATE utf8_bin AND table_name='Events' COLLATE utf8_bin AND permission='login' COLLATE utf8_bin";
                                                    $data = mysqli_query($dbc,$query);
                                                    dev_echo(mysqli_error($dbc));
                                                    while ($one_file = $data -> fetch_array()) {
                                                    ?>
                                                    <p>
                                                    <a href="<?php echo 'file.php?file='.$one_file['file_link'].'&CSRFcheck='.$_SESSION['CSRFcheck'] ?>"><?php echo urldecode($one_file['attachment_name']); ?></a> | 
                                                    <a href="<?php echo $_SERVER['PHP_SELF'].'?event_id='.$event_info_pre['event_id'].'&rmfile='.$one_file['file_link'].'&CSRFcheck='.$_SESSION['CSRFcheck']; ?>">Remove</a>
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
                                          <label class="control-label" for="typeahead">Budget </label>
                                          <div class="controls">
                                            <input type="text" class="input-xlarge" name="event_budget" 
                                            placeholder="Numerical only, in CNY" value="<?php echo $event_info_pre['budget'] ?>">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          
                                          <label class="control-label" for="fileInput">Budget File</label>
                                          <div class="controls">
                                            <div style="padding-top: 5px">
                                                <?php if ($event_info_pre['budget_file_link'] != ""): ?>
                                                    <a href="<?php echo $event_info_pre['budget_file_link'] ?>">
                                                        Budget File
                                                    </a><hr>
                                                <?php endif ?>
                                            </div>
                                            <input class="input-file uniform_on" id="fileInput" type="file" name="budget_file">
                                          </div>
                                        </div>

                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Status </label>
                                          <div class="controls">
                                            <input type="text" disabled="" class="input-xlarge" value="<?php if($event_info_pre['status'] == "") {echo "unpublished";} else {echo $event_info_pre['status'];}?>">
                                          </div>
                                        </div>

                                        <div class="form-actions">
                                          <input type="hidden" name="CSRFcheck" value="<?php echo $_SESSION['CSRFcheck'] ?>">
                                          <input type="hidden" name="org_name" value="<?php echo $event_info_pre["org_name"]?>">
                                          <input type="hidden" name="change_type" value="<?php if($event_info_pre['title']==""){echo "add";}else{echo "modify";} ?>">
                                          <input type="hidden" name="event_id" value="<?php echo $event_info_pre['event_id'] ?>">
                                          <button type="submit" class="btn btn-primary" name="submit">Save</button>
                                          <button type="submit" class="btn btn-primary" name="submit" value="my_return">Save and return</button>
                                          <a href="<?php echo $_SERVER["PHP_SELF"];?>"><button class="btn">Cancel</button></a>
                                        </div>
                                        </fieldset>
                                    </form>
                                    <hr>
                                    <legend>Registered people</legend>

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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            static $odd_even = 0;
                                            $he_is_admin = 0;
                                            $reg_info = array();
                                            $new_query = "SELECT * FROM Event_reg WHERE event_id='".$event_info_pre['event_id']."' COLLATE utf8_bin ORDER BY userID";
                                            $new_result = mysqli_query($dbc,$new_query);
                                            //echo $new_query;
                                            echo mysqli_error($dbc);
                                            if ($new_result) {
                                              while($reg_info = $new_result->fetch_array())
                                              { ?>

                                                    <tr class="<?php if($odd_even==0) {echo "odd gradeX";}else{echo "even gradeC";}?>">
                                                        <td class="center"><?php echo $reg_info['name'];?></td>
                                                        <td class="center"><?php echo $reg_info['userID'];?></td>
                                                        <td class="center"><?php echo $reg_info['phone'];?></td>
                                                        <td class="center"><a href="mailto:<?php echo $reg_info['email'];?>"><?php echo $reg_info['email'];?></a></td>
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

                <?php
                    $query = "SELECT DISTINCT belong_org FROM Member WHERE stuID='".$_SESSION["user_id"]."' COLLATE utf8_bin ORDER BY belong_org";
                    $result = mysqli_query($dbc,$query);
                    echo mysqli_error($dbc);
                        if ($result) {
                            while($row_org =$result->fetch_array())
                            {
                            ?>
                    <div class="row-fluid"> 
                        <!-- block -->
                        <div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left">Events</div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <legend><?php echo $row_org['belong_org']; ?></legend>
                                   <div class="table-toolbar">
                                      <div class="btn-group">
                                        <a href="<?php echo $_SERVER["PHP_SELF"]."?action=add_event&org=".$row_org['belong_org'];?>">
                                            <button type="submit" name="submit" class="btn btn-success">Add Event<i class="icon-plus icon-white"></i></button>
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
                                                <th>Time</th>
                                                <th>Title</th>
                                                <th>Location</th>
                                                <th>Manager</th>
                                                <th>Budget</th>
                                                <th>No. Reg.</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            static $odd_even = 0;
                                            $he_is_admin = 0;
                                            $event_info = array();
                                            $new_query = "SELECT * FROM Events WHERE org_name='".$row_org['belong_org']."' COLLATE utf8_bin ORDER BY time";
                                            $new_result = mysqli_query($dbc,$new_query);
                                            if ($new_result) {
                                              while($event_info = $new_result->fetch_array())
                                              { 
                                                $new2_query = "SELECT * FROM Event_reg WHERE event_id=".$event_info['event_id']." ORDER BY userID";
                                                $new2_result = mysqli_query($dbc,$new2_query);
                                                ?>

                                                    <tr class="<?php if($odd_even==0) {echo "odd gradeX";}else{echo "even gradeC";}?>">
                                                        <td class="center"><?php echo $event_info['time'];?></td>
                                                        <td class="center"><a href="<?php echo $_SERVER["PHP_SELF"]."?action=modify&event_id=".$event_info['event_id'];?>"><?php echo $event_info['title'];?></a></td>
                                                        <td class="center"><?php echo $event_info['location'];?></td>
                                                        <td class="center"><?php echo $event_info['manager'];?></td>
                                                        <td class="center"><?php echo $event_info['budget'];?></td>
                                                        <td class="center"><?php echo $new2_result -> num_rows;?></td>
                                                        <td class="center">
                                                            <a href="<?php echo $_SERVER["PHP_SELF"]."?action=modify&event_id=".$event_info['event_id'];?>">Details</a> /
                                                            <a href="<?php echo $_SERVER["PHP_SELF"]."?action=delete&event_id=".$event_info['event_id']."&CSRFcheck=".$_SESSION['CSRFcheck'];?>">Delete</a>
                                                            <?php
                                                            if (check_permission($dbc, $row_org['belong_org'])) { ?>
                                                               / <a href="<?php echo $_SERVER["PHP_SELF"]."?action="?><?php if($event_info['status'] == "published") {echo "unpublish";} else {echo "publish";}?><?php echo "&event_id=".$event_info['event_id']."&CSRFcheck=".$_SESSION['CSRFcheck'];?>">
                                                                <?php if($event_info['status'] == "published") {echo "Unpublish";} else {echo "Publish";}?>
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
                    <?php
                    }
                }?>
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