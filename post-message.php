<?php

require_once 'core/init.php';

$db = DB::getInstance();

$message = Input::Get('message');
$postersUsername = Session::Get('user');
$convoID = Session::Get('conversationID');

//getting the user id
$db->retrieveSpecificRecord('users', 'user_id', "user_name", $postersUsername);
$user_id = $db->getFirstResultRecord()->user_id;

//adding the message to the database

$IMmessage = array(
    'conversation_id' => $convoID,
    'user_id'         => $user_id,
    'response_text'   => $message
);

$db->insertRecord('conversation_response', $IMmessage);


