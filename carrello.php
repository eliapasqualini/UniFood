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
            <button class="dropbtn">Account
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

    <div class="container-fluid">
      <h1>Carrello</h1>
      <p>Ci sono fattorini disponibili alla consegna</p>
      <div class="row">
        <div class="col-sm-12 col-lg-8">
          <div class="table-responsive">
            <table class="table">
              <tr>
                <th scope="col">Prodotto</th>
                <th scope="col">Prezzo</th>
                <th scope="col">quntità</th>
              </tr>
              <tbody>
                <tr>
                  <td>prod1</td>
                  <td>1€</td>
                  <td>1</td>
                </tr>
              </tbody>
            </table>
          </div>
          <p id="totale">Totale: 1€</p>
        </div>
        <div class="col-sm-12 col-lg-4">
          <div name="luogo" id="luogo">
            <p>In che aula vuoi ricevere il tuo ordine?</p>
            <select name="aula">
              <option value="1.1">1.1</option>
              <option value="1.2">1.2</option>
              <option value="1.3">1.3</option>
              <option value="1.4">1.4</option>
              <option value="1.5">1.5</option>
              <option value="1.6">1.6</option>
              <option value="1.7">1.7</option>
              <option value="1.8">1.8</option>
              <option value="1.9">1.9</option>
            </select>
            <p>A che ora?</p>
            <select name="ora">
              <option value="12:00">12:00</option>
              <option value="12:30">12:30</option>
              <option value="13:00">13:00</option>
              <option value="13:30">13:30</option>
              <option value="14:00">14:00</option>
              <option value="14:30">14:30</option>
              <option value="15:00">15:00</option>
            </select>
          </div>
          <div name="pagamento">
            <p>Carta di credito o debito:</p>
            <img src="image/MasterCard_Logo.png" alt="mastercard" width="40px" height="25px">
            <img src="image/Visa_logo.png" alt="visa" width="40px" height="25px">
            <img src="image/cartasi_Logo.jpg" alt="cartasi" width="40px" height="25px">
            <p>Intestatario:</p>
            <input type="text" name="intestatatio" required>
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
          <button type="submit"class="btn btn-primary" name="paga" id="paga">Ordina e paga</button>
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
    ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/navbar.js" type="text/javascript"></script>

  </body>
</html>
