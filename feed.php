<?php 
    require_once 'core/init.php';
    
    date_default_timezone_set('America/Phoenix');
    
    $temp = Session::Get('date');
    
    //echo date('l F j, Y h:i A', $temp) . "<br>";
    
    $user = new User();
    if(!$user->isLoggedIn()){
        Redirect::To('index.php');
    }

    $allPosts   = [];   //stores all the posts returned from the database as Post objects 
    $users      = [];   //stores all the people who are currently logged in and able to be chatted with
    $tags       = ["Select a Hash Tag"];   //stores all the possible tags one could post with 
    
    DB::getInstance()->retrieveAll('tags');
    $allTags = DB::getInstance()->getResults();
    
    foreach($allTags as $tag){
        array_push($tags, $tag->hash_tag);
    }
    
    //this will retrieve the currently logged in users to display in the available chat window
    DB::getInstance()->retrieveSpecificRecord('users', 'user_name', 'isLoggedIn', 1);
    $usernames = DB::getInstance()->getResults();
    foreach($usernames as $username){
        if($username->user_name != Session::Get('user'))
            array_push($users, $username->user_name);
    }
    
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <link rel="stylesheet" href="styles.css">
        <script src="https://use.fontawesome.com/e3b9412439.js"></script>
        <!--google font -->
        <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,700italic' rel='stylesheet' type='text/css'>
        <title>CBoKIT Feed</title>
    </head>
    <body>
       <nav class="navbar navbar-default">
           <div class="container-fluid">
               <!-- Brand and toggle get grouped for better mobile display -->
               <div class="navbar-header">
                   <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                       <span class="sr-only">Toggle navigation</span>
                       <span class="icon-bar"></span>
                       <span class="icon-bar"></span>
                       <span class="icon-bar"></span>
                   </button>
                </div>   
                <a class="navbar-brand" href="feed.php">
                   <img class="feedLogo" src="img/cbokitLogo.jpg" alt="CBoKIT">
               </a>
               <!-- Collect the nav links, forms, and other content for toggling -->
               <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                   <ul class="nav navbar-nav cbokitnav">
                       <li class="active"><a href="feed.php">Feed <span class="sr-only">(current)</span></a></li>
                       <li><a href="#">Groups</a></li>
                       <li><a href="map.php">Map</a></li>
                       <li class="dropdown">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
                           <ul class="dropdown-menu">
                               <li><a href="#">Action</a></li>
                               <li><a href="#">Another action</a></li>
                               <li><a href="#">Something else here</a></li>
                               <li role="separator" class="divider"></li>
                               <li><a href="#">Separated link</a></li>
                               <li role="separator" class="divider"></li>
                               <li><a href="logout.php">Log Out</a></li>
                           </ul>
                       </li>
                   </ul>   
               </div>
                  <!-- <div class="col-md-6 col-md-6 pull-right">
                       <form class="navbar-form" role="search">
                           <div class="input-group">
                               <input type="text" class="form-control" placeholder="Search" name="srch-term" id="srch-term">
                               <div class="input-group-btn">
                                   <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                               </div>
                           </div>
                       </form>
                   </div>
               </div><!-- /.navbar-collapse -->
           </div><!-- /.container-fluid -->
       </nav>

        <!-- start main body section-->
        <div class="container-fluid">
            <div class="col-md-9 discussion-posts">
                <h3 class="text-center">Add New Post</h3>
                <div class="alert alert-danger" role="alert" id="add-new-post-error"></div>
                <div class="postBox">
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-8">
                            <input type="text" id="post-title" placeholder="Add A Title">
                        </div>
                        <div class="col-md-4">
                            <select name="select" id="hash_tags">
                                <?php
                                    foreach($tags as $tag){
                                        if($x==1)
                                            echo "<option value='".$tag."' selected>".$tag."</option>";
                                        else
                                            echo "<option value=".$tag.">".$tag."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                                <textarea class="post-message" id="feed-post" rows="5" maxLength="1000" placeholder=" Message"></textarea>
                        </div>
                    </div>
                    <div class="checkbox-anonymous">
                      <label><input type="checkbox" value="">   Post Anonymous</label>
                    </div>
                    <div class="row">
                        <div class="col-md-4"><button class="btn btn-success post-message-btn" id="post-message-btn">Post</button></div>
                        <div class="col-md-8"><span id="characters">1000</span> characters remaining (1000 max)</div>
                    </div>
                </div>    
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-center">Current Posts</h3>
                        <div id="all-posts">
                            <!--Posts will by dynamically loaded here, anything placed in this box will be deleted by AJAX-->
                            <!--Do not delete this div or rename it, thank you-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="row users">
                    <div class="col-md-12 user-list" id="user-list" style="overflow-y:scroll;">
                        <h3 class="text-center available-users" id=>Available Users</h3>
                        <div class="users-inner" id="users-inner">
                            <?php
                                foreach($users as $username){
                                    echo "<div><button class='available-user-btn' onclick='changeConversation(this)' value='".$username."'>".$username." <i class='fa fa-commenting speech-bubble' aria-hidden='true'></i></button></div>";
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row" id="message-Section" style="overflow-y: scroll">
                    <div class="col-md-12 messenger-section" id="messenger" >
                        <div id="messages">
                        </div>
                    </div>
                </div>
                <div class="row" style="height: 34px">
                    <div class="input-group messenger">
                        <input type="text" class="form-control" placeholder="Message" id="messageBox">
                        <span class="input-group-btn">
                            <button class="btn btn-default" id="post-message" type="button" onclick="return postImMessage()">Post</button>
                        </span>
                    </div><!-- /input-group -->                    
                </div>
            </div>
        </div>
         <footer class="footer" id="footer">
            <div class="container">
                <p class="text-muted">&copy; 2016 CBoKIT. All rights reserved.</p>
            </div>
        </footer>
    </body>
    
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="js/messenger.js"></script>
    <script src="js/cbokit.js"></script>
</html>