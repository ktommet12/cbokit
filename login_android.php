<?php

    require_once "core/init.php";

    $username = $_POST['username'];
    $password = $_POST['password'];
    
    //$username = 'tester';
    //$password = 'test';
    
    $user = new User();
    
    $logInErrors = "";
     
     if(!empty($username) && !empty($password)){
         
         

         if($user->logUserIn($username, $password)){
             //get user data
             //$user->getUserData($username);
           
            $results = $user->getJSONdata($user);
            echo $results;
            
         }else{
             //send error message back to droid
             $logInErrors = "Incorrect Username/Password";
            // echo $logInErrors;
             $results = print_r(json_encode($logInErrors), true);
             echo $results;
         }
     }

?>