<?php 
require_once 'core/init.php';

$postTitle = Input::Get('post-title');

echo $postTitle;

DB::getInstance()->deleteRecord('posts', 'title', $postTitle);