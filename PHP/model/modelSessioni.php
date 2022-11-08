<?php
  require_once("../connessione.php"); // controlla se il file è già messo dentro (se è gia stato messo non lo fa)

define("TITLE", "Home Page");
include('layouts/header.php');
?>

<?php
  $sessioni = $dbh->getSessioni();
  
?>


<div class="position-relative overflow-hidden p-1 p-md-1 m-md-1 text-center bg-light">
  <div class="col-md-5 p-lg-5 mx-auto my-1">
    <h1 class="display-4 fw-normal">SESSIONI</h1>
  </div>

  <div class="m-5" style ="text-align: left">
  <b><p class="col-md fs-5">SESSIONI DISPONIBILI CON RELATIVE PRESENTAZIONI (elenco puntato): </p></b>
    <?php foreach($sessioni as $sessione): ?>

      <form name="formChatSessione" method="get" action="./modelChat.php?chat="<?php echo($sessione['Codice']) ?>> 
        <b><?php echo "CODICE: " .$sessione['Codice'] . " - TITOLO: " . $sessione['Titolo']  . " - LINK: " . $sessione['Link']  ;?> </b>
        <input type="submit" id="submitChatSessione" name="submitChatSessione" value="<?php echo($sessione['Codice'])  ?>"></input>
      </form>

      <?php 
      $articoli=$dbh->getArticoli($sessione['Codice']);
      $tutorial=$dbh->getTutorial($sessione['Codice']);

      if(count($articoli)>0){
        ?>

        <?php 
        foreach($articoli as $articolo): 
          echo "<li>";
          echo "CODICE: " ."<b>".$articolo['Codice'] ."</b>". " - ORA INIZIO: " ."<b>". $articolo['OraInizio']  ."</b>"
          . " - ORA FINE: " ."<b>". $articolo['OraFine']."</b>" . " - TITOLO: " ."<b>". $articolo['Titolo'] ."</b>"
          . " - PRESENTER: " ."<b>". $articolo['UsernameUtentePresenter'] ."</b>" ;
          echo "</li>";
        endforeach;
        ?>

        <?php

      }
      if(count($tutorial)>0){
        ?>

        <?php 
        foreach($tutorial as $tut): 
          echo "<li>";
          echo "CODICE: "."<b>" .$tut['Codice'] ."</b>". " - ORA INIZIO: "."<b>" . $tut['OraInizio']  ."</b>"
          . " - ORA FINE: " ."<b>". $tut['OraFine']."</b>" . " - TITOLO: "."<b>" . $tut['Titolo']."</b>";
          echo "</li>";
        endforeach;
        ?>

        <br><br>
        <?php

      }
       ?>
       
    <?php endforeach; ?> 
    
  </div>
  
</div>


<?php
include('layouts/footer.php');
?>