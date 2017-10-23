<?php
session_start();
    require("lib.php");
    date_default_timezone_set("Asia/Brunei");
	$date_created = date("Y-m-d h:i:sa");
	$comment = $_POST['comment'];
	$username=$_SESSION['name'];
	$posts_id=$_POST['post_id'];
	
    $object = new CRUD();
    $object->Write_Comment($posts_id, $username, $date_created, $comment);

?>