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

      

      <button class="w-100 btn btn-lg btn-primary mt-3" id="submit" name="submit" onclick="register()" type="submit">Registrati</button>
    </form>
  </main>
</body>

<?php
  require_once("../connessione.php"); 

  // verifico che il pulsante venga cliccato
  if(isset($_POST["submit"])){
    if(!empty($_POST["cvRegister"]) && !empty($_POST["fotoRegister"]) 
    && !empty($_POST["dipartimentoRegister"])){

      $cv=$_POST["cvRegister"];
      $foto=$_POST["fotoRegister"];
      $dipartimento=$_POST["dipartimentoRegister"];



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