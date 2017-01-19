<?php 
require_once "core/init.php";
$db = DB::getInstance();

$postTitle         = Input::Get('title');
$postMessage       = Input::Get('message');
$postHashTag       = Input::Get('hash_tag');
$user_1_username   = Session::Get('user'); 
$anonymous         = false;

 

$db->retrieveSpecificRecord('users', 'user_id', 'user_name', $user_1_username);
$user_1_id = $db->getFirstResultRecord()->user_id;


$db->retrieveSpecificRecord('tags', 'tag_id', 'hash_tag', $postHashTag);
$tag_id = ($db->getFirstResultRecord()->tag_id);


$post = array(
    'user_id'    => $user_1_id,
    'tag_id'     => $tag_id,
    'title'      => $postTitle,
    'init_text'  => $postMessage,
    'anonymous'  => $anonymous,
    'help'       => true,
    'solved'     => false
);

//var_dump($post);

$db->insertRecord('posts', $post);
