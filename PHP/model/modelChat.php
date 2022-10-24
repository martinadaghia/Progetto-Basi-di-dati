<?php
  require_once("../connessione.php"); // controlla se il file è già messo dentro (se è gia stato messo non lo fa)

define("TITLE", "Home Page");
include('layouts/header.php');
?>
<?php

  if(isset($_GET["submitChatSessione"])){
    $_SESSION["tempCodiceSessione"]=$_GET["submitChatSessione"];
  }

  $messaggi=$dbh->getMessaggi($_SESSION["tempCodiceSessione"]);

?>
  
  <?php

  // verifico che il pulsante venga cliccato
  if(isset($_GET["inviaMessaggio"])){
    $testo=$_GET["testo"];

    $codiceChat=$dbh->getCodiceChat($_SESSION["tempCodiceSessione"]);
    print_r($codiceChat[0]["Codice"]);
    print_r($_SESSION["username"]);

    $dbh->inserisciMessaggio($testo, $codiceChat[0]["Codice"], $_SESSION["username"]);
    $_SESSION["tempCodiceSessione"];

  }

  ?>

<div class="position-relative overflow-hidden p-1 p-md-1 m-md-1 text-center bg-light">
  <div class="col-md-5 p-lg-5 mx-auto my-1">
    <h1 class="display-4 fw-normal">CHAT</h1>
  </div>
</div>


<div class="m-5">
  <b><p class="col-md-8 fs-5">MESSAGGI: </p></b>
    <?php foreach($messaggi as $messaggio): ?>
      <li><?php echo "USERNAME: " ."<b>".$messaggio['UsernameUtente'] ."</b>". " - TESTO: "."<b>" . $messaggio['Testo'] ."</b>" . " - DATA: "."<b>" . $messaggio['DataMessaggio'] ."</b>" ;?> </li> 
    <?php endforeach; ?>    
</div>


<form class="m-5" name="formChatSessione" method="get"> 
      <input type="text"  name="testo" id="testo" > </input>
      <input type="submit" name="inviaMessaggio" id="inviaMessaggio" value="INVIA MESSAGGIO"></input>
</form>



<?php
include('layouts/footer.php');
?>