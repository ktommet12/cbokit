<?php
class Config{
    public static function Get($name){
        return $GLOBALS['config'][$name];
    }
}