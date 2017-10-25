<?php
    session_start();
    require 'lib.php';
    $quiz_id=$_POST['quiz_id'];
    $quiz_name=$_POST['quiz_name'];
    $user_id=$_SESSION['e_user_id'];
    $object = new CRUD();
    $result=$object->Validate_Qiuz_Attempt($quiz_id, $user_id);
    if($result==0){
      $object->Insert_Attempt($quiz_id, $user_id, 1, 0);
      $data=$object->Show_Quiz_Question(1, $quiz_id, $quiz_name);
    }
    else if($result==1){
      $attempt=$object->Get_Attempt($quiz_id, $user_id);
      $object->Update_Attempt($attempt, $quiz_id, $user_id);
      $attempt+=1;
      $data=$object->Show_Quiz_Question($attempt, $quiz_id, $quiz_name);
    }
    else {
      $data='<h3>Cannot Answer Quiz Anymore.</h3>';
    }
    echo $data;
?>
