<?php
    require_once 'core/init.php';
    
 
    
    $user_1_username;
    $user_2_username;
    
    $test = array();
    
    //var_dump(Session::Get('conversation'));
    
    $initialLoad = boolval(Input::Get('initial-load')); //when the page is loaded for the first time
    
    $newConversation = boolval(Input::Get('newConversation')); //the user wants to change who they are chatting with
    
    $reload = boolval(Input::Get('reload'));//this is when the messager section should just be reloaded
    
    
    if($reload && Session::Exists('conversation')){
        $test = Session::Get('conversation');
        $user_1_username = $test['user_1_username'];
        $user_2_username = $test['user_2_username'];
        loadConversation($user_1_username, $user_2_username);        
    }
    elseif($initialLoad && Session::Exists('conversation')){
        $test = Session::Get('conversation');
        $user_1_username = $test['user_1_username'];
        $user_2_username = $test['user_2_username'];
        loadConversation($user_1_username, $user_2_username);
    }
    elseif($newConversation){
        $user_1_username = Session::Get('user');
        $user_2_username = Input::Get('user_2_username');
        
        $test = array(
            'user_1_username' => $user_1_username,
            'user_2_username' => $user_2_username
        );
        Session::Delete('conversation');
        Session::Put('conversation', $test);
        loadConversation($user_1_username, $user_2_username);
    }
    
    else{
        echo "Click on a User to Chat";
    }
    
    function loadConversation($user_1, $user_2){
        $db = DB::getInstance();                    //database instance
    
        //getting user 1's id
        $db->retrieveSpecificRecord('users', 'user_id', 'user_name', $user_1);
        $user_1_id = $db->getFirstResultRecord()->user_id;
    
    
        //gets the other username from the database 
        $db->retrieveSpecificRecord('users', 'user_id', 'user_name', $user_2);
        $user_2_id = $db->getFirstResultRecord()->user_id;
        
        $db->getConversation($user_1_id, $user_2_id);
        $conversationStarted = $db->rowCount();

        if($conversationStarted == 0){
            $db->generateConversation($user_1_id, $user_2_id);
        }       
    
        $db->getConversation($user_1_id, $user_2_id);
        $temp = $db->getFirstResultRecord();
        
        
        

        
    
        $conversation = new Conversation($temp);
        $messages = $conversation->getMessages();
        
        Session::Put('conversationID', $conversation->getID());
        
    
        $messageCount = count($messages);
        if($messageCount == 0) print "no messages posted yet";
        
        for($i = 0; $i < $messageCount; $i++){
            echo "<div class='row message-section'>";
                if($messages[$i]->user_id == $user_1_id){
                    echo "<div class='user-1-message user-message'>";
                        
                        echo "<p><strong>".$user_1 . ":</strong> " . $messages[$i]->response_text . "</p>";
                    echo "</div>";
                }
                else {
                    echo "<div class='user-2-message user-message'>";
                        echo "<p><strong>".$user_2 . ":</strong> " . $messages[$i]->response_text . "</p>";
                    echo "</div>";
                }
            echo "</div>";    
        }        
    }
    

