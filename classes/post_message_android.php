<?php
    require_once "core/init.php";
    
    
    
    $post_id = $_POST['post_id'];
    $user_id = $_POST['user_id'];
    $tag_id = $_POST['tag_id'];
    $title = $_POST['title'];
    $init_text = $_POST['init_text'];
    $time_stamp = $_POST['time_stamp'];
    $last_update = $_POST['last_update'];
    $anonymous = $_POST['anonymous'];
    $help = $_POST['help'];
    $solved = $_POST['solved'];
    $comments = $_POST['comments'];
    $numLikes = $_POST['numLikes'];
    /*
    $post_id = 0;
    $user_id = 17;
    $tag_id = 1;
    $title = "stuff";
    $init_text = "stuff";
    $time_stamp = "2016-07-23 20:17:55.493";
    $last_update = "2016-07-23 20:17:55.493";
    $anonymous = 0;
    $help = 1;
    $solved = 0;
    $comments = 0;
    $numLikes = 0;
    $user_name = 'tester';*/
    

   // $time_stamp = date($time_stamp);
    //$last_update = date($last_update);
   // $user->db->insertRecord();
  // $user = new User();
  // $user = $user->getUserData($user_name);
   $db = DB::getInstance();
 //  $user->db = $db;
 //  var_dump($user);
   var_dump($db);

    
    $post = array(
                    'post_id'   =>  $post_id,
                    'user_id'    => $user_id,
                    'tag_id'     => $tag_id,
                    'title'     => $title,
                    'init_text'      => $init_text,
                    'time_stamp'          => $time_stamp,
                    'last_update'        => $last_update,
                    'anonymous'      => $anonymous,
                    'help'     => $help,
                    'solved'         => $solved,
                    'comments'     => $comments,
                    'numLikes'         => $numLikes
                );

            
            
            if(!$db->insertRecord('posts', $post )){
                throw new Exception("There was a problem creating the user account");
            }
            
    
?>