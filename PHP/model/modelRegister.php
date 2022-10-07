<!doctype html>
<html lang="it">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Registrazione</title>
  <!-- js -->
  <script type="text/javascript" src="pages/register.js"></script>
  <!-- CSS-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/carousel/">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Custom CSS -->
  <link href="css/loginStyle.css" rel="stylesheet">
</head>

<body class="text-center">
  <main class="form-signin w-100 m-auto">

  <img class="mb-4" src="risorse/immagini/logo.png" alt="" height="100px">

    <form name="formRegister" action="" method="post"> <!-- post per maggiore sicurezza, per passare dati sensibili alla pagina --> 

      <div class="form-floating">
        <input name="emailRegister" type="email" class="form-control" id="emailInput" placeholder="name@example.com">
        <label for="floatingInput">Email</label>
      </div>

      <div class="form-floating">
        <input name="passwordRegister" type="password" class="form-control" id="passwordInput" placeholder="Password">
        <label for="floatingPassword">Password</label>
      </div>

      <div class="form-floating">
        <input name="nomeRegister" type="text" class="form-control" id="nomeInput" >
        <label for="floatingInput">Nome</label>
      </div>

      <div class="form-floating">
        <input name="cognomeRegister" type="text" class="form-control" id="cognomeInput">
        <label for="floatingInput">Cognome</label>
      </div>

      <div class="form-floating">
        <input name="luogoNascitaRegister" type="text" class="form-control" id="luogoNascitaInput">
        <label for="floatingInput">Luogo nascita</label>
      </div>

      <div class="form-floating">
        <input name="dataNascitaRegister" type="date" class="form-control" id="dataNascitaInput">
        <label for="floatingInput">Data nascita</label>
      </div>

      <div class="form-floating">
        <select id="tipoUtente" name="tipoUtente">
          <option value="base">
            Base
          </option>
          <option value="presenter">
            Presenter
          </option>
          <option value="speaker">
            Speaker
          </option>
          <option value="amministratore">
            Amministratore
          </option>
        </select>
      </div>

      <div class="form-floating">
        <input name="cvRegister" type="file" class="form-control" id="cvRegister" >
        <label for="floatingInput">Curriculum Vitae</label>
      </div>

      <div class="form-floating">
        <input name="fotoRegister" type="file" class="form-control" id="fotoRegister">
        <label for="floatingInput">Foto</label>
      </div>

      <div class="form-floating">
        <input name="dipartimentoRegister" type="text" class="form-control" id="dipartimentoRegister">
        <label for="floatingInput">Dipartimento</label>
      </div>

      <button class="w-100 btn btn-lg btn-primary mt-3" id="submit" name="submit" onclick="register()" type="submit">Registrati</button>
    </form>
  </main>
</body>

<?php
  require_once("../connessione.php"); 

  // verifico che il pulsante venga cliccato
  if(isset($_POST["submit"])){
    if(!empty($_POST["emailRegister"]) && !empty($_POST["passwordRegister"]) 
    && !empty($_POST["nomeRegister"]) && !empty($_POST["cognomeRegister"]) 
    && !empty($_POST["dataNascitaRegister"])  && !empty($_POST["luogoNascitaRegister"])){

      $username=$_POST["emailRegister"];
      $password=$_POST["passwordRegister"];
      $nome=$_POST["nomeRegister"];
      $cognome=$_POST["cognomeRegister"];
      $luogo=$_POST["luogoNascitaRegister"];
      $data=$_POST["dataNascitaRegister"];
      $tipo=$_POST["tipoUtente"];

      $dbh->insertUser($username, $password, $nome, $cognome, $luogo, $data);

      
      if($tipo=="amministratore"){
        $dbh->insertAmministratore($username);


      } else if($tipo=="speaker"){
        $immagine=file_get_contents($_FILES["fotoRegister"]);


      } else if($tipo=="presenter"){

      }

      $_SESSION["username"]=$_POST[$username];

    } 

  }

  if(!empty($_SESSION["username"])){ 
    // non c'è bisogno che user faccia il login, spostiamo lo user in una pagina
    // se lo user entra nella pagina login ma si è già loggato, lo reinderizzamo alla pagina home
    header("location: modelHome.php"); 
  }

?>

</html>