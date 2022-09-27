<?php
    session_start();

    require_once("Database.php");
    
    $dbh = new DatabaseHelper("localhost", "root", "root", "CONFVIRTUAL", 8889);

    if (!$dbh) {
        print "Si è verificato un problema";
        exit;
    }




    
    
?>