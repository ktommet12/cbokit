<?php 
    require_once 'core/init.php';
    
    Session::Get('');
    
    $user = new User();
    if(!$user->isLoggedIn()){
        Redirect::To('index.php');
    }

    $allPosts = []; //stores all the posts returned from the database
    //as Post objects 
    $currentPage = (int)Input::Get('page');

    $paginator = new Paginator();           //paginator object that will determing if pagination is needed
    $posts = $paginator->retrievePosts();

    //generating Post Object for each record returned from the database
    foreach($posts as $post){
        //then adding them to the posts array for display
        array_push($allPosts, new Post($post)); 
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <script src="https://use.fontawesome.com/e3b9412439.js"></script>
        <style>
            .post{
                width: 100%;
                background: #F7F7F7;
                border: 1px solid #000;
                margin-top: 15px; 
            }
            #username{
                padding: 5px;
            }
            .post-text, .post-tag, .post-title, .post-body{
                padding: 0 5px;
            }
            .post-comments, .post-likes{
                margin-left: 10px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="logout">
                <p><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a></p>
            </div>
            <h1>Feed</h1>
            <a href="addnewpost.php" class="btn btn-success">Add New Post</a>
            <div>
                <?php 
                    Post::displayPosts($allPosts);
                    if($currentPage == $paginator->getNumPages()){
                        echo "<p>End of Posts</p>";
                    }
                ?>
            </div>
            <?php $paginator->addPageNumbers(); ?>
        </div>
    </body>
</html>
