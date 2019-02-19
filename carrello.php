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
  <link rel="stylesheet" type="text/css" href="css/carrello.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>UniFood</title>
</head>
  <body>
    <?php
    include("php/config.php");
    $conn =new mysqli($servername, $username, $password, $db);

    $carta = $idOrdine = "";
    $cartaErr = "";
    $dbErr1 = $dbErr2 = "";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';

    $mail = new PHPMailer(true);
    $mail1 = new PHPMailer(true);
    $mail2 = new PHPMailer(true);

    if(!empty($_POST['intestatario'])){
      if (!preg_match("/^[a-zA-Z ]*$/",$_POST['intestatario'])) {
        $cartaErr = "Nel nome dell'intestatario sono ammessi solo lettere o spazi";
      }
      if ((strlen($_POST['creditCardNumber']) !== 12)) {
        $cartaErr = "La carta deve contenere 12 numeri";
      }
      if(!empty($_POST['cvv'])){
        if (strlen($_POST['cvv']) != 3){
          $cartaErr = "Il cvv deve essere composto da 3 numeri";
        }
      }
      if ($cartaErr == ""){
        $carta = "Carta inserita correttamente";
      }
    }

    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
    if ($conn->connect_errno) {
    ?>
      <p class="error">Connessione fallita: <?php echo $conn->connect_errno; ?> <?php echo $conn->connect_error; ?></p>
    <?php
    }
    else{





      $sql = "SELECT * FROM account WHERE email = '".$_SESSION['email']."'";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $nome = $row['nome'];
      $cognome = $row['cognome'];
      $id = $row['idAccount'];


      if(isset($_POST['delete'])){
        $sql = "DELETE FROM ordine WHERE idAccount = '".$id."'";
        $result = $conn->query($sql);
        $sql = "DELETE FROM account WHERE idAccount = '".$id."'";
        $result = $conn->query($sql);
        header("location: logout.php");
      }

        ?>
    <!--Header-->
    <header class="header clearfix">
      <a href="cliente.php" class="header__logo">
      <img src="image/logo-header.png" alt="logo" width="50" height="50">
      </a>
      <a href="" class="header__icon-bar">
        <span></span>
        <span></span>
        <span></span>
      </a>
      <ul class="header__menu animate">
        <li class="header__menu__item">
          <div class="dropdown">
            <button class="dropbtn"><?php echo $nome; ?>  <?php echo $cognome; ?>
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
<?php
    $sql = "SELECT * FROM account WHERE tipo = 'fattorino' AND disponibilita = '1'";
    $result = $conn->query($sql);
    if ($result->num_rows == 0){
      ?>
      <div class="container">
        <h3>Non sono disponibili fattorini</h3>
      </div>

      <?php
    } else {
      ?>

    <div class="container-fluid">
      <h1>Carrello</h1>
      <?php
      $sql = "SELECT * FROM ordine WHERE idAccount = '".$id."' AND stato = '0'";
      $result = $conn->query($sql);
      $count = mysqli_num_rows($result);
      $somma = 0;
      if($count > 0){
      ?>
      <div class="row">
        <div class="col-sm-12 col-lg-8">
          <div class="table-responsive">
            <form class="" name="piatti" method="post">
              <table class="table">
                <tr>
                  <th scope="col">Piatto</th>
                  <th scope="col">Prezzo</th>
                  <th scope="col">Quantità</th>
                  <th scope="col">Rimuovi</th>
                </tr>
                <tbody>
                  <tr>
                    <?php
                    $i = 0;
                    $idPiatti = array();
                      while ($row = $result->fetch_assoc()){
                        $idOrdine = $row['idOrdine'];
                        $query_sql = "SELECT * FROM menu WHERE idPiatto = '".$row['idPiatto']."' AND idRistorante = '".$row['idRistorante']."'";
                        $res = $conn->query($query_sql);
                        while ($ris = $res->fetch_assoc()){
                          $somma = $somma + ($ris['prezzo']*$row['quantita']);
                          array_push($idPiatti, $ris["idPiatto"]);
                     ?>
                    <td><?php echo $ris['nome'] ?></td>
                    <td><?php echo $ris['prezzo'] ?>€</td>
                    <td><?php echo $row['quantita'] ?></td>
                    <?php
                      echo "<td><button type='submit' class='btn btn-outline-danger text-center' name='elimina".$i."'> <i class='fas fa-times-circle'></i></button></td>";
                     ?>
                  </tr>
                  <?php
                    }
                    $i++;
                  }

                  for ($j = 0; $j < $i; $j++){
                    if(isset($_POST["elimina".$j])){
                      $sql = "DELETE FROM ordine WHERE idPiatto = '".$idPiatti[$j]."'";
                      $result = $conn->query($sql);
                      header("location: carrello.php");
                    }

                  }

                  if(isset($_POST['paga'])){
                    $sql = "SELECT * FROM account WHERE tipo = 'fattorino' AND disponibilita = '1' ORDER BY RAND()";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $fattorino = $row['idAccount'];
                    $emailF = $row['email'];
                    $data = date ("d/m/Y");
                    $sql = "INSERT INTO consegna (`idOrdine`, `idAccount`, `data`, `orario`, `aula`) VALUES ('".$idOrdine."', '".$fattorino."','".$data."', '".$_POST['ora']."', '".$_POST['aula']."')";
                    $result = $conn->query($sql);
                    $sql = "UPDATE ordine SET stato = '1' WHERE idOrdine = '".$idOrdine."'";
                    $result = $conn->query($sql);
                    $sql = "SELECT idRistorante FROM ordine WHERE idOrdine = '".$idOrdine."'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $idRist = $row['idRistorante'];
                    $query_sql = "SELECT * FROM account WHERE idRistorante = '".$idRist."'";
                    $result = $conn->query($query_sql);
                    $row = $result->fetch_assoc();
                    $emailR = $row['email'];
                    $sql = "SELECT * FROM ristorante WHERE idRistorante = '".$idRist."'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $nomeR = $row['nome'];
                    $sql = "SELECT * FROM ordine WHERE idOrdine = '".$idOrdine."'";
                    $result = $conn->query($sql);
                    $piatti = array();
                    while ($row = $result->fetch_assoc()){
                      array_push($piatti, $row['idPiatto']);

                    }

                    try {
                      $mail->SMTPDebug = 2;
                      $mail->isSMTP();
                      $mail->Host = 'smtp.gmail.com';
                      $mail->SMTPAuth = true;
                      $mail->Username = 'unifoodsm@gmail.com';
                      $mail->Password = 'unifoodunifood';
                      $mail->SMTPSecure = 'tls';
                      $mail->Port = 587;

                      $mail->setFrom('unifoodsm@gmail.com');

                      $mail1->SMTPDebug = 2;
                      $mail1->isSMTP();
                      $mail1->Host = 'smtp.gmail.com';
                      $mail1->SMTPAuth = true;
                      $mail1->Username = 'unifoodsm@gmail.com';
                      $mail1->Password = 'unifoodunifood';
                      $mail1->SMTPSecure = 'tls';
                      $mail1->Port = 587;

                      $mail1->setFrom('unifoodsm@gmail.com');

                      $mail2->SMTPDebug = 2;
                      $mail2->isSMTP();
                      $mail2->Host = 'smtp.gmail.com';
                      $mail2->SMTPAuth = true;
                      $mail2->Username = 'unifoodsm@gmail.com';
                      $mail2->Password = 'unifoodunifood';
                      $mail2->SMTPSecure = 'tls';
                      $mail2->Port = 587;

                      $mail2->setFrom('unifoodsm@gmail.com');
                      $mail->addAddress($emailF);

                      $mail->isHTML(true);
                      $mail->Subject = "nuova consegna";
                      $mail->Body = "Devi consegnare un ordine alle ".$_POST['ora']." nell'".$_POST['aula']." dal ristorante ".$nomeR;
                      header("location: cliente.php");
                      $mail->send();

                      $mail1->addAddress($emailR);

                      $mail1->isHTML(true);
                      $mail1->Subject = "nuovo ordine";
                      $intro = "Devi preparare un ordine per le ".$_POST['ora']." contenente:" ;
                      $mail1->Body = $intro;
                      foreach($piatti as $piatto){
                        $sql = "SELECT nome FROM menu WHERE idRistorante = '".$idRist."' AND idPiatto = '".$piatto."'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $name = $row["nome"];
                        $sql = "SELECT quantita FROM ordine WHERE idOrdine = '".$idOrdine."' AND idPiatto = '".$piatto."'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $quant = $row["quantita"];
                        $mail1->Body .= " ".$quant." ".$name;
                      }
                      $mail1->send();

                      $mail2->addAddress($_SESSION['email']);

                      $mail2->isHTML(true);
                      $mail2->Subject = "nuovo ordine";
                      $mail2->Body = "Ordine effettuato correttamente, recati alle ore: ".$_POST['ora']." nell'".$_POST['aula']." per ricevere il tuo pranzo";

                      $mail2->send();



                    } catch (Exception $e) {
                      echo "Il messaggio non può essere inviato:", $mail->ErrorInfo;
                    }
                  }
                   ?>
                </tbody>
              </table>
            </form>
          </div>
          <p id="totale">Totale (<?php echo $count; if ($count == 1){ echo " articolo"; } else { echo " articoli"; }?>): <span id="sum"><?php echo $somma; ?>€</span></p>
        </div>
        <div class="col-sm-12 col-lg-3" id="right">
          <div name="luogo" id="luogo">
            <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
              <div class="form-group">
                <p>Dove vuoi ricevere il tuo ordine?</p>
                <select name="aula" class="form-control">
                  <option value="ingresso">Ingresso</option>
                  <option value="magna">Aula magna</option>
                  <option value="1.1">Aula 1.1</option>
                  <option value="1.2">Aula 1.2</option>
                  <option value="1.3">Aula 1.3</option>
                  <option value="1.4">Aula 1.4</option>
                  <option value="1.5">Aula 1.5</option>
                  <option value="1.6">Aula 1.6</option>
                  <option value="1.7">Aula 1.7</option>
                  <option value="1.8">Aula 1.8</option>
                  <option value="1.9">Aula 1.9</option>
                </select>
              </div>
              <div class="form-group">
                <p id="time">A che ora?</p>
                <select name="ora" class="form-control">
                  <option value="12:00">12:00</option>
                  <option value="12:30">12:30</option>
                  <option value="13:00">13:00</option>
                  <option value="13:30">13:30</option>
                  <option value="14:00">14:00</option>
                  <option value="14:30">14:30</option>
                  <option value="15:00">15:00</option>
                </select>
              </div>
              <hr>
              <?php
                if ($carta != ""){
               ?>

                <button type="submit"class="btn btn-primary" name="paga" id="paga">Ordina e paga</button>
              <?php
                }
               ?>
            </form>
            <span class="error"><?php echo $carta;?></span><br/>
              <button type="insert" class="btn btn-primary"name="inserisciCarta" data-toggle="modal" data-target="#cardForm" id="ins"><?php if($carta != ""){ echo "cambia carta"; } else {echo "Inserisci la carta di credito";} ?></button>
         </div>
        </div>
      </div>
      <?php
    } else {
      ?>
      <p>Non hai ancora aggiunto nessun piatto al tuo carrello, cosa aspetti?</p>
      <?php

    }
       ?>
    </div>

    <?php

      }
     ?>

    <!--modal -->
    <div class="modal" id="cardForm">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <div class="modal-header">
              <h4 class="modal-title">Inserisci la tua carta</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <p>Tipi di carta accettati:</p>
              <i class="fa fa-cc-mastercard" style="font-size:36px"></i>
              <i class="fa fa-cc-paypal" style="font-size:36px"></i>
              <i class="fa fa-cc-visa" style="font-size:36px"></i>
              <div class="form-group">
                <p>Intestatario:</p>
                <input type="text" class="form-control" name="intestatario" required>
              </div>
              <div class="form-group">
                <p>Numero carta:</p>
                <input type="tel" class="form-control"name="creditCardNumber" required>
              </div>
              <div class="form-group">
                <p>Cvv:</p>
                <input type="text" class="form-control"name="cvv" required>
              </div>
              <p>Data di scadenza:</p>
              <select name="mese">
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03" selected>03</option>
                <option value="04">04</option>
                <option value="05">05</option>
                <option value="06">06</option>
                <option value="07">07</option>
                <option value="08">08</option>
                <option value="09">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
              </select>
              <select name="anno">
                <option value="2019">2019</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
                <option value="2023">2023</option>
                <option value="2024">2024</option>
                <option value="2025">2025</option>
                <option value="2026">2026</option>
                <option value="2027">2027</option>
                <option value="2028">2028</option>
                <option value="2029">2029</option>
                <option value="2030">2030</option>
              </select>
            </div>
              <span class="error"><?php echo $cartaErr;?></span>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary mr-auto submit">Inserisci</button>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Chiudi</button>
            </div>
          </form>
        </div>
      </div>
    </div>


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


    <footer>
      <div class="container-fluid">
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

    <?php
      //Chiusura connessione con db
      $conn->close();
    }
    ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/navbar.js" type="text/javascript"></script>
    <script>
    $(document).ready(function(){

      <?php
      if($dbErr1 != ""){
      ?>
        $('#emailForm').modal('show');
      <?php
    } else {
      $email1 = "";
      $email2 = "";
    }
      if($dbErr2 != ""){
      ?>
        $('#passwordForm').modal('show');
      <?php
    } else {
      $password1 = "";
      $password2 = "";
    }

    if($cartaErr != ""){
    ?>
      $('#cardForm').modal('show');
    <?php
    }
    ?>
    });




     </script>

  </body>
</html>
