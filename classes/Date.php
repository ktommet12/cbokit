<?php
class Date{
    
    private $months = null;

    function __construct($date = null){
        $this->months = Calendar::getMonths();
        if($date){
            $this->postDate = date_create($date);
        }
    }

}
