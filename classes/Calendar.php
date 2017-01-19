<?php


class Calendar{
    private $months         = null;     //stores the months of the year in an array
    private $days           = null;     //stores the days of the week in an array
    private $currentYear    = 0;        //stores the current year of this calendar instance
    private $currentMonth   = 0;        //stores the current Month of the calendar
    private $currentDay     = 0;        //stores the current Day
    private $currentDate    = null;
    private $daysInMonth    = 0;
    
    function __construct($month){
        $this->months = self::getMonths();
        $this->days = self::getDays();
        $this->currentMonth = $month;
        $this->currentYear = date("Y", time());
        $this->daysInMonth = $this->daysInMonth($month, $this->currentYear);
    }
    public function displayCalendar(){
        echo "<table class='calendar'>";
            echo "<tr align='center'>";
                echo "<td colspan='7' class='month-header'>";
                    //this will create a link to display the next month
                    $link = "index.php?month=".$this->getMonth($this->currentMonth-1);
                    echo "<span id='prev-month'><a href='$link'>".$this->getMonth($this->currentMonth-1)."</a></span>";
                    //prints the current month as the table header
                    echo "<span id='month-header'>".$this->getMonth($this->currentMonth)." " . $this->getYear()."</span>";
                    //this will create a link to display the previous month
                    $link = "index.php?month=".$this->getMonth($this->currentMonth+1);
                    echo "<span id='next-month'><a href='$link'>".$this->getMonth($this->currentMonth+1)."</a></span>";
                echo "</td>";
            echo "</tr>";
            echo "<tr align='center'>";
                $dayCount = count($this->days);
                for($i = 0; $i < $dayCount; $i++){
                    echo "<td class='day-of-week'><strong>".$this->days[$i]."</strong></td>";
                }
            echo "</tr>";
            echo "<tr>";
                $timestamp  = mktime(0,0,0,$this->currentMonth+1, 1, $this->getYear());         //creating a new date to calcualate the starting day of the month
                $maxDay     = date('t', $timestamp);                                            //stores how many total days are in this month
                $thisMonth  = getdate($timestamp);                                              //gets all the time and date information for this month
                $startDay   = $thisMonth['wday']-1;                                             //stores which day of the week the first day of the month is.
                
                //adds all the days to the calendar
                for($i = 0; $i<($maxDay+$startDay); $i++){
                    if(($i % 7) == 0) echo "<tr>";
                    if($i < $startDay) echo "<td></td>";
                    else echo "<td valign='top' class='calendar-day'>" . ($i - $startDay+1)."</td>";
                    if(($i % 7) == 6) echo "</tr>";
                }
                
            echo "</tr>";
        echo "</table>";
    }
    public function daysInMonth($month, $year){
        $timestamp  = mktime(0,0,0,$month+1, 1, $this->getYear());
        return date('t', $timestamp);
    }
    //returns the text representation of the current month
    private function getMonth($month){
        if($month == -1) $month = 11;
        if($month == 12) $month = 0;
        return $this->months[$month];
    }
    //returns the current year of the calendar
    private function getYear(){
        return $this->currentYear;
    }
    //returns the current day MON-TUES-etc in text form instead of numbers
    private function getDay($day){
        return $this->days[$day-1];
    }
    //sets the year one back
    public function goToPreYear(){
        $this->currentYear--;
    }
    //sets the year forward
    public function goToNextYear(){
        $this->currentYear++;
    }
    //These are static functions so you dont need to instantiate the class in order to use them
    public static function getMonths(){
        return ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    }
    public static function getDays(){
        return ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
    }
}