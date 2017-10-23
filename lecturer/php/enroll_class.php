<?php
session_start();
$username=$_SESSION['e_username'];
if (isset($_POST)) {
    require 'lib.php';
    $code = $_POST['code'];
    $password = $_POST['password'];
    $object = new CRUD();
    $object->Enroll_Class($code, $password, $username);
}