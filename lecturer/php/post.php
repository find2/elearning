<?php
    session_start();

    require 'lib.php';
    $object = new CRUD();
    //$id_class=0; // Input user
    $username=$_SESSION['e_username'];
    $monarch=$_SESSION['e_monarch'];
    $data=$object->Create_Post_Modal($username, $monarch);
    $data.=$object->Header_Post($username, $monarch);
    /*$posts = $object->Read_Post($id_class, $monarch);
    if(count($posts)>0){
        foreach ($posts as $post) {
            $data.= $object->Post($post['username'], $post['date_created'], $post['description'], $post['post_id']);
            $comments=$object->Read_Comment($post['post_id'], $monarch);
            if(count($comments>0)){
                foreach($comments as $comment){
                    $data.=$object->Comment_post($comment['username'], $comment['date_created'], $comment['content'], $comment['coment_id']);
                }
            }
            $data.= $object->End_Post($post['post_id']);
        }
    }*/
    $data.='</div></section>';
    echo $data;

?>
