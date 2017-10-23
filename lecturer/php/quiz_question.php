<?php
    session_start();
    require 'lib.php';
    $quiz_id=$_POST['quiz_id'];
    $object = new CRUD();
    $data=$object->Show_Quiz_Question($quiz_id);
    echo $data;
?>