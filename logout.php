<?php
    require_once 'core/init.php';
    $user = new User();
    
    $username = Session::Get('user');
    DB::getInstance()->retrieveSpecificRecord('users', "user_id", "user_name", $username);
    $user_id = DB::getInstance()->getFirstResultRecord()->user_id;
    
    DB::getInstance()->logUserOut($user_id);
    
    Session::Delete('user');
    Session::Delete('conversation');
    Redirect::To('index.php');