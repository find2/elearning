<?php
    session_start();
    require 'lib.php';
    $number=$_POST['number'];
    $type=$_POST['type'];
    $object = new CRUD();
    if($type==1)
   		$data=$object->Question_Field_Mc($number);
   	else
   		$data=$object->Question_Field_Essay($number);
    echo $data;
?>