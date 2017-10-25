<?php
  session_start();

  require 'lib.php';
  $object = new CRUD();
  $id_class=$_POST['class_id'];
  $monarch=$_SESSION['e_monarch'];
  $data="";
  $posts = $object->Read_Post($id_class, $monarch);
  if(count($posts)>0){
      foreach ($posts as $post) {
          $data.= $object->Post($post['username'], $post['date_created'], $post['description'], $post['post_id']);
          $comments=$object->Read_Comment($post['post_id'], $monarch);
          if(count($comments>0)){
              foreach($comments as $comment){
                  $data.=$object->Comment_post($comment['username'], $comment['date_created'], $comment['content'], $comment['comment_id']);
              }
          }
          $data.= $object->End_Post($post['post_id']);
      }
  }
  echo $data;
?>
