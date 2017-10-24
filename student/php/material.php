<?php
    session_start();
    require 'lib.php';
    $username=$_SESSION['e_username'];
    $object = new CRUD();
    $data=$object->Material_Modal($username); 
    $data.='</div></div>';
    echo $data;
?>