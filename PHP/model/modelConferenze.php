<?php
  require_once("../connessione.php"); // controlla se il file è già messo dentro (se è gia stato messo non lo fa)

define("TITLE", "Home Page");
include('layouts/header.php');
?>
<?php
  $conferenzeDisponibili = $dbh->getConferenzeDisponibili();
  $verificaRegistrazioneConf = $dbh->getVerificaRegistrazioneConf();
?>


<div class="position-relative overflow-hidden p-1 p-md-1 m-md-1 text-center bg-light">
  <div class="col-md-5 p-lg-5 mx-auto my-1">
    <h1 class="display-4 fw-normal">CONFERENZE</h1>
  </div>
  
  <!--<div class="col-md-5 mx-auto my-5">
    <p class="lead">Visualizza l'elenco delle conferenze disponibili</p>
    <a class="btn btn-outline-secondary" href="/info">Elenco conferenze</a>
  </div> -->
  </div>


  <div class="m-5">
  <p class="col-md-8 fs-5">Conferenze disponibili: </p>
    <?php foreach($conferenzeDisponibili as $conf): ?>
      <li><?php echo "ACRONIMO: " .$conf['Acronimo'] . " - NOME: " . $conf['Nome']  . " - ANNO: " . $conf['AnnoEdizione']  ;?></li> <br>
    <?php endforeach; ?>     
  </div>


  <div class="m-5">
  <p class="col-md-8 fs-5">Registrati ad una conferenza: </p>
      <select>
        <?php 
        foreach($verificaRegistrazioneConf as $verificata):
          echo "<option value='".$verificata['Acronimo']. "_". $verificata['AnnoEdizione']."'>".$verificata['Acronimo']. "_". $verificata['AnnoEdizione']."</option>";
        endforeach;
        ?>
      </select>
  </div>


<?php
include('layouts/footer.php');
?>