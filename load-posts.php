<?php 
require_once 'core/init.php';


$recordsPerPage = 10;
$pageNum = Input::Get('pageNum');

$user = Session::Get('user');

$db = DB::getInstance();    //database connection instance

//retrieving the first 10 posts from the database
$start = ($pageNum * $recordsPerPage) - $recordsPerPage;
$db->retrievePosts($start, $recordsPerPage);
$postResults = $db->getResults();

$allPosts = [];


foreach($postResults as $post){
    array_push($allPosts, new Post($post));
}

Post::displayPosts($allPosts, $user);

