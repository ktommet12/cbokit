<?php
    require_once 'core/init.php';
    
    $user = new User();
    if(!$user->isLoggedIn()){
        Session::Flash('serverError', 'There was an error with the server and you have been logged out, please log back in');
        Redirect::To('index.php');
    }
    else{
        //$post = new Post();
        if(Input::Get('post-id')){
            $postID = Input::Get('post-id');
            DB::getInstance()->retrieveRecord('posts', 'post_id', $postID);
            $temp = DB::getInstance()->getFirstResultRecord();
            $post = new Post($temp);
            var_dump($post);
            //var_dump($post);
        }
        
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        
        <!--Font Awesome CDN-->
        <script src="https://use.fontawesome.com/e3b9412439.js"></script> 
        <style>
            .topic{
                color: #00CC00;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h3><span class="topic">Topic: </span><?php echo $post->getPostTitle();  ?></h3>
            <div>
                <p><?php echo $post->getPostBody(); ?></p>
            </div>
        </div>
    </body>
</html>
    
    