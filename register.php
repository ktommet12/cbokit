<?php
    require_once "core/init.php";
    
    $requiredErr = "This Field is Required";
    $formHasErrors = false;
    $registerErrors = "";
    
    if(Input::Get('submit')){
        if(empty(Input::Get('firstname')) || empty(Input::Get('lastname'))||empty(Input::Get('email'))||empty(Input::Get('username'))||empty(Input::Get('password'))||empty(Input::Get('studentID'))){
            $registerErrors = "One or more required fields were left blank";
        }else{
            $user = new User();
            //if the user already exists we dont want to create a duplicate profile
            if($user->findUser(Input::Get('username'))){
                $registerErrors = "This user already Exists in the system";
            }else{
                //otherwise create the new user and add them to the database
                $salt = Hash::generateSalt();
                try{
                    $user->createNewUser(array(
                        'first_name'    => Input::Get('firstname'),
                        'last_name'     => Input::Get('lastname'),
                        'user_name'     => Input::Get('username'),
                        'password'      => Hash::encryptPassword(Input::Get('password'), $salt),
                        'salt'          => $salt,
                        'status'        => 0,
                        'latitude'      => 0.0,
                        'longitude'     => 0.0,
                        'email'         => Input::Get('email')
                    ));
                    Redirect::To('index.php');
                }
                catch(Exception $ex){
                    echo $ex->getMessage();
                }    
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>CBoKIT</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">


    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,700italic' rel='stylesheet' type='text/css'>


    <!-- Custom styles for this template -->
    <link href="styles.css" rel="stylesheet">

    </head>
    <body>
        <div class="container">
            <div id="logo"></div>
                <img class="cbokitLogo" src="img/cbokitLogo.jpg" alt="Logo">
             </div>

            <div class="container" id="signInBoxR">
                <h1>Register</h1>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" name="firstname" class="form-control" id="firstName" placeholder="First Name">
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" name="lastname" class="form-control" id="lastName" placeholder="Last Name">
                    </div>
                    <div class="radioSelect">
                        <label class="radio-inline"><input type="radio" name="optradio">Student</label>
                        <label class="radio-inline"><input type="radio" name="optradio">Tutor</label>
                        <label class="radio-inline"><input type="radio" name="optradio">Instructor</label>
                    </div>    
                    <div class="form-group">
                        <label for="studentId">Student/Employee ID</label>
                        <input type="text" name="studentID" class="form-control" id="studentId" placeholder="Student ID">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email">
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" class="form-control" id="username" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    </div>
                    <div class="container" id="submitBtn">
                        <input type="submit" name="submit" class="btn btn-default" value="Register">
                    </div>
                </form>
            </div>
            <footer class="footer">
                <div class="container">
                    <p class="text-muted">Copyright ©2016 CBoKIT. All rights reserved.</p>
                </div>
            </footer>
        </div>



    <!-- Bootstrap core JavaScript
            ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
   


    </body>
</html>