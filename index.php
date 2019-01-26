<?php
// Start the session
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.2.0/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/index.css">
  <title>UniFood</title>
</head>

  <body>
  <?php
    $email = $password = $verifica ="";
    $emailErr = $passwordErr = $dbErr = "";
    $femail = $fpassword1 = $fname = $fsurname = $fpassword2 = "";
    $femailErr = $fpasswordErr = $fdbErr = $fsurnameErr = $fnameErr ="";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["email"])) {
        $emailErr = "L'email è richiesta";
      } else {
        $email = test_input($_POST["email"]);
        // check if e-mail address is well-formed
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $emailErr = "Formato email invalido";
        }
      }
      if (empty($_POST["password"])) {
        $passwordErr = "La password è obbligatoria";
      } else {
        $password = test_input($_POST["password"]);
        // check if name only contains letters and whitespace
      }

      if (!empty($_POST["fname"])){
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/",$fname)) {
          $fnameErr = "Sono ammessi solo lettere o spazi";
        } else {
          $fname = test_input($_POST["fname"]);
      }
        // check if name only contains letters and whitespace
        if (!preg_match("/^[a-zA-Z ]*$/",$fsurname)) {
          $fsurnameErr = "Sono ammessi solo lettere o spazi";
        } else {
          $fsurname = test_input($_POST["fsurname"]);
      }
      if(!empty($_POST["femail"])){
        $femail = test_input($_POST["femail"]);
      }
      if(!empty($_POST["fpassword1"])){
        $fpassword1 = test_input($_POST["fpassword1"]);
      }
      if(!empty($_POST["fpassword2"])){
        $fpassword2 = test_input($_POST["fpassword2"]);
      }

        if ($fpassword1 != $fpassword2){
          $fpasswordErr = "Le due password devono essere uguali";
      }
    }

    }
    include("php/config.php");
    $conn =new mysqli($servername, $username, $password, $db);
    if (!empty($_POST["email"])){
      $password = test_input($_POST["password"]);
    } else {
      $password = "";
    }
    //Check della connessione
    if ($conn->connect_errno) {
        echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
    } else {
        $sql = "SELECT email FROM account WHERE email = '$email'";
        $result = $conn->query($sql);
        $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

        $count = mysqli_num_rows($result);
        if ($count == 1 && !empty($_POST["email"]) && $emailErr == "" && $passwordErr == ""){
          $query_sql="SELECT * FROM account WHERE email = '$email'";
          $result = $conn->query($query_sql);
          $row = $result->fetch_assoc();
          $verifica = $row["password"];
          $id = $row["idAccount"];
          if(strcmp($password,$verifica) == 0){
            $query_sql="SELECT tipo FROM account WHERE email = '$email'";
            $result = $conn->query($query_sql);
            $row = $result->fetch_assoc();
            $count = mysqli_num_rows($result);
            foreach ($row as $tipo) {
              $next = $tipo;
            } if ($next == "ristorante"){
              $query_sql="SELECT * FROM ristorante WHERE idAccount = '$id'";
              $result = $conn->query($query_sql);
              $count = mysqli_num_rows($result);
              if ($count != 0){
                header("location: ristorante.php");
                $_SESSION["email"] = $email;
              } else {
                $dbErr = "La tua richiesta non è ancora stata approvata";
              }
            } else if($next == "fattorino"){
              header("location: fattorino.php");
              $_SESSION["email"] = $email;
            } else if($next == "amministratore"){
              header("location: amministratore.php");
              $_SESSION["email"] = $email;
            } else {
              header("location: clienti.php");
              $_SESSION["email"] = $email;
            }
          } else {
            $dbErr = "password errata";
          }
        } else if ($count == 0 && !empty($_POST["email"])){
          $dbErr = "L'indirizzo email non corrisponde a nessun account!";
      }
    if (!empty($_POST["fpassword1"])){
      $sql = "SELECT email FROM account WHERE email = '$femail'";
      $result = $conn->query($sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);

      $count = mysqli_num_rows($result);
      $disponibilita = $_POST["disponibilita"];
      if ($disponibilita == "si"){
        $dis = 1;
      } else {
        $dis = 0;
      }
      if ($count == 0 && !empty($_POST["femail"]) && $fnameErr == "" && $fsurnameErr == "" && $femailErr == "" && $fpasswordErr == ""){
        $query_sql="INSERT INTO `account` (`Nome`, `Cognome`, `Email`,`Password`, `tipo`, `disponibilita`) VALUES ('$fname', '$fsurname', '$femail', '$fpassword1', 'fattorino', '$dis')";
        $result = $conn->query($query_sql);
        header("location: fattorino.php");
        $_SESSION["email"] = $femail;
      } else if ($count == 1){
        $fdbErr = "Esiste già un account con questa email!";
      }
    }
      //Chiusura connessione con db
      $conn->close();
    }




    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
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

    <!-- Immagine responsive -->
    <div class="container-fluid container-image">
      <div class="row row-image" >
        <div class="col-12 col-image">
          <img src="image/hamburger2.png" alt="cibo" height="auto" width="100%" >
        </div>
      </div>
    </div>


    <!-- Titolo e bottoni -->
    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-title">
          <h3>UniFood</h3>
          <p>Accedi, ordina, gusta. </p>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6 col-btn">
          <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#loginForm">Accedi</button>
        </div>
        <div class="col-sm-6 col-btn">
          <a href="registration.php" class="registration_link">Registrati</a>
        </div>
      </div>

      <!-- Modal-->
      <div class="modal" id="loginForm">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Inserisci le credenziali</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <div class="form-group">
                  <label for="email">Email:</label>
                  <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                  <label for="pwd">Password:</label>
                  <input type="password" name="password" class="form-control" required>
                </div>
                <span class="error"><?php echo $dbErr;?></span>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary mr-auto submit">Accedi</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Chiudi</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="jumbotron jumbotron-fluid">
      <div class="container">
        <div class="row row-coll">
          <div class="col-md-4 offset-md-1 col-sm-6">
            <div class="card">
              <img class="card-img-top" src="image/logo-risto.png" alt="ristorante">
              <div class="card-body">
                <h5 class="card-title text-center">Vendi con noi</h5>
                <p class="card-text text-center">Possiedi un ristorante e desideri entrare a far parte del nostro servizio?</p>
                <a href="registrRist.php" class="btn btn-primary">Clicca qui</a>
              </div>
            </div>
          </div>
          <div class="col-md-4 offset-md-2 col-sm-6">
            <div class="card">
              <img class="card-img-top" src="image/logo-fattorino.png" alt="fattorino">
              <div class="card-body">
                <h5 class="card-title">Lavora con noi</h5>
                <p class="card-text">Se possiedi un qualunque mezzo di trasporto comincia a guadagnare con noi</p>
                <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#fattorinoForm">Clicca qui</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal-->
    <div class="modal" id="fattorinoForm">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Inserisci le credenziali</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
              <h1>Registrati</h1>
              <div class="form-group">
                <label for="Nome">Nome:</label>
                <input type="text" class="form-control" name="fname" value="<?php echo $fname;?>" placeholder="Inserisci nome" required>
                <span class="error"> <?php echo $fnameErr;?> </span>
              </div>
              <div class="form-group">
                <label for="Nome">Cognome:</label>
                <input type="text" class="form-control" name="fsurname" value="<?php echo $fsurname;?>" placeholder="Inserisci cognome"required>
                <span class="error"> <?php echo $fsurnameErr;?></span>
              </div>
              <div class="form-group">
                <label for="Email">Email:</label>
                <input type="email" class="form-control" name="femail" value="<?php echo $femail;?>" placeholder="Inserisci email" required>
                <span class="error"> <?php echo $femailErr;?></span>
            </div>
            <div class="form-group">
              <label for="Password">Password</label>
              <input type="password" name="fpassword1" class="form-control" placeholder="Password" required>
            </div>
            <div class="form-group">
              <label for="ConfirmPassword">Conferma password</label>
              <input type="password" class="form-control" name="fpassword2" placeholder=" Ripetere la password" required>
              <span class="error"> <?php echo $fpasswordErr;?></span>
            </div>
            <div class="form-group">
              <label for="disponibilità">Disponibilità immediata a consegnare?</label><br/>
              <input type="radio" name="disponibilita" value="si"checked> Si<br>
              <input type="radio" name="disponibilita" value="no"> No<br>
            </div>
            <div class="row">
              <div class="col-4 offset-4 col-btn">
                <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Registrati"><br/>
                <span class="error"> <?php echo $fdbErr;?></span>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
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
  <script>
    $(document).ready(function(){

      <?php
      if($dbErr != ""){
      ?>
        $('#loginForm').modal('show');
      <?php
      }
      ?>

      <?php
      if($fdbErr != "" || $fnameErr != "" || $fsurnameErr != "" || $femailErr != ""){
      ?>
        $('#fattorinoForm').modal('show');
      <?php
      }
      ?>

    });
  </script>
</body>
</html>
