<?php
/**同时刊出session和cookie的页面*/
//即使是刊出时,也必须起首开端会话才干接见会话变量
session_start();
//应用一个会话变量搜检登录状况
if(isset($_SESSION["user_id"])){
    //要清除会话变量,将$_SESSION超等全局变量设置为一个空数组
    $_SESSION = array();
    //若是存在一个会话cookie,经由过程将到期时候设置为之前1个小时从而将其删除
    if(isset($_COOKIE[session_name()])){
        setcookie(session_name(),"",time()-3600);
    }
    //应用内置session_destroy()函数调用撤销会话
    session_destroy();
}
//同时将各个cookie的到期时候设为畴昔的某个时候,使它们由体系删除,时候以秒为单位
setcookie("user_id","",time()-3600);
setcookie("username","",time()-3600);
//location首部使浏览看重定向到另一个页面
$home_url = "index.php";
header("Location:".$home_url);
?>