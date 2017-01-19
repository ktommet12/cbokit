<?php
    require_once "core/init.php";
    date_default_timezone_set('America/Phoenix');
    
    $temp = Session::Get('date');
    
    echo date('l F j, Y h:i A', $temp) . "<br>";

    
