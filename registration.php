<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.2.0/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/registration.css">
  <title>UniFood</title>
</head>
<body>

<?php
// define variables and set to empty values
$nameErr = $emailErr = $surnameErr = $password1Err = $password2Err = $dbErr = "";
$name = $email = $surname = $password1 = $password2 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Il nome è obbligatorio";
  } else {
    $name = test_input($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $nameErr = "Sono ammessi solo lettere o spazi";
    }
  }

  if (empty($_POST["surname"])) {
    $surnameErr = "Il cognome è obbligatorio";
  } else {
    $surname = test_input($_POST["surname"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$surname)) {
      $surnameErr = "Sono ammessi solo lettere o spazi";
    }
  }

  if (empty($_POST["email"])) {
    $emailErr = "L'email è richiesta";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Formato email invalido";
    }
  }

  if (empty($_POST["password1"])) {
    $password1Err = "La password è obbligatoria";
  } else {
    $password1 = test_input($_POST["password1"]);
    // check if name only contains letters and whitespace
  }
  if (empty($_POST["password2"])) {
    $password2Err = "La conferma della password è obbligatoria";
  } else {
    $password2 = test_input($_POST["password2"]);
    // check if name only contains letters and whitespace
    if ($password1 != $password2){
      $password2Err = "Le due password devono essere uguali";
    }
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

include("php/config.php");
$conn =new mysqli($servername, $username, $password, $db);
//Check della connessione
if ($conn->connect_errno) {
    echo "Failed to connect to MySQL: (" . $conn->connect_errno . ") " . $conn->connect_error;
} else {
    $sql = "SELECT email FROM account WHERE email = '$email'";
    $result = $conn->query($sql);

    $count = mysqli_num_rows($result);
    if ($count == 0 && !empty($_POST["email"]) && $nameErr == "" && $surnameErr == "" && $emailErr == "" && $password1Err == "" && $password2Err == ""){
      $query_sql="INSERT INTO `account` (`Nome`, `Cognome`, `Email`,`Password`, `tipo`) VALUES ('$name', '$surname', '$email', '$password1', 'cliente')";
      $result = $conn->query($query_sql);
      header("location: index.php");
    } else if ($count == 1){
      $dbErr = "Esiste già un account con questa email!";
  }
  //Chiusura connessione con db
  $conn->close();
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

<div class="container">

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <h1>Registrati</h1>
  <div class="form-group">
    <label for="Nome">Nome:</label>
    <input type="text" class="form-control" name="name" value="<?php echo $name;?>" placeholder="Inserisci nome">
    <span class="error"> <?php echo $nameErr;?></span>
  </div>
  <div class="form-group">
    <label for="Nome">Cognome:</label>
    <input type="text" class="form-control" name="surname" value="<?php echo $surname;?>" placeholder="Inserisci cognome">
    <span class="error"> <?php echo $surnameErr;?></span>
  </div>
  <div class="form-group">
    <label for="Email">Email:</label>
    <input type="email" class="form-control" name="email" value="<?php echo $email;?>" placeholder="Inserisci email">
  <span class="error"> <?php echo $emailErr;?></span>
</div>
<div class="form-group">
  <label for="Password">Password</label>
  <input type="password" name="password1" class="form-control" placeholder="Password">
    <span class="error"> <?php echo $password1Err;?></span>
</div>
<div class="form-group">
  <label for="ConfirmPassword">Conferma password</label>
  <input type="password" class="form-control" name="password2" placeholder=" Ripetere la password">
  <span class="error"> <?php echo $password2Err;?></span>
</div>
<div class="row">
  <div class="col-4 offset-4 col-btn">
    <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Registrati"><br/>
    <span class="error"> <?php echo $dbErr;?></span>
  </div>
</form>
</div>


<!--Footer-->
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/navbar.js" type="text/javascript"></script>

</body>
</html>
