<?php
class Conversation{
    private $convoId        = 0;        //stores the conversation id from the conversations table
    private $firstUsername  = null;     //stores the username for the first person talking
    private $firstUserId    = 0;        //stores the id of the first user
    private $secondUserId   = 0;        //stores the id of the second user
    private $secondUsername = null;     //stores the username for the second person talking
    private $time_stamp     = null;     //when the conversation was first started
    private $messages;
    
    public function __construct($conversation){
        $this->convoId          = $conversation->conversation_id;
        $this->firstUsername    = self::getUsername($conversation->user_1_id);
        $this->firstUserId      = $conversation->user_1_id;
        $this->secondUsername   = self::getUsername($conversation->user_2_id);
        $this->secondUserId     = $conversation->user_2_id;
        $this->time_stamp       = date_create($conversation->time_stamp);   
        $this->messages         = self::getAllMessages();
    }
    public function getFirstUsername(){
        return $this->firstUsername;
    }
    public function getSecondUsername(){
        return $this->secondUsername;
    }
    public function getMessages(){
        return $this->messages;
    }
    public function getID(){
        return $this->convoId;
    }
    private function getUsername($id){
        DB::getInstance()->retrieveSpecificRecord('users', 'user_name', 'user_id', $id);
        return DB::getInstance()->getFirstResultRecord()->user_name;
    }
    private function getAllMessages(){
        $tempArray = array();
        //retrieving the responses from the database
        DB::getInstance()->getMessages($this->convoId);
        $results = DB::getInstance()->getResults();
        $resultCount = count($results);
        //looping through the returned responses from the database and adding them
        //to a array which is returned
        if($resultCount > 0){
            for($i = 0; $i < $resultCount; $i++){
                array_push($tempArray, $results[$i]);
            }
        }
        return $tempArray;
        
    }
}