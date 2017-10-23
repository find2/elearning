<?php
    session_start();
    require 'lib.php';
    $username=$_SESSION['e_username'];
    $object = new CRUD();
    $data=$object->Quiz_Modal($username); 
    $data.=$object->Show_Quiz_Modal($username);
    //Codes here
    $data.='</div></div>';
    echo $data;
?>