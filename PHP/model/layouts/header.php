<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo TITLE; ?></title>
        

        <!-- bootstrap -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


        <!-- css -->
        <link rel="stylesheet" href="css/homeStyle.css">
    </head>
    <body>
        <!-- barra di navigazione -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
            <img src="risorse/immagini/logoHomepage.png" height="80px">

              <a class="navbar-brand" href="../model/modelHome.php">CONFVIRTUAL</a>

              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
              </button>

              <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0"> <!-- ms = margin left = sposto la barra di navigazione a destra--> 
                  <li class="nav-item"> 
                    <a class="nav-link" href="../model/modelHome.php">Home</a>
                  </li>
                  <?php
                      
                    if(isset($_POST["submit"])){
                      unset($_SESSION["username"]);
                      header("location: modelHome.php"); 
                    }

                    if(!isset($_SESSION["username"])){
                      echo '<li class="nav-item">';
                      echo '<a class="nav-link" href="../model/modelLogin.php">Login</a>';
                      echo '</li>';
                      echo '<li class="nav-item">';
                      echo '<a class="nav-link" href="../model/modelRegister.php">Registrati</a>';
                      echo '</li>';
                    } else {
                      if($_SESSION["tipo"] != "utente"){
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="../model/modelProfilo.php">Profilo</a>';
                        echo '</li>';
                      }

                      echo '<li class="nav-item">';
                      echo '<a class="nav-link" href="../model/modelConferenze.php">Conferenze</a>';
                      echo '</li>';
                      echo '<li class="nav-item">';
                      echo '<a class="nav-link" href="../model/modelSessioni.php">Sessioni</a>';
                      echo '</li>';


                      /*echo '<li class="nav-item">';
                      echo '<a class="nav-link" href="../model/modelProfiloUtente.php">Profilo Utente</a>';
                      echo '</li>';*/

                      
                      echo '<li class="nav-item">';
                      echo '<form name="formLogin" method="post">';
                      echo '<input type="submit" id="submit" name="submit" value="Logout" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1"></input>';
                      echo '</form>';
                      echo '</li>';
                      
                      
                    } 
                    
                    
                    


                    


                  ?>
                </ul>
              </div>

            </div>
          </nav>
