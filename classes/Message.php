<?php
class Message{
    private $messageText = "";
    private $messageUsername = "";
    private $timePosted = null;
    
    public function __construct($message){
        $this->messageText = $message->response_text;
        $this->messageUsername = $message->
    }
}