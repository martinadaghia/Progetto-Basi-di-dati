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
    <main class="form-signin w-100 m-auto">
        <img class="mb-4" src="risorse/immagini/logo.png" alt="" height="100px">

        <div class="form-floating">
          <input type="email" class="form-control" id="emailInput" placeholder="name@example.com">
          <label for="floatingInput">Email</label>
        </div>

        <div class="form-floating">
          <input type="password" class="form-control" id="passwordInput" placeholder="Password">
          <label for="floatingPassword">Password</label>
        </div>

        <button class="w-100 btn btn-lg btn-primary" onclick="login()" type="submit">Login</button>
        <p style="margin-top: 10px;">Se non hai un account <a href="/registerPage">Registrati!</a></p>
    </main>
  </body>
</html>
