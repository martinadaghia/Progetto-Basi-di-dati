<?php
  require_once("../connessione.php"); // controlla se il file è già messo dentro (se è gia stato messo non lo fa)

define("TITLE", "Home Page");
include('layouts/header.php');
?>
<?php

?>


<div class="position-relative overflow-hidden p-1 p-md-1 m-md-1 text-center bg-light">
  <div class="col-md-5 p-lg-5 mx-auto my-1">
    <h1 class="display-4 fw-normal">PROFILO PERSONALE</h1>
  </div>
</div>

<?php



?>


<?php
if($_SESSION["tipo"] == "amministratore"){
?>

  <!-- Inserisci una conferenza -->

  <div class="m-5" style ="text-align: left">
    <b><p class="col-md fs-5">CREAZIONE CONFERENZA </p></b>

    <form method="post" enctype="multipart/form-data" name="formConferenza">
      <label for="acronimoConferenza">Acronimo Conferenza</label><br>
      <input type="text" name="acronimoConferenza" id="acronimoConferenza" maxlength="10">  <br><br>

      <label for="annoConferenza">Anno Conferenza</label><br>
      <input type="number" min="1900" max="2090" step="1" name="annoConferenza" id="annoConferenza" maxlength="4"> <br><br>

      <label for="nomeConferenza">Nome Conferenza</label><br>
      <input type="text" name="nomeConferenza" id="nomeConferenza" maxlength="50"> <br><br>

      <label for="logoConferenza">Logo Conferenza</label><br>
      <input type="file" name="logoConferenza" id="logoConferenza"> <br><br>

      <label for="sponsorConferenza">Numero Sponsor Conferenza</label><br>
      <input type="text" name="sponsorConferenza" id="sponsorConferenza"></input> <br><br>

      <label for="giornoProgramma">Data Programma Giornaliero</label><br>
      <input type="date" name="giornoProgramma" id="giornoProgramma"></input> <br><br>


      <input type="submit" name="registraConferenza" id="registraConferenza" value="Registra"/>
    </form>
    
  </div>

  <?php
    if(isset($_POST["registraConferenza"])){
      $immagine=file_get_contents($_FILES["logoConferenza"]["tmp_name"]);
      $acronimoConferenza=$_POST["acronimoConferenza"];
      $annoConferenza=$_POST["annoConferenza"];
      $nomeConferenza=$_POST["nomeConferenza"];
      $sponsorConferenza=$_POST["sponsorConferenza"];

      $dbh->caricaFoto($acronimoConferenza . "_" . $annoConferenza . "_" . $_FILES["logoConferenza"]["name"], $immagine);

      $ultimaFoto=$dbh->getUltimaFoto();
      
      $giornoProgramma=$_POST["giornoProgramma"];
      $dbh->inserisciConferenza($acronimoConferenza, $annoConferenza, $nomeConferenza, $ultimaFoto[0]["idLogo"], $sponsorConferenza, $_SESSION["username"], $giornoProgramma);
    }
  ?>



  <!-- Inserisci una sessione -->

  <div class="m-5" style ="text-align: left">
    <b><p class="col-md fs-5">CREAZIONE SESSIONE </p></b>

    <form method="post" enctype="multipart/form-data" name="formSessione">
      <label for="titoloSessione">Titolo Sessione</label><br>
      <input type="text" name="titoloSessione" id="titoloSessione" maxlength="100">  <br><br>

      <label for="numPresentazioni">Numero Presentazioni Sessione</label><br>
      <input type="text" name="numPresentazioni" id="numPresentazioni" maxlength="6"> <br><br>

      <label for="oraInizioSessione">Orario di inizio Sessione</label><br>
      <input type="time" name="oraInizioSessione" id="oraInizioSessione"> <br><br>

      <label for="oraFineSessione">Orario di fine Sessione</label><br>
      <input type="time" name="oraFineSessione" id="oraFineSessione"> <br><br>

      <label for="linkSessione">Link Sessione</label><br>
      <input type="text" name="linkSessione" id="linkSessione" maxlength="200"></input> <br><br>

      <label for="programmiSessione">Programmi Sessione</label><br>
      <?php $programmi=$dbh->getProgrammiGiornalieri(); ?>
      <select name="programmiSessione" id="programmiSessione">
        <?php foreach($programmi as $programma): ?>
          <option <?php  echo("value='" . $programma["Codice"] . "'"); ?> >  <?php echo($programma["Acronimo"]) . "-" . $programma["DataConferenza"];   ?>  </option> <br>
        <?php endforeach; ?>     
      </select> <br><br>


      <input type="submit" name="registraSessione" id="registraSessione" value="Registra"/>
    </form>
    
  </div>

  <?php
    if(isset($_POST["registraSessione"])){

      $titoloSessione=$_POST["titoloSessione"];
      $numPresentazioni=$_POST["numPresentazioni"];
      $oraInizioSessione=$_POST["oraInizioSessione"];
      $oraFineSessione=$_POST["oraFineSessione"];
      $linkSessione=$_POST["linkSessione"];
      $programmiSessione=$_POST["programmiSessione"];

      $dbh->inserisciSessione($titoloSessione, $numPresentazioni, $oraInizioSessione, $oraFineSessione, $linkSessione, $programmiSessione);
    }
  ?>


  <!-- Inserisci uno sponsor -->

  <div class="m-5" style ="text-align: left">
    <b><p class="col-md fs-5">INSERISCE UNO SPONSOR </p></b>

    <form method="post" enctype="multipart/form-data" name="formSessione">
      <label for="titoloSponsor">Titolo Sponsor</label><br>
      <input type="text" name="titoloSponsor" id="titoloSponsor" maxlength="100">  <br><br>

      <label for="logoSponsor">Logo Sponsor</label><br>
      <input type="file" name="logoSponsor" id="logoSponsor"> <br><br>

      <label for="importoSponsor">Importo Sponsor</label><br>
      <input type="text" name="importoSponsor" id="importoSponsor"> <br><br>

      <input type="submit" name="registraSponsor" id="registraSponsor" value="Registra"/>
    </form>
    
  </div>

  <?php
    if(isset($_POST["registraSponsor"])){

      $titoloSponsor=$_POST["titoloSponsor"];
      $importoSponsor=$_POST["importoSponsor"];

      $immagine=file_get_contents($_FILES["logoSponsor"]["tmp_name"]);
      $dbh->caricaFoto($titoloSponsor . "_" . $_FILES["logoSponsor"]["name"], $immagine);
      $ultimaFoto=$dbh->getUltimaFoto();

      $dbh->inserisciSponsor($titoloSponsor, $ultimaFoto[0]["idLogo"], $importoSponsor);
    }
  ?>



<!-- Inserisci una sponsorizzazione -->

<div class="m-5" style ="text-align: left">
    <b><p class="col-md fs-5">INSERISCE UNA SPONSORIZZAZIONE </p></b>

    <form method="post" enctype="multipart/form-data" name="formSessione">
      <label for="conferenzeSponsorizzazione">Scegli Conferenza</label><br>
      <?php $conferenze=$dbh->getConferenzeDisponibili() ?>
      <select name="conferenzeSponsorizzazione" id="conferenzeSponsorizzazione">
        <?php foreach($conferenze as $conferenza): ?>
          <option <?php  echo("value='" . $conferenza["Acronimo"] . "_" . $conferenza["AnnoEdizione"] . "'"); ?> >  <?php echo($conferenza["Acronimo"]) . "-" . $conferenza["AnnoEdizione"];   ?>  </option> <br>
        <?php endforeach; ?>
      </select> <br><br>


      <label for="nomeSponsor">Nome Sponsor</label><br>
      <?php $sponsor=$dbh->getNomeSponsor() ?>
      <select name="nomeSponsor" id="nomeSponsor">
        <?php foreach($sponsor as $spon): ?>
          <option <?php  echo("value='" . $spon["Nome"] . "'"); ?> >  <?php echo($spon["Nome"]);   ?>  </option> <br>
        <?php endforeach; ?>
      </select> <br><br>

      <input type="submit" name="registraSponsorizzazione" id="registraSponsorizzazione" value="Registra"/>
    </form>
    
  </div>

  <?php
    if(isset($_POST["registraSponsorizzazione"])){

      $conferenzeSponsorizzazione=$_POST["conferenzeSponsorizzazione"];
      $nomeSponsor=$_POST["nomeSponsor"];

      $split=explode("_", $conferenzeSponsorizzazione);
      $titolo=$split[0];
      $anno=$split[1];
      
      $dbh->inserisciSponsorizzazione($titolo, $anno, $nomeSponsor);
    }
  ?>





<?php
}
?>


<?php
include('layouts/footer.php');
?>