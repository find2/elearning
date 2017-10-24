<?php
session_start();
if (isset($_POST)) {
    require 'lib.php';
    $username=$_SESSION['e_username'];
    $id_user=$_SESSION['e_user_id'];
    $monarch=$_SESSION['e_monarch'];
    $id_class = $_POST['id_class'];
    $password = $_POST['password'];
    $object = new CRUD();
    date_default_timezone_set("Asia/Brunei");
	$date = date("Y-m-d h:i:sa");
    $enroll_codes=$object->Get_Enroll_Id($id_class);
    if(count($enroll_codes)>0){
      foreach ($enroll_codes as $enroll) {
        $enroll_id=$enroll['id_enroll'];
      }
    }
    $result=$object->Enroll_Class_Validate($enroll_id, $password, $username, $id_class, $monarch);
    if($result=="1"){
        $object->Enroll_Class($enroll_id, $id_user, $date, $id_class, $monarch);
    }
    echo $result;
	}
?>
