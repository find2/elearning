<?php
    session_start();
    require 'lib.php';
    $username=$_SESSION['e_username'];
    $object = new CRUD();
    $data=$object->Ass_Modal($username); 
    $data.='</div></div>';
    echo $data;
?>