<!doctype html>
<html lang="it">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Login</title>
  <!-- js -->
  <!-- CSS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/sign-in/">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
  <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link href="css/loginStyle.css" rel="stylesheet">
</head>



<body class="text-center">
  <?php
    require_once("../connessione.php"); 

    $user = !empty($_POST['emailLogin']) ? $_POST['emailLogin'] : '';
    $password = !empty($_POST['emailPassword']) ? $_POST['emailPassword'] : '';

    
  ?>


  <main class="form-signin w-100 m-auto">

    <img class="mb-4" src="risorse/immagini/logo.png" alt="" height="100px">

    <form name="formLogin" method="post"> <!-- post per maggiore sicurezza, per passare dati sensibili alla pagina --> 

      <div class="form-floating">
        <input name="emailLogin" value="<?php echo($user)  ?>" type="text" class="form-control" id="emailInput" placeholder="Username">
        <label for="floatingInput">Username</label>
      </div>

      <div class="form-floating">
        <input name="passwordLogin" value="<?php echo($password)  ?>" type="password" class="form-control" id="passwordInput" placeholder="Password">
        <label for="floatingPassword">Password</label>
      </div>

      <button class="w-100 btn btn-lg btn-primary" onclick="login()" type="submit">Login</button>
      <p style="margin-top: 10px;">Se non hai un account <a href="/registerPage">Registrati!</a></p>
    </form>

  </main>
</body>

<?php
  
  // controllo che ci sia scritto qualcosa nel textfield
  if(!empty($_POST["emailLogin"]) && !empty($_POST["passwordLogin"])){
    $risultatiQuery=$dbh->getUtenteReal($_POST["emailLogin"], $_POST["passwordLogin"]);

    // controllo se la query  ritorna qualcosa
    // if(count($risultatiQuery)>0 && $_POST["emailPassword"]==$risultatiQuery[0]['Password']){
    //   $_SESSION["username"]=$_POST["emailLogin"]; // per non perdere lo username
    // }

    if($risultatiQuery[0]["@ok"] == "OK"){
      $_SESSION["username"] = $_POST["emailLogin"]; // per non perdere lo username
      $_SESSION["tipo"] = $risultatiQuery[0]["@tipo"]; // per non perdere tipo di utente
    }

  }

  if(isset($_SESSION["username"])){ 
    // non c'è bisogno che user faccia il login, spostiamo lo user in una pagina
    // se lo user entra nella pagina login ma si è già loggato, lo reinderizzamo alla pagina home
    header("location: modelHome.php"); 
  }


?>

</html>
