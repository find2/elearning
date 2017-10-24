<?php
    session_start();

    require 'lib.php';
    $object = new CRUD();
    $username=$_SESSION['e_username'];
    $monarch=$_SESSION['e_monarch'];
    $data=$object->Create_Post_Modal($username, $monarch);
    $data.=$object->Header_Post($username, $monarch);
    $data.='</div></section>';
    echo $data;

?>
