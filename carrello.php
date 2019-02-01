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
        <li class="header__menu__item"><a href="logout.php"><i class="fas fa-cart-arrow-down"></i></a></li>
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
            <table class="table">
              <tr>
                <th scope="col">Piatto</th>
                <th scope="col">Prezzo</th>
                <th scope="col">Quantità</th>
              </tr>
              <tbody>
                <tr>
                  <?php
                    while ($row = $result->fetch_assoc()){
                      $idOrdine = $row['idOrdine'];
                      $query_sql = "SELECT * FROM menu WHERE idPiatto = '".$row['idPiatto']."' AND idRistorante = '".$row['idRistorante']."'";
                      $res = $conn->query($query_sql);
                      while ($ris = $res->fetch_assoc()){
                        $somma = $somma + ($ris['prezzo']*$row['quantita']);
                   ?>
                  <td><?php echo $ris['nome'] ?></td>
                  <td><?php echo $ris['prezzo'] ?>€</td>
                  <td><?php echo $row['quantita'] ?></td>
                </tr>
                <?php
                  }
                }

                if(isset($_POST['paga'])){
                  $sql = "SELECT * FROM account WHERE tipo = 'fattorino' AND disponibilita = '1' ORDER BY RAND()";
                  $result = $conn->query($sql);
                  $row = $result->fetch_assoc();
                  $fattorino = $row['idAccount'];
                  $data = date ("d/m/Y");
                  $sql = "INSERT INTO consegna (`idOrdine`, `idAccount`, `data`, `orario`, `aula`) VALUES ('".$idOrdine."', '".$fattorino."','".$data."', '".$_POST['ora']."', '".$_POST['aula']."')";
                  $result = $conn->query($sql);
                  $sql = "UPDATE ordine SET stato = '1' WHERE idOrdine = '".$idOrdine."'";
                  $result = $conn->query($sql);
                  header("Location: cliente.php");
                }
                 ?>
              </tbody>
            </table>
          </div>
          <p id="totale">Totale (<?php echo $count; if ($count == 1){ echo " articolo"; } else { echo " articoli"; }?>): <span id="sum"><?php echo $somma; ?>€</span></p>
        </div>
        <div class="col-sm-12 col-lg-3" id="right">
          <div name="luogo" id="luogo">
            <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
              <p>Dove vuoi ricevere il tuo ordine?</p>
              <select name="aula">
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
              <p id="time">A che ora?</p>
              <select name="ora">
                <option value="12:00">12:00</option>
                <option value="12:30">12:30</option>
                <option value="13:00">13:00</option>
                <option value="13:30">13:30</option>
                <option value="14:00">14:00</option>
                <option value="14:30">14:30</option>
                <option value="15:00">15:00</option>
              </select>
              <hr>
              <?php
                if ($carta != ""){
               ?>

                <button type="submit"class="btn btn-primary" name="paga" id="paga">Ordina e paga</button>
              <?php
                }
               ?>
            </form>
            <span class="error"><?php echo $carta;?></span>
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
              <p>Intestatario:</p>
              <input type="text" name="intestatario" required>
              <p>Numero carta:</p>
              <input type="tel" name="creditCardNumber" required>
              <p>Cvv:</p>
              <input type="text" name="cvv" required>
              <p>Data di scadenza:</p>
              <select name="mese">
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
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
      //Chiusura connessione con db
      $conn->close();
    }
    ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/navbar.js" type="text/javascript"></script>
    <script>

     <?php
     if($cartaErr != ""){
     ?>
       $('#cardForm').modal('show');
     <?php
     }
     ?>

     </script>

  </body>
</html>
