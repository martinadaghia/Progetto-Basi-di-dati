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

    <img class="mb-4" src="images/animalhouse.png" alt="" height="100px">

    <div class="form-floating">
      <input type="email" class="form-control" id="emailInput" placeholder="name@example.com">
      <label for="floatingInput">Email</label>
    </div>

    <div class="form-floating">
      <input type="password" class="form-control" id="passwordInput" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div>

    <button class="w-100 btn btn-lg btn-primary" id="submit" onclick="register()" type="submit">Registrati</button>
  </main>
</body>

</html>