<?php

    //public function test_reg_droid(){
    require_once "core/init.php";
    
    
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type'];
    
    
    /*
    $fname = "tyler";
    $lname = "durden";
    $id = "1234";
    $username = "tester";
    $password = "test";
    $email = "stuff@uat.edu";
    $user_type = "student";
    */
    $requiredErr = "This Field is Required";
    $formHasErrors = false;
    $registerErrors = "";
    
   // if(empty(Input::Get('firstname')) || empty(Input::Get('lastname'))||empty(Input::Get('email'))||empty(Input::Get('username'))||empty(Input::Get('password'))||empty(Input::Get('studentID'))){
    //    $registerErrors = "One or more required fields were left blank";
   // }else{
        $user = new User();
        //if the user already exists we dont want to create a duplicate profile
        if($user->findUser($username)){
            $registerErrors = "This user already Exists in the system";
        }else{
            //otherwise create the new user and add them to the database
            $salt = Hash::generateSalt();
            try{
                
                $user->createNewUser(array(
                    'first_name'    => $fname,
                    'last_name'     => $lname,
                    'user_name'     => $username,
                    'password'      => Hash::encryptPassword($password, $salt),
                    'salt'          => $salt,
                    'status'        => 0,
                    'latitude'      => 0.0,
                    'longitude'     => 0.0,
                    'email'         => $email
                    //'hot_spot_id'   => 0,
                    //'avatar'        => null
                ));
                
                //echo json_encode($user->getUserData($username));
            }
            catch(Exception $ex){
                echo $ex->getMessage();
            }    
            
        
        }
    
?>