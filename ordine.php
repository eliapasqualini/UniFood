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
  <link rel="stylesheet" type="text/css" href="css/ordine.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <title>UniFood</title>
</head>

  <body>
    <?php
    include("php/config.php");
    $conn =new mysqli($servername, $username, $password, $db);

    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    if(isset($_POST['delete'])){
      $sql = "DELETE FROM ordine WHERE idAccount = '".$id."'";
      $result = $conn->query($sql);
      $sql = "DELETE FROM account WHERE idAccount = '".$id."'";
      $result = $conn->query($sql);
      header("location: logout.php");
    }

    if ($conn->connect_errno) {
    ?>
      <p class="error">Connessione fallita: <?php echo $conn->connect_errno; ?> <?php echo $conn->connect_error; ?></p>
    <?php
    }
    else{
      $query_sql="SELECT * FROM `account` WHERE email = '" . $_SESSION['email'] . "'";
      $result = $conn->query($query_sql);
      $row = $result->fetch_assoc();
    ?>
    <!--Header-->
    <header class="header clearfix">
      <a href="#" class="header__logo">
      <img src="image/logo-header.png" alt="logo" width="50px" height="50px">
      </a>
      <a href="" class="header__icon-bar">
        <span></span>
        <span></span>
        <span></span>
      </a>
      <ul class="header__menu animate">
        <li class="header__menu__item">
          <div class="dropdown">
            <button class="dropbtn"><?php echo $row['nome']; ?>  <?php echo $row['cognome']; ?>
              <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
              <a href="logout.php">Logout</a>
              <a href="" data-toggle="modal" data-target="#emailForm">Modifica email</a>
              <a href="" data-toggle="modal" data-target="#passwordForm">Modifica password</a>
              <a href="" data-toggle="modal" data-target="#deleteForm">Elimina Account</a>
            </div>
          </div>
        </li>
        <li class="header__menu__item"><a href="carrello.php"><i class="fas fa-cart-arrow-down"></i></a></li>
      </ul>
    </header>


    <div class="container">
      <div class="row">
        <div class="col-4">
          <?php

            $query_sql="SELECT * FROM `ristorante` WHERE idRistorante = '" . $_GET['ristoranteID'] . "'";
            $result = $conn->query($query_sql);
            $row = $result->fetch_assoc();
            $sql = "SELECT idAccount FROM account WHERE email = '".$_SESSION['email']."'";
            $ris = $conn->query($sql);
            $res = $ris->fetch_assoc();
            $id = $res["idAccount"];
            if ($row['logo'] == null){
            ?>
            <img src='image/food.png' class='mr-5 image-risto' width='70%' height='auto' alt='ristorante'>
            <?php
            }
            else{

             echo '<img width="70%" height="auto" class="mr-5 image-risto" alt="ristorante" src="data:image/jpeg;base64,'.base64_encode( $row['logo'] ).'"/>';


             }
             ?>


        </div>
        <div class="col-8">
          <h1 class="titlee" valign=""><?php echo $row['nome']; ?> </h1>
        </div>
      </div>
    </div>


    <div class="container">
      <br><h2> Menu </h2> <hr>



    <div class="card table-responsive">
      <?php
      $query_sql="SELECT * FROM `menu` WHERE idRistorante = '" . $_GET['ristoranteID'] . "'";
      $result = $conn->query($query_sql);
      if ($result->num_rows > 0){

       ?>
      <table class="table shopping-cart-wrap">
        <form class="" name="piatti" method="post">
          <thead class="text-muted">
            <tr>
              <th scope="col">Piatto</th>
              <th scope="col" width="10%">Quantità</th>
              <th scope="col" >Prezzo</th>
              <th scope="col">Aggiungi</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $i = 0;
              $idPiatti = array();
              while($row = $result->fetch_assoc()) {
                array_push($idPiatti, $row['idPiatto']);

            ?>
            <tr>
      	       <td>

      		              <h5 class="title text-truncate"><?php echo $row["nome"]; ?> </h5>
      		                <dl class="param param-inline small">
      		                    <dt>Categoria: </dt>
                              <dd><?php echo $row["categoria"]; ?></dd>
      		                </dl>
      		                <dl class="param param-inline small">
                            <?php
                            $sql = "SELECT nomeIngrediente FROM `ingrediente` WHERE idPiatto = '" . $row['idPiatto'] . "'";
                            $result2 = $conn->query($sql);
                            ?>
      		                  <dt>Ingredienti: </dt>
                            <dd>
                              <?php  while ($righe = $result2->fetch_assoc()){

                                  echo $righe['nomeIngrediente'];
                                  echo "  ";
                                }
                              ?></dd>
      		                </dl>

      	           </td>
      	        <td>
                  <?php
                  echo "<input type='number' value='0' name='quantita".$i."'class='form-control'>";
                  ?>
      	        </td>
      	        <td>
              		<div class="price-wrap">
              			<var class="price"><?php echo $row["prezzo"]; ?>&euro;</var><br>
              			<small class="text-muted">(&euro; per ogni pz.)</small>
              		</div> <!-- price-wrap .// -->
      	        </td>
      	        <td>
                  <?php
                    echo "<button type='submit' class='btn btn-outline-success text-center' name='aggiungi".$i."'> <i class='fas fa-plus-circle'></i></button>";
                   ?>
      	        </td>
              </tr>
              <?php
                $i++;
              }


            }
            else {
            ?>
              <p>Nessun piatto a disposizione nel menù</p>
            <?php
            }
            ?>
            <!--prova
              <tr>
        	       <td>

        		              <h5 class="title text-truncate">Margherita</h5>
        		                <dl class="param param-inline small">
        		                    <dt>Categoria: </dt>
        		                      <dd>Pizza</dd>
        		                </dl>
        		                <dl class="param param-inline small">
        		                    <dt>Ingredienti: </dt>
        		                    <dd>Pomodoro, Mozzarella</dd>
        		                </dl>

        	        </td>
        	        <td>
                      <input type="number" value="0" class="form-control">
        	        </td>
        	        <td>
                		<div class="price-wrap">
                			<var class="price">5.5&euro;</var><br>
                			<small class="text-muted">(&euro; per ogni pz.)</small>
                		</div>
        	        </td>
        	        <td>
        	        <button class="btn btn-outline-success text-center"> <i class="fas fa-plus-circle"></i></button>
        	        </td>
                </tr>
                <tr>
                   <td>

                            <h5 class="title text-truncate">Margherita</h5>
                              <dl class="param param-inline small">
                                  <dt>Categoria: </dt>
                                    <dd>Pizza</dd>
                              </dl>
                              <dl class="param param-inline small">
                                  <dt>Ingredienti: </dt>
                                  <dd>Pomodoro, Mozzarella</dd>
                              </dl>

                    </td>
                    <td>
                        <input type="number" value="0" class="form-control">
                    </td>
                    <td>
                      <div class="price-wrap">
                        <var class="price">5.5&euro;</var><br>
                        <small class="text-muted">(&euro; per ogni pz.)</small>
                      </div>
                    </td>
                    <td>
                    <button class="btn btn-outline-success text-center"> <i class="fas fa-plus-circle"></i></button>

                    </td>
                  </tr>
                   -->
                </tbody>
              </form>
            </table>
          </div> <!-- card.// -->

        </div>
        <!--container end.//-->

        <?php
        $sql = "SELECT * FROM ordine ORDER BY idOrdine DESC";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
          $row = $result->fetch_assoc();
          $idOrdine = $row['idOrdine'];
          if ($row['stato'] == 1){
            $idOrdine = $row['idOrdine']+1;
          } else {}
        } else {
          $idOrdine = 1;
        }
        for ($j = 0; $j < $i; $j++){
          if (isset($_POST["aggiungi".$j])){
            $quantita = $_POST["quantita".$j];
            $sql = "INSERT INTO ordine (`idPiatto`, `idAccount`, `quantita`, `idRistorante`, `stato`, `idOrdine`) VALUES ('".$idPiatti[$j]."', '".$id."', '".$quantita."', '".$_GET['ristoranteID']."', '0', '".$idOrdine."')";
            $result = $conn->query($sql);
          }
        }
         ?>


    <footer>
      <div class="container-fluid">
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

    <?php
      if (!empty($_POST["email1"]) && !empty($_POST["email2"])){
          $email1 = test_input($_POST["email1"]);
          $email2 = test_input($_POST["email2"]);
          if (strcmp($email1,$email2) == 0){
            $sql = "SELECT email FROM account WHERE email = '$email1'";
            $result = $conn->query($sql);

            $count = mysqli_num_rows($result);
            if ($count == 0){
              $sql = "UPDATE `account` SET `email`= '".$email1."' WHERE `idAccount` = '".$id."'";
              $result = $conn->query($sql);
              $_SESSION["email"] = $email1;
            } else {
              $dbErr1 = "L'email inserita è già presente";
            }
          } else {
            $dbErr1 = "I due indirizzi email non corrispondono!";
          }
        }
     ?>

    <!-- Modal-->
    <div class="modal" id="emailForm">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Cambia la tua mail</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              <div class="form-group">
                <label for="email">Nuova email:</label>
                <input type="email" name="email1" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="pwd">Conferma email:</label>
                <input type="email" name="email2" class="form-control" required>
              </div>
              <span class="error"><?php echo $dbErr1;?></span>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary mr-auto submit">Conferma</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Chiudi</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php
    if (!empty($_POST["password1"]) && !empty($_POST["password2"])){
      $password1 = test_input($_POST["password1"]);
      $password2 = test_input($_POST["password2"]);
      if (strcmp($password1,$password2) == 0){
        $sql = "UPDATE `account` SET `password`= '".$password1."' WHERE `idAccount` = '".$id."'";
        $result = $conn->query($sql);
      } else {
        $dbErr2 = "Le due password non corrispondono!";
      }
    }
     ?>


    <!-- Modal-->
    <div class="modal" id="passwordForm">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Cambia la tua password</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              <div class="form-group">
                <label for="email">Nuova password:</label>
                <input type="password" name="password1" class="form-control" required>
              </div>
              <div class="form-group">
                <label for="pwd">Conferma password:</label>
                <input type="password" name="password2" class="form-control" required>
              </div>
              <span class="error"><?php echo $dbErr2;?></span>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary mr-auto submit">Conferma</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Chiudi</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>


    <!-- Modal-->
    <div class="modal" id="deleteForm">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Elimina account</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              <div class="form-group">
                <label>Sei sicuro di voler eliminare l'account?</label>
              </div>
              <div class="modal-footer">
                  <button type="submit" name="delete" class="btn btn-primary mr-auto submit">Conferma</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Chiudi</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php
      //Chiusura connessione con db
      $conn->close();
    }
    ?>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/navbar.js" type="text/javascript"></script>
  </body>
</html>
