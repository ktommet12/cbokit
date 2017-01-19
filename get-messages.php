<?php
    require_once 'core/init.php';
    
    $db = DB::getInstance();                    //database instance
    $user_1_username = Session::Get('user');   //getting the currently logged in username
    $user_2_id = Input::Get('user_2_id');
    
    $db->retrieveSpecificRecord('users', 'user_id', 'user_name', $user_1_username);
    $user_1_id = $db->getFirstResultRecord()->user_id;


    //gets the other username from the database 
    $db->retrieveSpecificRecord('users', 'user_name', 'user_id', $user_2_id);
    $user_2_username = DB::getInstance()->getFirstResultRecord()->user_name;


    $db->getConversation($user_1_id, $user_2_id);
    $temp = $db->getFirstResultRecord();

    $conversation = new Conversation($temp);

    $user1Messages = $conversation->getMessages($user_1_username);
    $user2Messages = $conversation->getMessages($user_2_username);

    $messageCount = count($user1Messages);

    for($i = 0; $i < $messageCount; $i++){
        echo "<p><strong>".$user_1_username . ":</strong> " . $user1Messages[$i] . "</p><br>";
    }








