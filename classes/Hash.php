<?php 
    class Hash{
        static function encryptPassword($string, $salt = ''){
    		return hash('sha256', $string . $salt);
    	}
    	static function encryptData($string){
    	    return hash('sha256', $string);
    	}
    	static function generateSalt(){
    		return mcrypt_create_iv(32);
    	}
    	static function unique(){
    		return self::encryptData(uniqid());
    	}
    }