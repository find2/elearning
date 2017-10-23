<?php
session_start();
if (isset($_POST)) {
    require 'lib.php';
    $username=$_SESSION['e_username'];
    $id_user=$_SESSION['e_user_id'];
    $monarch=$_SESSION['e_monarch'];
    $code = $_POST['code'];
    $password = $_POST['password'];
    $class_name = $_POST['class_name'];
    $object = new CRUD();
    date_default_timezone_set("Asia/Brunei");
	$date = date("Y-m-d h:i:sa");
    $result=$object->Enroll_Class_Validate($code, $password, $username, $class_name, $monarch);
    if($result=="1"){
        $id_enrolls=$object->Get_Id_Enroll($code);
        foreach ($id_enrolls as $id) {
            $id_enroll=$id['id'];
        }
        $id_class=$object->Create_Class_Name($id_enroll, $class_name, $id_user, $monarch);
        $object->Enroll_Class($id_enroll, $id_user, $date, $id_class, $monarch);
    }
    echo $result;
	}
?>