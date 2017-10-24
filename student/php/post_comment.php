<?php
session_start();
    require("lib.php");
    date_default_timezone_set("Asia/Brunei");
	$date_created = date("Y-m-d h:i:sa");
	$desciption = $_POST['comment'];
  $user_id=$_SESSION['e_user_id'];
	$posts_id=$_POST['post_id'];

    $object = new CRUD();
    $object->Write_Comment($posts_id, $user_id, $date_created, $desciption);

?>
