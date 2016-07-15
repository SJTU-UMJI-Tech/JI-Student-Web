<!DOCTYPE html>
<?php

//应用会话内存储的变量值之前必须先开启会话
session_start();
//若是会话没有被设置，查看是否设置了cookie
include 'function.php';
include 'component.php';
$dbc = db_connect();
init_dash_web($dbc);

$add_member_err_msg = "";
$add_user_err_msg = "";
if(isset($_POST["submit"]) && check_CSRF()){
    // add new user
    if (!empty($_POST["add_user"])) {
        if (check_admin($dbc)) {
            $query = "SELECT * FROM All_users WHERE stuID=\"".mysqli_real_escape_string($dbc, $_POST["usr_ID"])."\" COLLATE utf8_bin ";
            $data = mysqli_query($dbc,$query);
            echo mysqli_error($dbc);
            if ($data) {
                if ($data->num_rows > 0) {
                    $add_user_err_msg="User with ID:".mysqli_real_escape_string($dbc, $_POST["usr_ID"])." already exist";
                } else {
                    if ($_POST["usr_password"] == $_POST["usr_password_again"]) {
                        $query = "INSERT INTO All_users SET email=\"". mysqli_real_escape_string($dbc, $_POST["usr_email"])
                        ."\", phone=\"". mysqli_real_escape_string($dbc, $_POST["usr_phone"])
                        ."\", dob=\"". mysqli_real_escape_string($dbc, $_POST["usr_dob"])
                        ."\", password=\"". mysqli_real_escape_string($dbc, $_POST["usr_password"])
                        ."\", username=\"". mysqli_real_escape_string($dbc, $_POST["usr_name"])
                        ."\", stuID=\"". mysqli_real_escape_string($dbc, $_POST["usr_ID"])
                        ."\"";
                        $data = mysqli_query($dbc,$query);
                        //echo $query;
                        echo mysqli_error($dbc);
                    } else {
                        $add_user_err_msg = "Please type the <b>same</b> password twice.";
                    }
                }
            }
        } else {
            $add_user_err_msg = "You do not have such permission.";
        }
    }

    // add member
    if (!empty($_POST["add_member_ID"])) {
        if (check_permission($dbc, $_POST["add_to_org"])) {
            $query = "SELECT * FROM Member WHERE stuID=\"".mysqli_real_escape_string($dbc, $_POST["add_member_ID"])
            ."\" COLLATE utf8_bin AND belong_org=\"".mysqli_real_escape_string($dbc, $_POST["add_to_org"])."\" COLLATE utf8_bin ";
            $data = mysqli_query($dbc,$query);
            //echo $query;
            echo mysqli_error($dbc);
            if ($data) {
                if ($data->num_rows > 0) {
                    $add_member_err_msg = "Member already exist in ".mysqli_real_escape_string($dbc, $_POST["add_to_org"]);
                } else {
                    //$query = "SELECT * FROM All_users WHERE stuID=\"".mysqli_real_escape_string($dbc, $_POST["add_member_ID"])."\" COLLATE utf8_bin ";
                    $query = "INSERT INTO Member SET stuID=\"".mysqli_real_escape_string($dbc, $_POST["add_member_ID"])
                    ."\", status='show', belong_org=\"".mysqli_real_escape_string($dbc, $_POST["add_to_org"])."\"";
                    $data = mysqli_query($dbc,$query);
                    dev_echo(mysqli_error($dbc));
                    /*if ($data) {
                        if ($data->num_rows == 0) {
                            $add_member_err_msg = "No such user, please add user first.";
                        } else {
                            $query = "INSERT INTO member SET stuID=\"".mysqli_real_escape_string($dbc, $_POST["add_member_ID"])
                            ."\", belong_org=\"".mysqli_real_escape_string($dbc, $_POST["add_to_org"])."\"";
                            $data = mysqli_query($dbc,$query);
                            //echo $query;
                            echo mysqli_error($dbc);
                        }
                    }*/
                }
            }
        } else {
            $add_member_err_msg = "You do not have such permission.";
        }
    }
}

//change member permission
function check_field()
{
    return isset($_GET['who']) && isset($_GET['org']);
}

function check_admin($dbc)
{
    $query = "SELECT * FROM Admin_users WHERE boss_stuID=\"".mysqli_real_escape_string($dbc, $_SESSION["user_id"])
    ."\" COLLATE utf8_bin";
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

$modify_member_err_msg = "";
if (isset($_GET['action']) && check_CSRF()) {
    $my_action = $_GET['action'];
    if ($my_action == "Admin") {
        if (check_field() == true) {
            if (check_permission($dbc, $_GET['org']) == true) {
                $query = "SELECT * FROM Admin_users WHERE boss_stuID=\"".mysqli_real_escape_string($dbc, $_GET["who"])
                ."\" COLLATE utf8_bin AND org_name ='".mysqli_real_escape_string($dbc, $_GET['org'])."' COLLATE utf8_bin ";
                $data = mysqli_query($dbc,$query);
                //echo $query;
                echo mysqli_error($dbc);
                if ($data) {
                    if ($data -> num_rows > 0) {
                        $modify_member_err_msg = "He/she is already Admin.";
                    } else {
                        $query = "INSERT INTO Admin_users SET boss_stuID=\"".mysqli_real_escape_string($dbc, $_GET["who"])
                        ."\", org_name ='".mysqli_real_escape_string($dbc, $_GET['org'])."'";
                        $data = mysqli_query($dbc,$query);
                        //echo $query;
                        echo mysqli_error($dbc);
                    }
                }
            } else {
                $modify_member_err_msg = "You do not have such permission.";
            }
        } else {
            $modify_member_err_msg = "Parameter insufficient.";
        }
    } else if ($my_action == "Member") {
        if (check_field() == true) {
            if (check_permission($dbc, $_GET['org']) == true) {
                $query = "SELECT * FROM Admin_users WHERE boss_stuID=\"".mysqli_real_escape_string($dbc, $_GET["who"])
                ."\" COLLATE utf8_bin AND org_name ='".mysqli_real_escape_string($dbc, $_GET['org'])."' COLLATE utf8_bin ";
                $data = mysqli_query($dbc,$query);
                //echo $query;
                echo mysqli_error($dbc);
                if ($data) {
                    if ($data -> num_rows == 0) {
                        $modify_member_err_msg = "He/she is not Admin, already.";
                    } else {
                        $query = "DELETE FROM Admin_users WHERE boss_stuID=\"".mysqli_real_escape_string($dbc, $_GET["who"])
                        ."\"COLLATE utf8_bin AND org_name ='".mysqli_real_escape_string($dbc, $_GET['org'])."' COLLATE utf8_bin ";
                        $data = mysqli_query($dbc,$query);
                        //echo $query;
                        echo mysqli_error($dbc);
                    }
                }
            } else {
                $modify_member_err_msg = "You do not have such permission.";
            }
        } else {
            $modify_member_err_msg = "Parameter insufficient.";
        }
    } else if ($my_action == "delete") {
        if (check_field() == true) {
            if (check_permission($dbc, $_GET['org']) == true) {
                $query = "DELETE FROM Admin_users WHERE boss_stuID=\"".mysqli_real_escape_string($dbc, $_GET["who"])
                ."\"COLLATE utf8_bin AND org_name ='".mysqli_real_escape_string($dbc, $_GET['org'])."' COLLATE utf8_bin ";
                $data = mysqli_query($dbc,$query);
                //echo $query;
                echo mysqli_error($dbc);
                $query = "DELETE FROM Member WHERE stuID=\"".mysqli_real_escape_string($dbc, $_GET["who"])
                ."\"COLLATE utf8_bin AND belong_org ='".mysqli_real_escape_string($dbc, $_GET['org'])."' COLLATE utf8_bin ";
                $data = mysqli_query($dbc,$query);
                //echo $query;
                echo mysqli_error($dbc);
            } else {
                $modify_member_err_msg = "You do not have such permission.";
            }
        } else {
            $modify_member_err_msg = "Parameter insufficient.";
        }
    } else if (($my_action == "show" || $my_action == "hide")&& check_CSRF()) {
        if (check_field() == true) {
            if (check_permission($dbc, $_GET['org'])) {
                if ($my_action == "show") {
                    $query = "UPDATE Member SET status='show' WHERE belong_org='".mysqli_real_escape_string($dbc, $_GET["org"])
                    ."' COLLATE utf8_bin AND stuID='".mysqli_real_escape_string($dbc, $_GET["who"])."' COLLATE utf8_bin";
                } else {
                    $query = "UPDATE Member SET status='hide' WHERE belong_org='".mysqli_real_escape_string($dbc, $_GET["org"])
                    ."' COLLATE utf8_bin AND stuID='".mysqli_real_escape_string($dbc, $_GET["who"])."' COLLATE utf8_bin";
                }
                $data = mysqli_query($dbc,$query);
                //echo $query;
                dev_echo(mysqli_error($dbc));
            } else {
                $modify_member_err_msg = "You do not have such permission.";
            }
        } else {
            $modify_member_err_msg = "Parameter insufficient.";
        }
    } else {
        $modify_member_err_msg = "Unknown aciton.";
    }
}

function admin_echo($he_is_admin)
{
    if($he_is_admin==1) {return "Admin";} else {return "Member";}
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
                <?php gen_sidebar_admin(2);?>
                
                <!--/span-->
                <div class="span9" id="content">
                <?php // handle error msg
                err_handle($add_member_err_msg, "Member added.", "add_member_ID");
                err_handle($add_user_err_msg, "New user added.", "add_user");
                err_handle($modify_member_err_msg, "Action done.", "action");
                ?>

                <?php
                    $query = "SELECT * FROM Organization WHERE org_name IN (SELECT org_name FROM Admin_users WHERE boss_stuID='".$_SESSION["user_id"]."' COLLATE utf8_bin ORDER BY org_name)";
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
                                <div class="muted pull-left">Members</div>
                            </div>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <legend><?php echo $row_org['org_name']; ?></legend>
                                   <div class="table-toolbar">
                                      <div class="btn-group">
                                        <form class="form-inline" method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>">
                                        <input type="text" class="span8" id="add_member_ID" name="add_member_ID" placeholder="User ID / Student ID">
                                        <input type="hidden" name="add_to_org" value="<?php echo $row_org['org_name']; ?>">
                                        <input type="hidden" name="CSRFcheck" value="<?php echo $_SESSION["CSRFcheck"]; ?>">
                                        <button style="margin-left:20px;" type="submit" name="submit" class="btn btn-success">Add Member<i class="icon-plus icon-white"></i></button></a>
                                        </form>
                                      </div>
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
                                                <th>User ID</th>
                                                <th>Birthday</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Permission</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            static $odd_even = 0;
                                            $he_is_admin = 0;
                                            $mem_info = array();
                                            $new_query = "SELECT * FROM Member WHERE belong_org='".$row_org['org_name']."' COLLATE utf8_bin";
                                            $new_result = mysqli_query($dbc,$new_query);
                                            if ($new_result) {
                                              while($mem_info = $new_result->fetch_array())
                                              {
                                                $new2_query = "SELECT * FROM All_users WHERE stuID='".$mem_info['stuID']."' COLLATE utf8_bin";
                                                $new2_result = mysqli_query($dbc,$new2_query);
                                                if ($new2_result) {
                                                    $person_info = $new2_result->fetch_array();

                                                    $new3_query = "SELECT * FROM Admin_users WHERE boss_stuID='".$person_info['stuID']."' COLLATE utf8_bin AND org_name='".$row_org['org_name']."' COLLATE utf8_bin";
                                                    $new3_result = mysqli_query($dbc,$new3_query);
                                                    if ($new3_result) {
                                                        if ($new3_result->num_rows > 0) {
                                                            $he_is_admin = 1;
                                                        }
                                                        else
                                                        {
                                                            $he_is_admin = 0;
                                                        }
                                                    }
                                                    ?>

                                                    <tr class="<?php if($odd_even==0) {echo "odd gradeX";}else{echo "even gradeC";}?>">
                                                        <td class="center"><?php echo $person_info['username'];?></td>
                                                        <td class="center"><?php echo $mem_info['stuID'];?></td>
                                                        <td class="center"><?php echo $person_info['dob'];?></td>
                                                        <td class="center"><?php echo $person_info['phone'];?></td>
                                                        <td class="center"><a href="mailto:<?php echo $person_info['email'];?>"><?php echo $person_info['email'];?></td>
                                                        <td class="center"><?php echo admin_echo($he_is_admin);?></td>
                                                        <td class="center">
                                                            <a href="<?php $tmp=admin_echo(!$he_is_admin); echo $_SERVER["PHP_SELF"]."?action=".$tmp."&who=".$person_info['stuID']."&org=".$row_org['org_name']."&CSRFcheck=".$_SESSION["CSRFcheck"];?>">Set <?php echo admin_echo(!$he_is_admin); ?></a> /
                                                            <a href="<?php echo $_SERVER["PHP_SELF"]."?action=delete&who=".$person_info['stuID']."&org=".$row_org['org_name']."&CSRFcheck=".$_SESSION["CSRFcheck"];?>">Delete</a>
                                                            <?php
                                                            if (check_permission($dbc, $row_org['org_name'])) { ?>
                                                               / <a href="<?php echo $_SERVER["PHP_SELF"]."?action="?><?php if($mem_info['status'] == "show") {echo "hide";} else {echo "show";}?><?php echo "&who=".$mem_info['stuID']."&org=".$row_org['org_name']."&CSRFcheck=".$_SESSION['CSRFcheck'];?>">
                                                                <?php if($mem_info['status'] == "show") {echo "hide";} else {echo "show";}?>
                                                                </a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
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
                
                <!-- add user section-->
                <div class="row-fluid">
                <!-- block -->
                        <div class="block">
                            <div class="navbar navbar-inner block-header">
                                <div class="muted pull-left">Add User</div>
                            </div>
                            <?php if (check_admin($dbc)) {
                            ?>
                            <div class="block-content collapse in">
                                <div class="span12">
                                   <legend>Add User</legend>
                                   <form method="POST" class="form-horizontal" enctype="multipart/form-data" action="<?php echo $_SERVER["PHP_SELF"];?>">
                                    <fieldset>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Name </label>
                                          <div class="controls">
                                            <input type="text" class="input-xlarge required" id="name" name="usr_name" placeholder="FirstName LastName">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">ID </label>
                                          <div class="controls">
                                            <input type="text" class="input-xlarge required" id="stuID" name="usr_ID" placeholder=" User ID / Student ID">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Initial Password </label>
                                          <div class="controls">
                                            <input type="password" class="input-xlarge required equalTo" id="password1" name="usr_password" placeholder="password">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Initial Password Again</label>
                                          <div class="controls">
                                            <input type="password" class="input-xlarge required equalTo" id="password2" name="usr_password_again" placeholder="password">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Email </label>
                                          <div class="controls">
                                            <input type="text" class="input-xlarge required email" id="email" name="usr_email" placeholder="eg. example@example.xxx">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="typeahead">Phone </label>
                                          <div class="controls">
                                            <input type="text" class="input-xlarge required" id="phone" name="usr_phone" placeholder="eg. +86 12345678910">
                                          </div>
                                        </div>
                                        <div class="control-group">
                                          <label class="control-label" for="date01">Birthday</label>
                                          <div class="controls">
                                            <input type="text" class="input-xlarge datepicker" placeholder="MM dd, yyyy" name="usr_dob" value="January 01, 1995">
                                          </div>
                                        </div>
                                        <div class="form-actions">
                                          <input type="hidden" name="add_user" value="set">
                                          <input type="hidden" name="CSRFcheck" value="<?php echo $_SESSION["CSRFcheck"]; ?>">
                                          <button type="submit" class="btn btn-primary" name="submit">Add User</button>
                                          <button type="reset" class="btn">Cancel</button>
                                        </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php } else { ?>
                            <div class="block-content collapse in">
                                <legend>Sorry... You are not Admin of any organization.</legend>
                            </div>
                        <?php } ?>
                        <!-- /block -->
                    </div>
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