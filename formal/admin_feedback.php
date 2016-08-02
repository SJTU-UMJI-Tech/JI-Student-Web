<!DOCTYPE html>
<?php
ini_set('display_errors',1);            //错误信息
            ini_set('display_startup_errors',1);    //php启动错误信息
            error_reporting(-1); 
//应用会话内存储的变量值之前必须先开启会话
session_start();
//若是会话没有被设置，查看是否设置了cookie
include 'function.php';
include 'component.php';
include 'smtp_send.php';
$dbc = db_connect();
init_dash_web($dbc);

$feedback_db_field=array("ticket_id","time","to_org","from_username","from_stuID","from_email","replied_with","status","ticket_id","content");

//change member permission
function check_field_post()
{
    return isset($_POST['replied_with']) && isset($_POST['ticket_id']);
}

$reply_err_msg = "";
if(isset($_POST["submit"]) && check_CSRF()){
    // add new user
    $my_ticket_id = -1;
    $gogogo = 0;
    $our_reply = $_POST["our_reply"];
    if (isset($_POST['ticket_id'])) {
        if (check_permission_id($dbc, $_POST['ticket_id'])) {
            $gogogo = 1;
            $my_ticket_id = $_POST['ticket_id'];
            $query = "UPDATE Feedback SET replied_with=\"". mysqli_real_escape_string($dbc, $our_reply)
            ."\" WHERE ticket_id='".mysqli_real_escape_string($dbc, $_POST["ticket_id"])."'";
            $data = mysqli_query($dbc,$query);
            //echo $query;
            echo mysqli_error($dbc);
        } else {
            $gogogo = 0;
            $reply_err_msg = "You do not have such permission.";
        }
    } else {
        $gogogo = 0;
        $reply_err_msg = "Parameter insufficient.";
    } 

    if ($_POST['submit']=="save") {
        header("Location: ".$_SERVER["PHP_SELF"]."?action=reply&ticket_id=".$my_ticket_id."&error=".$reply_err_msg."&CSRFcheck=".$_SESSION['CSRFcheck']);
    } else if ($_POST['submit']=="send_reply") {
        if ($gogogo == 1) {
            $query = "UPDATE Feedback SET status='replied' WHERE ticket_id='".mysqli_real_escape_string($dbc, $_POST["ticket_id"])."'";
            $data = mysqli_query($dbc,$query);
            //echo $query;
            echo mysqli_error($dbc);
            $query = "SELECT * FROM Feedback WHERE ticket_id='".mysqli_real_escape_string($dbc, $_POST["ticket_id"])."'";
            $data = mysqli_query($dbc,$query);
            //echo $query;
            echo mysqli_error($dbc);
            if ($data) {
                $feedback_info = $data->fetch_array();
            }

            //construct email
            $email_content = "Dear ".$feedback_info['from_username'].",</br></br>".
            $our_reply."</br></br>------------------</br>".
            "Your original feedback ticket:</br></br>"."<b>From:</b> ".$feedback_info['from_username'].
            "</br><b>To:</b> ".$feedback_info['to_org']."</br><b>On:</b> ".$feedback_info['time'].
            "</br></br>".$feedback_info['content']."</br>------------------</br></br>".
            "Thank you for your feedback!</br></br>Sinerely,</br>".$feedback_info['to_org'];
            //php send email
            send_one_email('notification', $feedback_info['from_email'], "Re:Feedback ticket ID No.".$feedback_info['ticket_id'], $email_content);
        }
    }
    //echo "save and return";
}

//change member permission
function check_field()
{
    return isset($_GET['ticket_id']);
}

function check_permission_id($dbc, $check_id)
{
    $query = "SELECT * FROM Member WHERE stuID=\"".mysqli_real_escape_string($dbc, $_SESSION["user_id"])
    ."\" COLLATE utf8_bin AND belong_org IN (SELECT to_org AS belong_org FROM Feedback WHERE ticket_id=".mysqli_real_escape_string($dbc, $check_id).")";
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

$feedback_info_pre = array();
$add_or_modify = 0;
$modify_feedback_err_msg = "";
if (isset($_GET['action'])) {
    $my_action = $_GET['action'];
    if ($my_action == "reply" && check_CSRF()) {
        if (check_field() == true) {
            if (isset($_GET["error"])) {
                $modify_feedback_err_msg = $_GET["error"];
            }
            if (check_permission_id($dbc, $_GET['ticket_id']) == true) {
                $add_or_modify = 1;
                $query = "SELECT * FROM Feedback WHERE ticket_id='".mysqli_real_escape_string($dbc, $_GET["ticket_id"])
                ."' COLLATE utf8_bin ";
                $data = mysqli_query($dbc,$query);
                //echo $query;
                echo mysqli_error($dbc);
                if ($data) {
                    if ($data -> num_rows == 0) {
                        $modify_feedback_err_msg = "No such event.";
                    } else {
                        $feedback_info_pre = $data->fetch_array();
                    }
                }
            } else {
                $modify_feedback_err_msg = "You do not have such permission.";
            }
        } else {
            $modify_feedback_err_msg = "Parameter insufficient.";
        }
    } else if ($my_action == "delete") {
        /*if (check_field() == true) {
            if (check_permission_id($dbc, $_GET['ticket_id']) == true) {
                $query = "DELETE FROM Feedback WHERE ticket_id=".mysqli_real_escape_string($dbc, $_GET["ticket_id"]);
                $data = mysqli_query($dbc,$query);
                //echo $query;
                echo mysqli_error($dbc);
            } else {
                $modify_feedback_err_msg = "You do not have such permission.";
            }
        } else {
            $modify_feedback_err_msg = "Parameter insufficient.";
        }*/
    } else {
        $modify_feedback_err_msg = "Unknown aciton.";
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
                <?php gen_sidebar_admin(4);?>
                
                <!--/span-->
                <div class="span9" id="content">

                <?php // handle error msg
                err_handle($reply_err_msg, "Data saved.", "submit");
                err_handle($modify_feedback_err_msg, "Action done.", "action");
                ?>

                <!-- add event section-->
                <?php if ($add_or_modify == 1) {
                ?>
                <div class="row-fluid">
                <!-- block -->
                        <div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left">Feedbacks</div>
                            </div>
                            
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <legend><?php echo $feedback_info_pre["to_org"]?></legend>
                                   <form method="POST" class="form-horizontal" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>">
                                    <fieldset>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">From </label>
                                          <div class="controls" style="margin-top: 5px">
                                            <?php echo $feedback_info_pre['from_username'] ?>
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="date01">User ID</label>
                                          <div class="controls" style="margin-top: 5px">
                                            <?php echo $feedback_info_pre['from_stuID'] ?>
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="date01">At</label>
                                          <div class="controls" style="margin-top: 5px">
                                            <?php echo $feedback_info_pre['time'] ?>
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Email</label>
                                          <div class="controls" style="margin-top: 5px">
                                            <a href=mailto:'<?php echo $feedback_info_pre['from_email'] ?>'><?php echo $feedback_info_pre['from_email'] ?></a>
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Content</label>
                                          <div class="controls" style="margin-top: 5px">
                                            <p><?php echo $feedback_info_pre['content'] ?></p>
                                          </div>
                                        </div>
                                        
                                        <div class="control-group">
                                          <label class="control-label" for="textarea2">Reply</label>
                                          <div class="controls">
                                            <textarea id="ckeditor_standard" placeholder="Our response" name="our_reply" ><?php echo $feedback_info_pre['replied_with'] ?></textarea>
                                          </div>    
                                        </div>
                                        <div class="form-actions">
                                          <input type="hidden" name="CSRFcheck" value="<?php echo $_SESSION['CSRFcheck'] ?>">
                                          <input type="hidden" name="ticket_id" value="<?php echo $feedback_info_pre['ticket_id'] ?>">
                                          <button type="submit" class="btn btn-primary" name="submit" value="save">Save</button>
                                          <button type="submit" class="btn btn-primary" name="submit" value="send_reply">Send Reply</button>
                                          <a href="<?php echo $_SERVER["PHP_SELF"];?>"><button class="btn">Cancel</button></a>
                                        </div>
                                        </fieldset>
                                    </form>
                                    
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
                                <div class="muted pull-left">Feedbacks</div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <legend><?php echo $row_org['belong_org']; ?></legend>
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
                                                <th>Time</th>
                                                <th>From</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            static $odd_even = 0;
                                            $event_info = array();
                                            $new_query = "SELECT * FROM Feedback WHERE to_org='".$row_org['belong_org']."' COLLATE utf8_bin ORDER BY time";
                                            $new_result = mysqli_query($dbc,$new_query);
                                            if ($new_result) {
                                              while($event_info = $new_result->fetch_array())
                                              { ?>

                                                    <tr class="<?php if($odd_even==0) {echo "odd gradeX";}else{echo "even gradeC";}?>">
                                                        <td class="center"><?php echo $event_info['time'];?></td>
                                                        <td class="center"><?php echo $event_info['from_username'];?></td>
                                                        <td class="center"><?php echo $event_info['from_email'];?></td>
                                                        <td class="center"><?php echo $event_info['status'];?></td>
                                                        <td class="center">
                                                            <a href="<?php echo $_SERVER["PHP_SELF"]."?action=reply&ticket_id=".$event_info['ticket_id']."&CSRFcheck=".$_SESSION['CSRFcheck'];?>">Detials & Reply</a>
                                                            <!--a href="<?php echo $_SERVER["PHP_SELF"]."?action=delete&ticket_id=".$event_info['ticket_id'];?>">Delete</a-->
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