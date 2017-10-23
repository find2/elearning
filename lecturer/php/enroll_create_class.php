<?php
session_start();
if (isset($_POST)) {
    require 'lib.php';
    $username=$_SESSION['e_username'];
    $code = $_POST['code'];
    $password = $_POST['password'];
    $object = new CRUD();
    date_default_timezone_set("Asia/Brunei");
	$date_created = date("Y-m-d h:i:sa");
    $result=$object->Enroll_Create_Class($code, $password, $username, $date_created);
    if($result!="")
        $result=1;
    else
        $result=0;
	}
?>