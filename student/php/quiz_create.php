<?php
session_start();
    require("lib.php");
    date_default_timezone_set("Asia/Brunei");
	$date_created = date("Y-m-d h:i:sa");
	$user_id=$_SESSION['e_user_id'];
	$class_id = $_POST['class_code'];
	$quiz_name = $_POST['title'];
	$duration = $_POST['duration'];
	$monarch = $_SESSION['e_monarch'];
	$number_mc = $_POST['number_mc'];
	$number_e = $_POST['number_e'];
	$qm = $_POST['qm'];
	$km = $_POST['km'];
	$ama = $_POST['ama'];
	$amb = $_POST['amb'];
	$amc = $_POST['amc'];
	$amd = $_POST['amd'];
	if($number_e > 0){
		$qe = $_POST['qe'];
		$ke = $_POST['ke'];
	}

	$object = new CRUD();
	$result = $object->Validate_Quiz($quiz_name, $class_id, $monarch); // Melakukan validasi apakah nama kuis sudah ad pda kelas dan monarch yg sama (0 belum ada, 1 sdh ada)
	if($result==0){
	    $quiz_id = $object->Write_Quiz($user_id, $class_id, $quiz_name, $duration, $date_created, $monarch, $number_mc, $number_e);

	    if($number_mc>0){
		    for($i = 1; $i <= $number_mc; $i++){
				$qa_mc_id = $object->Write_QA_MC($quiz_id, $i, $qm[$i], $km[$i]);
				$object->Write_MC_Quiz($qa_mc_id, $ama[$i], $amb[$i], $amc[$i], $amd[$i]);
			}
	    }

	    if($number_e>0){
		    for($i = 1; $i <= $number_e; $i++){
				$object->Write_QA_Essay($quiz_id, $i, $qe[$i], $ke[$i]);
			}
	    }
	}
	echo $result;




?>
