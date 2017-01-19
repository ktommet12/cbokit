<?php 
    /*
        cBoKit Database Connection Class
        Date: 6/24/2016
        Author: Kyle Tommet
    */
    class DB {
        private static $instance;   //stores the current database connection instance
        private $pdo = null;
        private $rowCount = 0;      //stores a counter to how many rows were return from the database
        private $errors = false;    //stores whether or not there was an error in the query
        private $results;           //store the results that were return from the database
        private $query = "";        //stores the current query being executed
        
        /////////////////////////////Database Connection Variables /////////////////////////////////////////
        private $host = "mysql:host=phpmyadmin.cahlhzz0imjg.us-west-2.rds.amazonaws.com;dbname=cbokit";
        private $username = "phpMyAdmin";
        private $password = "phpMyAdmin";
        ////////////////////////////////////DO NOT CHANGE///////////////////////////////////////////////////
        
        private function __construct(){
            try{
                $this->pdo = new PDO($this->host, $this->username, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(PDOException $ex){
                echo $ex->getMessage();
            }
        }
        public static function getInstance(){
            if(!isset(self::$instance)){
                self::$instance = new DB();
            }
            return self::$instance;
        }
        private function queryDB($sql, $params = array(), $action){
            $this->errors = false;
            //var_dump($sql);
            //var_dump($params);
            if($this->query = $this->pdo->prepare($sql)){
                $x = 1;
                if(count($params)){
                    foreach($params as $field){
                        $this->query->bindValue($x, $field);
                        $x++;
                    }
                }
                //$this->query->debugDumpParams();        //simple debug to dump the prepared statement
                if($action == "SELECT"){
                    if($this->query->execute()){
                        $this->results = $this->query->fetchAll(PDO::FETCH_OBJ);
                        $this->rowCount = $this->query->rowCount();
                        //print_r($this->rowCount);
                    }else{
                        $this->errors = true;
                    }
                }else{
                    $this->query->execute();
                }
            }
            return $this;
        }
        //returns a specific number of records starting at $start and retrieving $limit number of records
        public function retrievePosts($start, $limit){
            $sql = "SELECT * FROM posts ORDER BY time_stamp DESC LIMIT $start, $limit";
            $this->queryDB($sql, [], "SELECT");
        }
        public function getMessages($conversation_id){
            $sql = "SELECT user_id, response_text, time_stamp from conversation_response WHERE conversation_id = ? ORDER BY time_stamp ASC";
            $this->queryDB($sql, [$conversation_id], "SELECT");
        }
        //returns the number of records that are in $table, but will return the number as a column called total
        public function getNumRows($table){
            $sql = "SELECT count(*) as total from $table";
            return $this->queryDB($sql, [], "SELECT");
        }
        //retrieve the tag or tags from the hash_tags table
        //that are associated with the posts table reference
        public function retrieveTags($values = array()){
            $arrayCount = count($values);
            $sql = '';
            if($arrayCount == 0){
                $sql = "SELECT hash_tag FROM tags WHERE tag_id = ?";
                $values = 12;
            }else{
                $tagString = '(';
                for($i = 0; $i < $arrayCount; $i++){
                    if($i == $arrayCount-1){
                        $tagString .= $values[$i];
                    }
                    else $tagString .= $values[$i] . ",";
                }
                $tagString .= ")";
                $values = [];
                $sql = "SELECT hash_tag FROM tags WHERE tag_id IN $tagString";
            }
            $this->queryDB($sql, $values, "SELECT");
        }
        //selects everything from $table where $field = $value
        public function retrieveRecord($table, $field, $value){
            $sql = "SELECT * FROM $table WHERE $field = ?";
            $this->queryDB($sql, [$value], "SELECT");
        }
        public function likePost($postID, $likeCount){
            $sql = "UPDATE posts set numLikes = ? WHERE post_id=?";
            $this->queryDB($sql, [$likeCount, $postID], "UPDATE");
        }
        function retrieveSpecificRecord($table, $column, $id, $value){
            $sql = "SELECT $column FROM $table WHERE $id = ?";
            $this->queryDB($sql, [$value], "SELECT");
        }
        public function retrieveAll($table){
           $sql = "SELECT * FROM $table";
           $this->queryDB($sql, [], "SELECT");
        }
        public function logUserIn($user_id){
            $sql = "UPDATE users SET isLoggedIn = true where user_id=?";
            $this->queryDB($sql, [$user_id], "UPDATE");
        }
        public function logUserOut($user_id){
            $sql = "UPDATE users SET isLoggedIn = false WHERE user_id=?";
            $this->queryDB($sql, [$user_id], "UPDATE");
        }
        public function generateConversation($user_id_1, $user_id_2){
            $sql = "INSERT INTO conversations values(null, ?, ?, now())";
            $this->queryDB($sql, [$user_id_1, $user_id_2], "INSERT");
        }
        public function getConversation($user_id_1, $user_id_2){
            $sql = "SELECT * FROM conversations WHERE (user_1_id = $user_id_1  AND user_2_id = $user_id_2) OR (user_2_id = $user_id_1 AND user_1_id = $user_id_2)";
            return $this->queryDB($sql, [], "SELECT");
        }
        public function insertRecord($table, $fields = array()){
            $fieldsCount = count($fields);
            if($fieldsCount){
                $keys = array_keys($fields);
                $values = '';
                $x = 1;
                foreach($fields as $field){
                    $values .= "?";
                    if($x < $fieldsCount){
                        $values .= ",";
                    }
                    $x++;
                }
                $sql = "INSERT INTO $table (`".implode('`,`', $keys)."`) VALUES ($values)";
                if($this->queryDB($sql, $fields, "INSERT")->hasError()){
                    return false;
                }
                else return true;
            }
        }
        public function deleteRecord($table, $field, $value){
            $sql = "DELETE FROM $table WHERE $field = ?";
            if($this->queryDB($sql, [$value], "DELETE")->hasError()){
                return false;
            }else{
                return true;
            }
        }

        //returns the whole array from the previous query
        public function getResults(){
            return $this->results;
        }
        public function rowCount(){
            return $this->rowCount;
        }
        public function getFirstResultRecord(){
            return $this->results[0];
        }
        public function hasError(){
            return $this->errors;
        }
    }