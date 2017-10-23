<?php
session_start(); 
	unset($_SESSION['level']);unset($_SESSION['username']);unset($_SESSION['name']);unset($_SESSION['monarch']);unset($_SESSION['level']);
	//header('location:index.php');
	header('location:index.php');
	echo "<script>
		location.href='index.php';
	</script>";
?>