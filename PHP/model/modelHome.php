<?php
  define("TITLE", "Home Page");
  include('layouts/header.php');
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
      <img src="./risorse/immagini/presentazione.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Presentazioni formative</h5>
        <p>Impara grazie ai nostri esperti in materia</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="./risorse/immagini/microfono.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Partecipazione attiva</h5>
        <p>Avrai la possibilità di interagire con i relatori</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="./risorse/immagini/pubblico.jpg" class="d-block w-100" alt="...">
      <div class="carousel-caption d-none d-md-block">
        <h5>Grande partecipazione</h5>
        <p>Migliaia di persone si sono già iscritte, che aspetti?</p>
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
<p class="col-md-8 fs-5">Totale conferenze registrate: <?php echo $templateParams["conferenze"][0]["NumConf"]?></p>
<p class="col-md-8 fs-5">Totale conferenze attive: <?php echo $templateParams["conferenzeAttive"][0]["NumConfAtt"]?></p>
<p class="col-md-8 fs-5">Totale utenti registrati: <?php echo $templateParams["utenti"][0]["NumUtenti"]?></p>
</div>

<div class="m-5">
  <p class="col-md-8 fs-5">Classifica presenter: </p>
  <li>
    <?php foreach($templateParams['presenter'] as $presenter): ?>
      <li><?php echo $presenter['UsernameUtente'] . " - Voto: " . $presenter['Media'];?></li> <br>
    <?php endforeach; ?>     
  </li>
</div>

<div class="m-5">
  <p class="col-md-8 fs-5">Classifica speaker: </p>
  <li>
    <?php foreach($templateParams['speaker'] as $speaker): ?>
      <li><?php echo $speaker['UsernameUtente'] . " - Voto: " . $speaker['Media'];?></li><br>
    <?php endforeach; ?>
  </li>
</div>





<?php
  include('layouts/footer.php');
?>