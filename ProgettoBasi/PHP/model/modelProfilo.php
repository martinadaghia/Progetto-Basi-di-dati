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

<div class="m-5" style ="text-align: left">
  <b><p class="col-md fs-5">VISUALIZZA PRESENTAZIONI PREFERITI </p></b>

  <?php $articoli=$dbh->getArticoliTutorialFavoriti($_SESSION["username"]); ?>
  <?php foreach($articoli as $articolo): ?>
    <li> <?php echo("Articolo - " . $articolo["TitoloPresentazione"]);   ?>  </li> <br>
  <?php endforeach; ?>     


  <b><p class="col-md fs-5">INSERISCI ARTICOLO PREFERITO </p></b>

  <form method="post" enctype="multipart/form-data" name="formConferenza">

    <label for="codiceArticoloFavourite">Titolo Articoli</label><br><br>
    <?php $articoli=$dbh->getNotArticoliFavoriti($_SESSION["username"]); ?>
    <select name="codiceArticoloFavourite" id="codiceArticoloFavourite">
    <?php foreach($articoli as $articolo): ?>
      <option value=<?php echo($articolo["Titolo"]);   ?>> <?php echo("Articolo - " . $articolo["Titolo"]);   ?>  </option> <br>
    <?php endforeach; ?>   
    </select> <br><br>  

    <input type="submit" name="insFavoArticolo" id="insFavoArticolo" value="Registra"/> <br><br>
  </form>

  <?php
    if(isset($_POST["insFavoArticolo"])) {
      $titolo=$_POST["codiceArticoloFavourite"];
      $dbh->inserisciPresentazioneFavorita($_SESSION["username"], $titolo);
    }
  ?>


  <b><p class="col-md fs-5">INSERISCI TUTORIAL PREFERITO </p></b>

  <form method="post" enctype="multipart/form-data" name="formConferenza">

    <label for="codiceTutorialFavourite">Titolo Tutorial</label><br><br>
    <?php $articoli=$dbh->getNotTutorialFavoriti($_SESSION["username"]); ?>
    <select name="codiceTutorialFavourite" id="codiceTutorialFavourite">
    <?php foreach($articoli as $articolo): ?>
      <option value=<?php echo($articolo["Titolo"]);   ?>> <?php echo("Articolo - " . $articolo["Titolo"]);   ?>  </option> <br>
    <?php endforeach; ?>    
    </select> <br><br>

    <input type="submit" name="insFavoTutorial" id="insFavoTutorial" value="Registra"/> <br><br>
  </form>

  <?php
    if(isset($_POST["insFavoTutorial"])) {
      $titolo=$_POST["codiceTutorialFavourite"];
      $dbh->inserisciPresentazioneFavorita($_SESSION["username"], $titolo);
    }
  ?>


</div>



<?php
if($_SESSION["tipo"] == "amministratore"){
?>

  <!-- Valutazioni Articoli -->

  <div class="m-5" style ="text-align: left">
      <b><p class="col-md fs-5">VISUALIZZA VALUTAZIONI ARTICOLI </p></b>

      <form method="post" enctype="multipart/form-data" name="formConferenza">

        <label for="codiceArticolo">Codici Articoli</label><br>
        <?php $articoli=$dbh->getTuttiArticoli(); ?>
        <select name="codiceArticolo" id="codiceArticolo">
          <?php foreach($articoli as $articolo): ?>
            <option <?php  echo("value='" . $articolo["Codice"] . "'"); ?> >  <?php echo($articolo["Codice"]);   ?>  </option> <br>
          <?php endforeach; ?>     
        </select> <br><br>

        <input type="submit" name="visualizzaValuArticolo" id="visualizzaValuArticolo" value="Registra"/> <br><br>
      </form>

      <?php
        if(isset($_POST["visualizzaValuArticolo"])){
          $codice = $_POST["codiceArticolo"];
          $articoli = $dbh->getValutazioniArticolo($codice);


        foreach($articoli as $articolo): ?>
          <li><?php echo "TITOLO: " .$articolo['Titolo'] . " - VOTO: " . $articolo['Voto']  . " - NOTE: " . $articolo['Note']  ;?></li> <br>
        <?php endforeach; }?>     

    </div>


    <!-- Valutazioni Tutorial -->

  <div class="m-5" style ="text-align: left">
      <b><p class="col-md fs-5">VISUALIZZA VALUTAZIONI TUTORIALS </p></b>

      <form method="post" enctype="multipart/form-data" name="formConferenza">

        <label for="codiceTutorial">Codici Tutorial</label><br>
        <?php $tutorials=$dbh->getTuttiTutorial(); ?>
        <select name="codiceTutorial" id="codiceTutorial">
          <?php foreach($tutorials as $tutorial): ?>
            <option <?php  echo("value='" . $tutorial["Codice"] . "'"); ?> >  <?php echo($tutorial["Codice"]);   ?>  </option> <br>
          <?php endforeach; ?>     
        </select> <br><br>

        <input type="submit" name="visualizzaValuTutorial" id="visualizzaValuTutorial" value="Registra"/> <br><br>
      </form>

      <?php
        if(isset($_POST["visualizzaValuTutorial"])){
          $codice = $_POST["codiceTutorial"];
          $tutorials = $dbh->getValutazioniTutorial($codice);


        foreach($tutorials as $tutorial): ?>
          <li><?php echo "TITOLO: " .$tutorial['Titolo'] . " - VOTO: " . $tutorial['Voto']  . " - NOTE: " . $tutorial['Note']  ;?></li> <br>
        <?php endforeach; }?>     

    </div>

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

      echo("<p>Registrazione avvenuta con successo!</p>");
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



  <!-- Inserimento Tutorial -->

  <div class="m-5" style ="text-align: left">
      <b><p class="col-md fs-5">INSERIMENTO TUTORIAL</p></b>
  
      <form method="post" enctype="multipart/form-data" name="formSessione">
  
        <label for="oraInizioTutorial">Orario Inizio</label><br>
        <input type="time" name="oraInizioTutorial" id="oraInizioTutorial"> <br><br>
  
        <label for="oraFineTutorial">Orario Fine</label><br>
        <input type="time" name="oraFineTutorial" id="oraFineTutorial"> <br><br>

        <label for="numSequenza">Numero Sequenza</label><br>
        <input type="text" name="numSequenza" id="numSequenza"> <br><br>

        <label for="titoloTutorial">Titolo Tutorial</label><br>
        <input type="text" name="titoloTutorial" id="titoloTutorial"> <br><br>

        <label for="abstractTutorial">Abstract</label><br>
        <input type="text" name="abstractTutorial" id="abstractTutorial"> <br><br>

        <label for="codiciTutorialIns">Codice Sessione</label><br>
        <?php $codici=$dbh->getSessioni() ?>
        <select name="codiciTutorialIns" id="codiciTutorialIns">
          <?php foreach($codici as $codice): ?>
            <option <?php  echo("value='" . $codice["Codice"] . "'"); ?> >  <?php echo($codice["Codice"]);   ?>  </option> <br>
          <?php endforeach; ?>
        </select> <br><br>

  
        <input type="submit" name="inserisciTutorial" id="inserisciTutorial" value="Registra"/>
      </form>
      
  </div>

  <?php
  if(isset($_POST["inserisciTutorial"])){
    $oraInizio=$_POST["oraInizioTutorial"];
    $oraFine=$_POST["oraFineTutorial"];
    $numSequenza=$_POST["numSequenza"];
    $titoloTutorial=$_POST["titoloTutorial"];
    $abstract=$_POST["abstractTutorial"];
    $codiceSessione=$_POST["codiciTutorialIns"];


    $dbh->inserisciTutorial($oraInizio, $oraFine, $numSequenza, $titoloTutorial, $abstract, $codiceSessione);
  }
  ?>



  <!-- Inserimento Articoli -->

  <div class="m-5" style ="text-align: left">
        <b><p class="col-md fs-5">INSERIMENTO ARTICOLO</p></b>
    
        <form method="post" enctype="multipart/form-data" name="formSessione">
    
          <label for="oraInizioArticoli">Orario Inizio</label><br>
          <input type="time" name="oraInizioArticoli" id="oraInizioArticoli"> <br><br>
    
          <label for="oraFineArticoli">Orario Fine</label><br>
          <input type="time" name="oraFineArticoli" id="oraFineArticoli"> <br><br>

          <label for="numSequenzaArticoli">Numero Sequenza</label><br>
          <input type="text" name="numSequenzaArticoli" id="numSequenzaArticoli"> <br><br>

          <label for="titoloArticoli">Titolo Articolo</label><br>
          <input type="text" name="titoloArticoli" id="titoloArticoli"> <br><br>

          <label for="numeroPagine">Numero Pagine</label><br>
          <input type="text" name="numeroPagine" id="numeroPagine"> <br><br>

          <label for="filePresentazione">File Presentazione</label><br>
          <input type="file" name="filePresentazione" id="filePresentazione"> <br><br>
          

          <label for="codiciArticoliIns">Codice Sessione</label><br>
          <?php $codici=$dbh->getSessioni() ?>
          <select name="codiciArticoliIns" id="codiciArticoliIns">
            <?php foreach($codici as $codice): ?>
              <option <?php  echo("value='" . $codice["Codice"] . "'"); ?> >  <?php echo($codice["Codice"]);   ?>  </option> <br>
            <?php endforeach; ?>
          </select> <br><br>

    
          <input type="submit" name="inserisciArticoli" id="inserisciArticoli" value="Registra"/>
        </form>
        
    </div>

    <?php
    if(isset($_POST["inserisciArticoli"])){
      $oraInizio=$_POST["oraInizioArticoli"];
      $oraFine=$_POST["oraFineArticoli"];
      $numSequenza=$_POST["numSequenzaArticoli"];
      $titoloArticolo=$_POST["titoloArticoli"];
      $numPagine=$_POST["numeroPagine"];
      $codiceSessione=$_POST["codiciArticoliIns"];

      $immagine=file_get_contents($_FILES["filePresentazione"]["tmp_name"]);
      $nomeFoto = basename($_FILES["filePresentazione"]["name"]);

      $dbh->caricaFileEsterni($_SESSION["username"]."_".$nomeFoto, $immagine);
      $ultimaFoto=$dbh->getUltimoFile();
      
      $dbh->inserisciArticoli($oraInizio, $oraFine, $numSequenza, $titoloArticolo, $numPagine, $ultimaFoto[0]["idFile"], $codiceSessione);
    }
    ?>



  <!-- Associazione Presentazione Tutorial -->
  
  <div class="m-5" style ="text-align: left">
      <b><p class="col-md fs-5">ASSOCIAZIONE PRESENTAZIONE TUTORIAL</p></b>
  
      <form method="post" enctype="multipart/form-data" name="formSessione">

        <label for="tutorialsWSpeaker">Codici Tutorial</label><br>
        <?php $codici=$dbh->getTutorialsWSpeakers(); ?>
        <select name="tutorialsWSpeaker" id="tutorialsWSpeaker">
          <?php foreach($codici as $codice): ?>
            <option <?php  echo("value='" . $codice["Codice"] . "'"); ?> >  <?php echo($codice["Codice"]);   ?>  </option> <br>
          <?php endforeach; ?>
        </select> <br><br>

        <label for="assocSpeaker">Username Speakers</label><br>
        <?php $speakers=$dbh->getSpeaker(); ?>
        <select name="assocSpeaker" id="assocSpeaker">
          <?php foreach($speakers as $speaker): ?>
            <option <?php  echo("value='" . $speaker["UsernameUtente"] . "'"); ?> >  <?php echo($speaker["UsernameUtente"]);   ?>  </option> <br>
          <?php endforeach; ?>
        </select> <br><br>

        <input type="submit" name="assocSpeakerSubmit" id="assocSpeakerSubmit" value="Registra"/>
      </form>
  </div>

  <?php
    if(isset($_POST["assocSpeakerSubmit"])){

      $speaker=$_POST["assocSpeaker"];
      $codice=$_POST["tutorialsWSpeaker"];

      $dbh->associaPresentazioneSpeaker($codice, $speaker);
    }
  ?>

  <!-- Associazione Presentazione Articoli -->
  
  <div class="m-5" style ="text-align: left">
      <b><p class="col-md fs-5">ASSOCIAZIONE PRESENTAZIONE ARTICOLI</p></b>
  
      <form method="post" enctype="multipart/form-data" name="formSessione">

        <label for="articolisWPresenter">Codici Articoli</label><br>
        <?php $codici=$dbh->getArticolisWPresenter(); ?>
        <select name="articolisWPresenter" id="articolisWPresenter">
          <?php foreach($codici as $codice): ?>
            <option <?php  echo("value='" . $codice["Codice"] . "'"); ?> >  <?php echo($codice["Codice"]);   ?>  </option> <br>
          <?php endforeach; ?>
        </select> <br><br>

        <label for="assocPresenter">Username Presenters</label><br>
        <?php $speakers=$dbh->getPresenters(); ?>
        <select name="assocPresenter" id="assocPresenter">
          <?php foreach($speakers as $speaker): ?>
            <option <?php  echo("value='" . $speaker["UsernameUtente"] . "'"); ?> >  <?php echo($speaker["UsernameUtente"]);   ?>  </option> <br>
          <?php endforeach; ?>
        </select> <br><br>

        <input type="submit" name="assocPresenterSubmit" id="assocPresenterSubmit" value="Registra"/>
      </form>
  </div>

  <?php
    if(isset($_POST["assocPresenterSubmit"])){

      $presenter=$_POST["assocPresenter"];
      $codice=$_POST["articolisWPresenter"];

      $dbh->associaPresentazionePresenter($codice, $presenter);
    }
  ?>

  <!-- Inserimento Valutazione articolo -->
  
  <div class="m-5" style ="text-align: left">
      <b><p class="col-md fs-5">INSERIMENTO VALUTAZIONE PRESENTAZIONE ARTICOLO</p></b>
  
      <form method="post" enctype="multipart/form-data" name="formSessione">

        <label for="articoliValu">Codici Articoli</label><br>
        <?php $codici=$dbh->getArticoliWVote($_SESSION["username"]); ?>
        <select name="articoliValu" id="articoliValu">
          <?php foreach($codici as $codice): ?>
            <option <?php  echo("value='" . $codice["Codice"] . "'"); ?> >  <?php echo($codice["Codice"]);   ?>  </option> <br>
          <?php endforeach; ?>
        </select> <br><br>

        <label for="votoArticolo">Voto</label><br>
        <input type="text" name="votoArticolo" id="votoArticolo" maxlength="100">  <br><br>

        <label for="noteArticolo">Note</label><br>
        <input type="text" name="noteArticolo" id="noteArticolo" maxlength="100">  <br><br>

        <input type="submit" name="insValuArticoli" id="insValuArticoli" value="Registra"/>
      </form>
  </div>

  <?php
    if(isset($_POST["insValuArticoli"])){

      $voto=$_POST["votoArticolo"];
      $codice=$_POST["articoliValu"];
      $note=$_POST["noteArticolo"];

      $dbh->inserisciValutazioneArticoli($voto, $_SESSION["username"], $note, $codice);
    }
  ?>




<!-- Inserimento Valutazione tutorial -->
  
<div class="m-5" style ="text-align: left">
      <b><p class="col-md fs-5">INSERIMENTO VALUTAZIONE PRESENTAZIONE TUTORIAL</p></b>
  
      <form method="post" enctype="multipart/form-data" name="formSessione">

        <label for="tutorialValu">Codici Articoli</label><br>
        <?php $codici=$dbh->getTutorialWVote($_SESSION["username"]); ?>
        <select name="tutorialValu" id="tutorialValu">
          <?php foreach($codici as $codice): ?>
            <option <?php  echo("value='" . $codice["Codice"] . "'"); ?> >  <?php echo($codice["Codice"]);   ?>  </option> <br>
          <?php endforeach; ?>
        </select> <br><br>

        <label for="votoTutorial">Voto</label><br>
        <input type="text" name="votoTutorial" id="votoTutorial" maxlength="100">  <br><br>

        <label for="noteTutorial">Note</label><br>
        <input type="text" name="noteTutorial" id="noteTutorial" maxlength="100">  <br><br>

        <input type="submit" name="insValuTutorial" id="insValuTutorial" value="Registra"/>
      </form>
  </div>

  <?php
    if(isset($_POST["insValuTutorial"])){

      $voto=$_POST["votoTutorial"];
      $codice=$_POST["tutorialValu"];
      $note=$_POST["noteTutorial"];

      $dbh->inserisciValutazioneTutorial($voto, $_SESSION["username"], $note, $codice);
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
//INSERIMENTO PRESENTER

} else if($_SESSION["tipo"] == "presenter") {
?>


<!-- Inserisci/modifica una foto -->

<div class="m-5" style ="text-align: left">
    <b><p class="col-md fs-5">INSERISCI/MODIFICA FOTO </p></b>

    <form method="post" enctype="multipart/form-data" name="formSessione">

      <label for="fotoPresenter">Foto Presenter</label><br>
      <input type="file" name="fotoPresenter" id="fotoPresenter"> <br><br>

      <input type="submit" name="modificaFotoPresenter" id="modificaFotoPresenter" value="Registra"/>
    </form>
    
</div>

<!-- Inserisci/modifica un cv -->

<div class="m-5" style ="text-align: left">
    <b><p class="col-md fs-5">INSERISCI/MODIFICA CV </p></b>

    <form method="post" enctype="multipart/form-data" name="formSessione">

      <label for="cvPresenter">CV Presenter</label><br>
      <input type="text" name="cvPresenter" id="cvPresenter" maxlength="30"> <br><br>

      <input type="submit" name="modificaCVPresenter" id="modificaCVPresenter" value="Registra"/>
    </form>
    
</div>

<!-- Modifica dipartimento Uni -->

<div class="m-5" style ="text-align: left">
    <b><p class="col-md fs-5">MODIFICA DIPARTIMENTO</p></b>

    <form method="post" enctype="multipart/form-data" name="formSessione">

      <label for="nomePresenter">Nome Universita Presenter</label><br>
      <input type="text" name="nomePresenter" id="nomePresenter"> <br><br>

      <label for="dipaPresenter">Nome Dipartimento Presenter</label><br>
      <input type="text" name="dipaPresenter" id="dipaPresenter"> <br><br>

      <input type="submit" name="modificaUniPresenter" id="modificaUniPresenter" value="Registra"/>
    </form>
    
</div>


<?php

  if(isset($_POST["modificaFotoPresenter"])){
    $immagine=file_get_contents($_FILES["fotoPresenter"]["tmp_name"]);

    $dbh->modificaFotoPresenter($_SESSION["username"], $immagine);
  }

  if(isset($_POST["modificaCVPresenter"])){
    $cv=$_POST["cvPresenter"];

    $dbh->modificaCVPresenter($_SESSION["username"], $cv);
  }

  if(isset($_POST["modificaUniPresenter"])){
    $nomeUni=$_POST["nomePresenter"];
    $nomeDipa=$_POST["dipaPresenter"];
      
    $dbh->modificaUniPresenter($_SESSION["username"], $nomeUni, $nomeDipa);
  }

} else if($_SESSION["tipo"] == "speaker") {
  ?>
  
  
  <!-- Inserisci/modifica una foto -->
  
  <div class="m-5" style ="text-align: left">
      <b><p class="col-md fs-5">INSERISCI/MODIFICA FOTO </p></b>
  
      <form method="post" enctype="multipart/form-data" name="formSessione">
  
        <label for="fotoSpeaker">Foto Speaker</label><br>
        <input type="file" name="fotoSpeaker" id="fotoSpeaker"> <br><br>
  
        <input type="submit" name="modificaFotoSpeaker" id="modificaFotoSpeaker" value="Registra"/>
      </form>
      
  </div>
  
  <!-- Inserisci/modifica un cv -->
  
  <div class="m-5" style ="text-align: left">
      <b><p class="col-md fs-5">INSERISCI/MODIFICA CV </p></b>
  
      <form method="post" enctype="multipart/form-data" name="formSessione">
  
        <label for="cvSpeaker">CV Speaker</label><br>
        <input type="text" name="cvSpeaker" id="cvSpeaker" maxlength="30"> <br><br>
  
        <input type="submit" name="modificaCVSpeaker" id="modificaCVSpeaker" value="Registra"/>
      </form>
      
  </div>
  
  <!-- Modifica dipartimento Uni -->
  
  <div class="m-5" style ="text-align: left">
      <b><p class="col-md fs-5">MODIFICA DIPARTIMENTO</p></b>
  
      <form method="post" enctype="multipart/form-data" name="formSessione">
  
        <label for="nomeSpeaker">Nome Universita Speaker</label><br>
        <input type="text" name="nomeSpeaker" id="nomeSpeaker"> <br><br>
  
        <label for="dipaSpeaker">Nome Dipartimento Speaker</label><br>
        <input type="text" name="dipaSpeaker" id="dipaSpeaker"> <br><br>
  
        <input type="submit" name="modificaUniSpeaker" id="modificaUniSpeaker" value="Registra"/>
      </form>
      
  </div>

  <!-- Inserimento risorsa aggiuntiva -->
  
  <div class="m-5" style ="text-align: left">
      <b><p class="col-md fs-5">INSERIMENTO RISORSA AGGIUNTIVA</p></b>
  
      <form method="post" enctype="multipart/form-data" name="formSessione">
  
        <label for="linkWeb">Link Web Tutorial</label><br>
        <input type="text" name="linkWeb" id="linkWeb"> <br><br>
  
        <label for="descRisorsa">Descrizione risorsa</label><br>
        <input type="text" name="descRisorsa" id="descRisorsa"> <br><br>

        <label for="codiciTutorial">Nome Sponsor</label><br>
        <?php $codici=$dbh->getSpeakerCodici($_SESSION["username"]) ?>
        <select name="codiciTutorial" id="codiciTutorial">
          <?php foreach($codici as $codice): ?>
            <option <?php  echo("value='" . $codice["CodiceTutorial"] . "'"); ?> >  <?php echo($codice["CodiceTutorial"]);   ?>  </option> <br>
          <?php endforeach; ?>
        </select> <br><br>

  
        <input type="submit" name="inserisciRisorsa" id="inserisciRisorsa" value="Registra"/>
      </form>
      
  </div>

  <!-- Modifica risorsa aggiuntiva -->
  
  <div class="m-5" style ="text-align: left">
      <b><p class="col-md fs-5">MODIFICA RISORSA AGGIUNTIVA</p></b>
  
      <form method="post" enctype="multipart/form-data" name="formSessione">
  
        <label for="linkWebMod">Link Web Tutorial</label><br>
        <input type="text" name="linkWebMod" id="linkWebMod"> <br><br>
  
        <label for="descRisorsaMod">Descrizione risorsa</label><br>
        <input type="text" name="descRisorsaMod" id="descRisorsaMod"> <br><br>

        <label for="codiciTutorial">Nome Sponsor</label><br>
        <?php $codici=$dbh->getSpeakerCodici($_SESSION["username"]) ?>
        <select name="codiciTutorialMod" id="codiciTutorialMod">
          <?php foreach($codici as $codice): ?>
            <option <?php  echo("value='" . $codice["CodiceTutorial"] . "'"); ?> >  <?php echo($codice["CodiceTutorial"]);   ?>  </option> <br>
          <?php endforeach; ?>
        </select> <br><br>

  
        <input type="submit" name="modificaRisorsa" id="modificaRisorsa" value="Registra"/>
      </form>
      
  </div>
  
  
  <?php
  
  if(isset($_POST["modificaFotoSpeaker"])){
    $immagine=file_get_contents($_FILES["fotoSpeaker"]["tmp_name"]);
    print_r("dio");
  
    $dbh->modificaFotoSpeaker($_SESSION["username"], $immagine);
  }
  
  if(isset($_POST["modificaCVSpeaker"])){
    $cv=$_POST["cvSpeaker"];
  
    $dbh->modificaCVspeaker($_SESSION["username"], $cv);
  }
  
  if(isset($_POST["modificaUniSpeaker"])){
    $nomeUni=$_POST["nomeSpeaker"];
    $nomeDipa=$_POST["dipaSpeaker"];
      
    $dbh->modificaUniSpeaker($_SESSION["username"], $nomeUni, $nomeDipa);
  }

  if(isset($_POST["inserisciRisorsa"])){
    $linkWeb=$_POST["linkWeb"];
    $descRisorsa=$_POST["descRisorsa"];
    $codiceTutorial=$_POST["codiciTutorial"];
      
    $dbh->inserimentoRisorsaSpeaker($linkWeb, $descRisorsa, $codiceTutorial, $_SESSION["username"]);
  }

  if(isset($_POST["modificaRisorsa"])){
    $linkWeb=$_POST["linkWebMod"];
    $descRisorsa=$_POST["descRisorsaMod"];
    $codiceTutorial=$_POST["codiciTutorialMod"];
      
    $dbh->modificaRisorsaSpeaker($linkWeb, $descRisorsa, $codiceTutorial, $_SESSION["username"]);
  }

}
?>


<?php
include('layouts/footer.php');
?>