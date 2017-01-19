<?php

    class User {
        private $userData = null;
        private $isLoggedIn = false;
        private $sessionName = 'user';
        private $db;
        
        public function __construct($user = null){
            $this->db = DB::getInstance();
            if(!$user){
                if(Session::Exists($this->sessionName)){
                    $user = Session::Get($this->sessionName);
                    if($this->findUser($user)){
                        $this->isLoggedIn = true;
                    }else{
                        //process logout
                    }
                }
            }else{
                $this->findUser($user);
            }
        }
        
        
        public function getJSONdata($data){
           // $data->userData;
            
           // $response = $this->db->retrieveRecord("users", "user_name", "tester" );
           // echo $response['first_name'];
            //echo json_encode($response, JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT);
            
            $response = array (
                    'first_name' => $data->userData->first_name,
                    'last_name' => $data->userData->last_name, 
                    'user_name' => $data->userData->user_name, 
                    // $data->userData->password => 'password',  
                     //$data->userData->salt => 'salt',      
                    'status' => $data->userData->status,    
                    'latitude' => $data->userData->latitude,  
                    'longitude' => $data->userData->longitude, 
                    'email' => $data->userData->email,    
                    'hot_spot_id' => $data->userData->hot_spot_id,
                    'avatar' => $data->userData->avatar,
                    'user_id' => $data->userData->user_id );
                
            return print_r(json_encode( $response, JSON_NUMERIC_CHECK | JSON_FORCE_OBJECT), true);
        }
        
        function findUser($username = null){
            if($username){
                $userData = $this->db->retrieveRecord('users', 'user_name', $username);
                if($this->db->rowCount()){
                    $this->userData = $this->db->getFirstResultRecord();
                    return true;
                }
            }
            return false;
        }
        public function logUserIn($username, $password){
            $user = $this->findUser($username);
            //var_dump($user);
            if($user){
                $encNewPassword = Hash::encryptPassword($password, $this->userData->salt);

                if($this->userData->password === $encNewPassword){
                    Session::Put($this->sessionName, $this->userData->user_name);
                    DB::getInstance()->logUserIn($this->userData->user_id);
                    return true;
                }
            }
            return false;
        }
        //returns this Users instance username
        public function getUsername(){
            return $this->userData->user_name;
        }
        //creates a brand new user and inserts them into the database into the users table, otherwise throws an error.
        public function createNewUser($fields = array()){
            if(!$this->db->insertRecord('users', $fields)){
                throw new Exception("There was a problem creating the user account");
            }
        }
        //returns whether or not this user is logged in or not;
        public function isLoggedIn(){
            return $this->isLoggedIn;
        }
    }