<?php 
    class Redirect{
        static function To($location = null){
            if($location){
                echo "Test";
                if(is_numeric($location)){
                    switch($location){
                        case 404:{
                            header('HTTP/1.0 404 Not Found');
                            include 'include/errors/404.php';
                            exit();
                            break;
                        }
                    }
                }
                header('Location: '.$location);
            }
        }
    }