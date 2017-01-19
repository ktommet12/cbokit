<?php 
    class Validator{
        private $_passed = false;       //if validation has passed or not
        private $_errors = array();     //stores all the error from the previous validation run
        private $_db = null;            //database instance
        
        function __construct(){
            $this->_db = DB::getInstance();
        }
        public static function validate($params = array()){
            foreach($params as $key){
                echo $key;
            }
        } 
        //adds a new error to the errors array
        private function addError($error){
            $this->_errors[] = $error;
        }
        //returns the array storing the errors of the past valdation
        function getErrors(){
            return $this->_errors;
        }
        //returns whether the last validation passed or not
        function hasPassed(){
            return $this->_passed;
        }
    }