<?php
    require_once 'connection.php';

    $templateParams["conferenze"] = $dbh->getNumConferenze();
    $templateParams["conferenzeAttive"] = $dbh->getNumConferenzeAttive();
    $templateParams["utenti"] = $dbh->getNumUtenti();

    $templateParams["presenter"] = $dbh->avgVotoPresenter();
    $templateParams["speaker"] = $dbh->avgVotoSpeaker();

    require 'template/templateHome.php';
?>