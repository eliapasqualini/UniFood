<?php
session_start();
?>
<!DOCTYPE html>
<html lang="it">
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
    $idRistorante = $idPiatto = "";
    $nameErr = $prezzoErr = $categoriaErr = "";
    $conn =new mysqli($servername, $username, $password, $db);
    ?>
    <!--Header-->
    <header class="header clearfix">
      <a href="#" class="header__logo">
      <img src="image/logo-header.png" alt="logo" width="50" height="50">
      </a>
      <a href="" class="header__icon-bar">
        <span></span>
        <span></span>
        <span></span>
      </a>
      <ul class="header__menu animate">
        <li class="header__menu__item"><a href="logout.php">Logout</a></li>
      </ul>
    </header>

    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <?php
      		if ($conn->connect_errno) {
      		?>
      			<p class="error">Connessione fallita: <?php echo $conn->connect_errno; ?> <?php echo $conn->connect_error; ?></p>
      		<?php
      		}
      		else{
            $query_sql="SELECT idAccount FROM `account` WHERE email = '" . $_SESSION['email'] . "'";
            $result = $conn->query($query_sql);
            $row = $result->fetch_assoc();
            $query_sql="SELECT * FROM `ristorante` WHERE idAccount = '" . $row['idAccount'] . "'";
            $result = $conn->query($query_sql);
            $row = $result->fetch_assoc();
            $idRistorante = $row["idRistorante"];
            $nomeRist = $row["nome"];

            if(isset($_POST['piatto'])){
              if(!empty($_POST['nome'])){
                if(!empty($_POST['prezzo'])){
                  if(!empty($_POST['categoria'])){
                    //inserisco il piatto
                    $query = "SELECT MAX(idPiatto) FROM menu";
                    $ris = $conn->query($query);
                    $righe = $ris->fetch_assoc();
                    $sql = "SELECT COUNT(idPiatto) FROM adminmenu";
                    $r = $conn->query($sql);
                    $i = $r->fetch_assoc();
                    $id = $righe["MAX(idPiatto)"] + $i["COUNT(idPiatto)"]+1;
                    $sql = "INSERT INTO adminmenu (`idRistorante`,`nome`,`prezzo`,`categoria`, `idPiatto`) VALUES ('" . $idRistorante . "', '" . $_POST['nome'] . "', '" . $_POST['prezzo'] . "', '" . $_POST['categoria'] . "', '".$id."')";
                    $result = $conn->query($sql);
                    $sql = "SELECT idPiatto FROM `adminmenu` WHERE nome= '" . $_POST['nome'] . "' AND idRistorante = '" . $idRistorante . "'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0){
                      $row = $result->fetch_assoc();
                      for ($i = 0; $i < count($_POST['ingredienti']); $i++)
                      {
                        //inserisco gli ingredienti
                        if($_POST['ingredienti'][$i] != ""){
                          $query = "SELECT MAX(idIngrediente) FROM ingrediente";
                          $ris = $conn->query($query);
                          $righe = $ris->fetch_assoc();
                          $sql = "SELECT COUNT(idIngrediente) FROM adminingrediente";
                          $r = $conn->query($sql);
                          $s = $r->fetch_assoc();
                          $id = $righe["MAX(idIngrediente)"] + $s["COUNT(idIngrediente)"]+1;
                          $sql = "INSERT INTO adminingrediente (`idPiatto`,`idRistorante`,`nomeIngrediente`,`idIngrediente`) VALUES ('" . $row['idPiatto'] . "','" . $idRistorante . "', '" . $_POST['ingredienti'][$i] . "','".$id."')";
                          $result = $conn->query($sql);
                        }

                      }
                    }
                  }  else{
                      $categoriaErr = "Inserisci una categoria";
                    }
                  }
                  else{
                    $prezzoErr = "Inserisci un prezzo";
                  }
                }
                else{
                  $nameErr = "Inserisci un nome";
                }
              }

          ?>
          <h1>Benvenuto, <?php echo $nomeRist ?></h1>
        </div>
        <div class="col-sm-12">
          <h3>Questi sono gli ordini riservati a te</h3>
          <div class="table-responsive">

        	  <?php

        			$query_sql="SELECT idOrdine, idPiatto, quantita, stato FROM ordine WHERE idRistorante = $idRistorante";
        			$result = $conn->query($query_sql);
        			if($result !== false){
                if ($result->num_rows > 0) {
        			?>
        			<table class="table table-hover table-bordered">
        			  <thead class="thead-dark">
        				<tr>
        				  <th scope="col">N. ordine</th>
        				  <th scope="col">Piatto</th>
        				  <th scope="col">Quantità</th>
                  <th scope="col">Categoria</th>
                  <th scope="col">Stato</th>
        				</tr>
        			  </thead>
        			  <tbody>
        			<?php
        				while($row = $result->fetch_assoc()) {
                  $query_sql="SELECT nome, categoria FROM menu WHERE idRistorante = $idRistorante AND idPiatto = '" . $row['idPiatto'] . "'";
                  $result2 = $conn->query($query_sql);
                  $row2 = mysqli_fetch_assoc($result2);
                  ?>
      						<tr>
      							<td><?php echo $row["idOrdine"]; ?></td>
      							<td><?php echo $row2["nome"]; ?></td>
      							<td><?php echo $row["quantita"]; ?></td>
                    <td><?php echo $row2["categoria"]; ?></td>
                    <td><?php echo $row["stato"]; ?></td>
      						</tr>
      						<?php
        				  }
        				} else{
            		?>
            			<p>Nessun ordine, rimani aggiornato</p>
            		<?php
            		}
                ?>

        			  </tbody>
        			</table>
        		<?php
        		}
        		else{
        		?>
        			<p>Nessun ordine, rimani aggiornato</p>
        		<?php
        		}
            ?>

          </div>

        </div>
        <div class="col-sm-12">
          <h3>Aiutaci a tenere aggiornato il tuo menù</h3>
        </div>
      </div>


      <div class="table-responsive">

    	  <?php

    			$query_sql="SELECT idPiatto, nome, prezzo, categoria FROM menu WHERE idRistorante = $idRistorante";
    			$result = $conn->query($query_sql);
    			if($result !== false){
            if ($result->num_rows > 0) {
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
    				}else{
        		?>
        			<p>Menù ancora vuoto, che aspetti?</p>
            <?php
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
            <h3>Modifica il tuo menù con pochi click</h3>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <p>Inserisci l'id del piatto che intendi eliminare</p>
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
                  <input type="submit" name="delete" class="btn btn-primary btn-lg" value="Elimina"><br/>
                  <?php
                    if(isset($_POST['delete'])){
                      if(isset($_POST['idPiatto'])){
                        $query_sql ="SELECT idPiatto from menu WHERE idPiatto = '" . $_POST['idPiatto'] . "' AND idRistorante = $idRistorante";
                        $result = $conn->query($query_sql);
                        if($result !== false){
                          if ($result->num_rows > 0) {
                            echo "delete";

                            $sql = "DELETE FROM ingrediente WHERE idPiatto = '" . $_POST['idPiatto'] . "' AND idRistorante = $idRistorante";
                            $result = $conn->query($sql);
                            $sql = "DELETE FROM ordine WHERE idPiatto = '" . $_POST['idPiatto'] . "' AND idRistorante = $idRistorante";
                            $result = $conn->query($sql);
                            $sql = "DELETE FROM menu WHERE idPiatto = '" . $_POST['idPiatto'] . "' AND idRistorante = $idRistorante";
                            $result = $conn->query($sql);
                            $sql = "DELETE FROM adminingrediente WHERE idPiatto = '" . $_POST['idPiatto'] . "' AND idRistorante = $idRistorante";
                            $result = $conn->query($sql);
                            $sql = "DELETE FROM adminmenu WHERE idRistorante = $idRistorante";
                            $result = $conn->query($sql);
                            $sql = "DELETE FROM adminristorante WHERE idRistorante = $idRistorante";
                            $result = $conn->query($sql);
                            header("location: ristorante.php");
                          }
                          else{
                    ?>
                      <span class="error">L'id corrispondente non esiste nel menù</span>
                    <?php
                  }
                }
                else{
                  ?>
                    <span class="error">L'id corrispondente non esiste nel menù</span>
                  <?php
                }
              }
              else{
                ?>
                  <span class="error">Inserisci un id</span>
                <?php
              }
            }
                  ?>
                </div>
              </form>
            </div>
            <hr>

            <div class="row">
              <div class="col-sm-12">
                <p>Aggiungi un nuovo piatto</p>
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
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                      <div class="form-group">
                        <label for="nome">Nome</label>
                        <input type="text" class="form-control" name="nome">
                        <span class="error"><?php echo $nameErr;?></span>
                      </div>
                      <div class="form-group">
                        <label for="prezzo">Prezzo</label>
                        <input type="number" class="form-control" name="prezzo">
                        <span class="error"><?php echo $prezzoErr;?></span>
                      </div>
                      <div class="form-group">
                        <label for="categoria">Categoria:</label>
                        <select name="categoria">
                          <?php
                            $sql = "SELECT * FROM categoria";
                            $result = $conn->query($sql);
                            while ($row = $result->fetch_assoc()){
                            echo "<option value='".$row[categoria]."'>".$row['categoria']."</option>";
                            }
                           ?>

                        </select>
                      </div>
                      <div class="form-group add_ingredienti">
                        <div id="dynamicInput">
                          <label for="ingredienti[]">Ingredienti</label>
                          <input class="form-control" name="ingredienti[]" type='text'>
                        </div>
                        <input class="form-control" type="button" value="Add" onclick="addInput('dynamicInput');" />
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="piatto" class="btn btn-primary mr-auto">Aggiungi</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Chiudi</button>
                  </div>
                  </form>
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


    <!--Footer-->
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-sm-6 col-footer">
            <p>email: unifoodsm@gmail.com</p>
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
  <script>

    function addInput(divName){
        var newDiv=document.createElement('div');
        newDiv.innerHTML="<input class='form-control' name='ingredienti[]'' type='text'>";

        newDiv.innerHTML=newDiv.innerHTML+"</input>";
        document.getElementById(divName).appendChild(newDiv);
    }
  </script>
</body>
</html>
