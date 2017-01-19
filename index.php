<?php 
    require_once "core/init.php";
    
    Session::Flash('serverError');
    date_default_timezone_set('America/Phoenix');
    

    $user = new User();
    if($user->isLoggedIn()){
        Redirect::To('feed.php');
    }else{
        $logInErrors = "";
        if(Input::Get('submit')){
            
            $login = $user->logUserIn(Input::Get('username'), Input::Get('password'));

            if($login){
                Session::Put('date', time());
                Redirect::To('feed.php');
            }else{
                $logInErrors = "Incorrect Username/Password";
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
        
        <!--Font Awesome CDN-->
        <script src="https://use.fontawesome.com/e3b9412439.js"></script>

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

            <div class="container" id="signInBox">
            <h1>Sign In</h1>
                <div class="alert alert-danger" role="alert" id="log-in-error"></div>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" class="form-control" id="username" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    </div>
                    <div class="container" id="submitBtn">
                        <input type="submit" name="submit" class="btn btn-default" onclick="return checkLogin()" value="Log In">
                    </div>
                    <div class="container regLinks" id="registerLink">
                        <a href="register.php">Register</a>
                        <br>
                        <a href="register.php">Forgot Password</a>
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
    
    <script>
        $(document).ready(function(){
            $('#log-in-error').hide();
        });
        function checkLogin(){
            var username = $('#username').val();
            var password = $('#password').val();
            if(username == "" || password == ""){
                $('#log-in-error').text("Incorrect Username/Password");
                $('#log-in-error').show();
                return false;
            }
        }
    </script>


    </body>
</html>