<?php
    session_start();
    require 'lib.php';
    $quiz_id=$_POST['quiz_id'];
    $user_id=$_SESSION['e_user_id'];
    $total_mc=$_POST['total_mc'];
    $mc = $_POST['mc'];
    $score_mc=100/$total_mc;
    $total_score_mc=0;
    $total_score_essay=0;
    $object = new CRUD();
    if($total_mc>0){
      for($i=1; $i<=$total_mc; $i++){
        $answer=$object->Get_Answer($quiz_id, $i, $mc[$i]);
        if($answer==1){
          $total_score_mc += $score_mc;
        }
      }
      $id_attempt=$object->Get_Id_Attempt($quiz_id, $user_id);
      $result=$object->Validate_Score($id_attempt);
      if($result==0){
        $object->Save_Score($id_attempt, $total_score_mc, $total_score_essay);
      }
      else{
        $object->Update_Score($id_attempt, $total_score_mc, $total_score_essay);
      }
    }

    //echo $data;
?>
