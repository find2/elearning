<?php
    session_start();
    require 'lib.php';
    $class_id=$_POST['class_id'];
    $object = new CRUD();
    $data=$object->Set_Quiz_Question($class_id);
    echo $data;
?>