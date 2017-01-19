<?php 
    require_once 'core/init.php';
    
    
    Session::Get('');
    
    $db = DB::getInstance();
    
    $db->retrieveAll('posts');

    $posts = $db->getResults();
    
    
    foreach($posts as $post=>$value){
       
        $val =  $value->user_id;
        DB::getInstance()->retrieveSpecificRecord('users','user_name','user_id', $val );
        $value->user_name = DB::getInstance()->getFirstResultRecord()->user_name;
        
    }
    
    $allPosts = print_r(json_encode( $posts, JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT), true);
    
    echo $allPosts;

?>