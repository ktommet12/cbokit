<?php
require_once 'core/init.php';

$db = DB::getInstance();

$db->retrieveSpecificRecord('users', "user_name", "isLoggedIn", 1);
$users = $db->getResults();

//var_dump($users);
foreach ($users as $username) {
    if($username->user_name != Session::Get('user'))
        echo "<div><button class='available-user-btn' onclick='changeConversation(this)' value='".$username->user_name."'>".$username->user_name."<i class='fa fa-commenting speech-bubble' aria-hidden='true'></i></button></div>";
}