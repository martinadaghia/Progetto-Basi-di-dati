<?php
    session_start();

    require_once("database.php");


    $dbh = new DatabaseHelper("localhost", "root", "", "CONFVIRTUAL", 8889);

    function sortByVoto($x, $y) {  // riordino la classifica
        return $y['Voto'] - $x['Voto'];
    }

    
    
?>