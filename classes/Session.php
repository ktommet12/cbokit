<?php
    /*
    *   
    *          Session Class Used for Maipulating the Session Global Array
    *          Author: Kyle Tommet
    *          Date: 7/2/2016
    */

class Session{
    static function Put($name, $value){
        return $_SESSION[$name] = $value;
    }
    static function Exists($name){
        return (isset($_SESSION[$name]))? true : false;
    }
    static function Delete($name){
        if(self::Exists($name)){
            unset($_SESSION[$name]);
        }
    }
    static function Get($name){
        return $_SESSION[$name];
    }
    static function Flash($name, $string = ''){
        if(self::Exists($name)){
            $session = self::Get($name);
            self::Delete($name);
            return $session;
        }else{
            if(strlen($string) > 0)
                self::Put($name, $string);
        }
    }
}