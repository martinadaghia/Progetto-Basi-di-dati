<?php
define("TITLE", "Home Page");
include ('layouts/header.php');
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
        
        <!-- chi siamo -->
        <div class="container mt-5 pt-5 mb-5 pb-5">
          <div class="row">
            <!-- 12 colonne-->
            <div class="col-lg-4 my-auto">
              <img src="./risorse/immagini/chisiamo.jpg" class="img-fluid" alt="">
            </div>
            <div class="col-lg-8 my-auto">
              <h2 class="mb-3">Chi siamo</h2>
              <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Perferendis vitae eos, illo nisi animi natus mollitia consequuntur voluptate architecto accusantium repellat placeat magnam praesentium sunt explicabo quia commodi aperiam aliquam.</p>
            </div>

          </div>
        </div>

        <!-- Servizi -->
        <div class="container">
          <div class="row">
            <div class="col-12 text-center">
              <div class="h2">Servizi</div>
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-lg-3 text-center">
              <h3>Conferenze</h3>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
            <div class="col-lg-3 text-center">
              <h3>Conferenze</h3>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
            <div class="col-lg-3 text-center">
              <h3>Conferenze</h3>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
            <div class="col-lg-3 text-center">
              <h3>Conferenze</h3>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
          </div>
        </div>

        <!-- contatti -->
        <div class="container mt-5 pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 offset-lg-3">
              <h2 class="text-center">Contatti</h2>
              <form>
                <div class="mb-3">
                  <label for="nome" class="form-label">Nome: </label>
                  <input type="text" class="form-control" id="nome" aria-describedby="nome">
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email: </label>
                  <input type="email" class="form-control" id="email" aria-describedby="email">
                </div>
                <div class="mb-3">
                  <label for="messaggio" class="form-label">Messaggio: </label>
                  <textarea name="messaggio" id="" class="form-control" cols="30" rows="10"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Invia</button>
              </form>
            </div>
          </div>
        </div>

<?php
include ('layouts/footer.php');
?>