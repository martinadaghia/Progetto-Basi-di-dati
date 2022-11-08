<?php
  require_once("../connessione.php"); // controlla se il file è già messo dentro (se è gia stato messo non lo fa)

  define("TITLE", "Home Page");
  include('layouts/header.php');
?>
<?php

  $totaleConferenze = $dbh->getTotaleConferenze();
  $totaleConferenzeAttive = $dbh->getTotaleConfAttive();
  $totaleUtenti = $dbh->getTotaleUtenti();
  $classificaPresenter = $dbh->getClassificaPresenter();
  $classificaSpeaker = $dbh->getClassificaSpeaker();
  $sponsorizzazioni = $dbh->getSponsorizzazioniHome();

  $classificaFinale=array_merge($classificaPresenter, $classificaSpeaker);
  usort($classificaFinale, 'sortByVoto'); // per riordinare presenter e speaker in base al voto
?>

<!-- carosello -->
<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="./risorse/immagini/image1.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Conferenze formative</h5>
        <p>Impara grazie ai nostri esperti in materia</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="./risorse/immagini/image2.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Sessioni e presentazioni interessanti</h5>
        <p>Iscriviti anche tu!</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="./risorse/immagini/image3.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Possibilità di interagire</h5>
        <p>Utilizza la chat di sessione per inviare messaggi</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<!-- DATI DA STAMPARE -->
<div class="m-5">
  <p class="col-md-8 fs-5">Totale conferenze registrate: <?php echo $totaleConferenze[0]["totale"]?></p>
  <p class="col-md-8 fs-5">Totale conferenze attive: <?php echo $totaleConferenzeAttive[0]["totale"]?></p>
  <p class="col-md-8 fs-5">Totale utenti registrati: <?php echo $totaleUtenti[0]["totale"]?></p>

  <p class="col-md-8 fs-5">Classifica presenter e speaker: </p>
    <?php foreach($classificaFinale as $utente): ?>
      <li><?php echo "USERNAME: " .$utente['Username'] . " - VOTO: " . $utente['Voto']  . " - TIPO UTENTE: " . $utente['tipo']  ;?></li> <br>
    <?php endforeach; ?>     

    <p class="col-md-8 fs-5">Sponsorizzazioni e Numero sponsorizzazioni: </p>
    <?php foreach($sponsorizzazioni as $sponsorizzazione): ?> 
      <li><?php echo "Sponsorizzazione: " .$sponsorizzazione['NomeSponsor'] . " - Numero: " . $sponsorizzazione['numeroSponsorizzazioni'] ;?> <br><?php echo("<img src='.\\risorse\\immagini\\".$sponsorizzazione["NomeFile"]."' width='300' height='200'"); ?></li> <br>
    <?php endforeach; ?>     
</div>

<?php
  include('layouts/footer.php');
?>