<?php
	session_start();
    require("lib.php");
    date_default_timezone_set("Asia/Brunei");
	$date_created = date("Y-m-d h:i:sa");
	$description = $_POST['description'];
	$id_class = $_POST['post_to'];
	$id_user=$_SESSION['e_user_id'];
    $object = new CRUD();
    $object->Write_Post($description, $id_class, $date_created, $id_user);

?>