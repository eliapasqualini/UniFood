<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.2.0/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/ristorante.css">
  <title>UniFood</title>
</head>

  <body>
    <?php
    include("php/config.php");
    $conn =new mysqli($servername, $username, $password, $db);
    ?>
    <!--Header-->
    <header class="header clearfix">
      <a href="index.php" class="header__logo">
      <img src="image/logo-header.png" alt="logo" width="50px" height="50px">
      </a>
      <a href="" class="header__icon-bar">
        <span></span>
        <span></span>
        <span></span>
      </a>
      <ul class="header__menu animate">
        <li class="header__menu__item"><a href="aiuto.html">Aiuto?</a></li>
        <li class="header__menu__item"><a href="contattaci.php">Contattaci</a></li>
      </ul>
    </header>

    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <!--bisogna ottenerlo con la query-->
          <h1>Benvenuto,nome ristorante</h1>
        </div>
        <div class="col-sm-12">
          <p>Aiutaci a tenere aggiornato il tuo menù</p>
        </div>
      </div>

      <div class="table-responsive">

    	  <?php
    		if ($conn->connect_errno) {
    		?>
    			<p class="error">Connessione fallita: <?php echo $conn->connect_errno; ?> <?php echo $conn->connect_error; ?></p>
    		<?php
    		}
    		else{
    			$query_sql="SELECT idPiatto, nome, prezzo, categoria FROM menù WHERE idRistorante = 1";
    			$result = $conn->query($query_sql);
    			if($result !== false){
    			?>
    			<table class="table table-hover table-bordered">
    			  <thead class="thead-dark">
    				<tr>
    				  <th scope="col">idPiatto</th>
    				  <th scope="col">Nome</th>
    				  <th scope="col">Prezzo</th>
              <th scope="col">Categoria</th>
    				</tr>
    			  </thead>
    			  <tbody>
    			<?php
    				if ($result->num_rows > 0) {
    					while($row = $result->fetch_assoc()) {
    						?>
    						<tr>
    							<td><?php echo $row["idPiatto"]; ?></td>
    							<td><?php echo $row["nome"]; ?></td>
    							<td><?php echo $row["prezzo"]; ?>&euro;</td>
                  <td><?php echo $row["categoria"]; ?></td>
    						</tr>
    						<?php
    					}
    				}
    			?>
    			  </tbody>
    			</table>
    		<?php
    		}
    		else{
    		?>
    			<p>Menù ancora vuoto, che aspetti?</p>
    		<?php
    		}
        ?>

      </div>
    </div>
    <div class="jumbotron jumbotron-fluid">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <h4>Modifica il tuo menù con pochi click</h4>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <h6>Inserisci l'id del piatto che intendi eliminare</h6>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
              <div class="form-group">
                <label for="Nome">idPiatto:</label>
                <input type="text" class="form-control" name="idPiatto" placeholder="Codice del piatto">
                <span class="error"></span>
              </div>
          </div>
                <div class="col-sm-12">
                  <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Elimina"><br/>
                  <span class="error"></span>
                </div>
              </form>
            </div>
            <hr>

            <div class="row">
              <div class="col-sm-12">
                <h6>Aggiungi un nuovo piatto</h6>
              </div>
              <div class="col-sm-12">
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#foodForm">Aggiungi</button>
              </div>
            </div>




            <!-- Modal-->
            <div class="modal" id="foodForm">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Aggiungi un nuovo piatto in pochi passi</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                    <form method="post" action="login.php">
                      <div class="form-group">
                        <label for="email"></label>
                        <input type="email" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="pwd">Password:</label>
                        <input type="password" class="form-control">
                      </div>
                      <div class="form-group form-check">
                        <label class="form-check-label">
                        <input class="form-check-input" type="checkbox"> Ricordami
                        </label>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary mr-auto">Accedi</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Chiudi</button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          </div>
        </div>
      </div>
    </div>


      <?php
        //Chiusura connessione con db
        $conn->close();
      }
      ?>
      </div>


    <!--Footer-->
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-sm-6 col-footer">
            <p>email: unifood@gmail.com</p>
          </div>
          <div class="col-sm-6 col-footer">
            <p>&copy; Elia Pasqualini & Marco Pazzaglia</p>
          </div>
        </div>
      </div>
    </footer>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/navbar.js" type="text/javascript"></script>
</body>
</html>
