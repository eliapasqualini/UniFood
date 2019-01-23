<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.2.0/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/contattaci.css">
  <title>UniFood</title>
</head>
<body>
  <?php
  // definisco mittente e destinatario della mail$nameErr = $emailErr = $surnameErr = $password1Err = $password2Err = $dbErr = "";
$nome = $email = $oggetto = $text = "";
$nomeErr = $emailErr = $textErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (empty($_POST["nome"])) {
  $nomeErr = "Il nome è obbligatorio";
} else {
  $nome = test_input($_POST["nome"]);
  // check if name only contains letters and whitespace
  if (!preg_match("/^[a-zA-Z ]*$/",$nome)) {
    $nomeErr = "Sono ammessi solo lettere o spazi";
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

if (empty($_POST["text"])) {
  $textErr = "Il testo è obbligatorio";
} else {
  $mail_corpo = test_input($_POST["text"]);
}

}
  $mail_destinatario = "mar.pazz14@gmail.com";

  // definisco il subject ed il body della mail
  $mail_oggetto = test_input($_POST["oggetto"]);

  // aggiusto un po' le intestazioni della mail
  // E' in questa sezione che deve essere definito il mittente (From)
  // ed altri eventuali valori come Cc, Bcc, ReplyTo e X-Mailer
  $mail_headers = "From: " .  $nome . " <" .  $email . ">\r\n";
  $mail_headers .= "Reply-To: " .  $email . "\r\n";
  $mail_headers .= "X-Mailer: PHP/" . phpversion();

  if (mail($mail_destinatario, $mail_oggetto, $mail_corpo, $mail_headers)){
    echo "Messaggio inviato con successo a " . $mail_destinatario;
  } else {
    echo "Errore. Nessun messaggio inviato.";
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


  <div class="container">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <h1>Inviaci una mail</h1>
      <div class="form-group">
        <label for="Nome">Nome:</label>
        <input type="text" class="form-control" name="nome" aria-describedby="Nome" placeholder="Inserisci nome">
        <span class="error"> <?php echo $nomeErr;?></span>
      </div>
      <div class="form-group">
        <label for="Email">Email:</label>
        <input type="email" class="form-control" name="email" aria-describedby="emailHelp" placeholder="Inserisci email">
        <span class="error"> <?php echo $emailErr;?></span>
      </div>
      <div class="form-group">
        <label for="text">Oggetto:</label>
        <input type="text" class="form-control" name="oggetto" aria-describedby="oggetto" placeholder="Inserisci oggetto">
      </div>
      <label for="text">Testo:</label>
      <div class="form-group">
        <textarea name="text" rows="8" cols="80"></textarea>
        <span class="error"> <?php echo $textErr;?></span>
      </div>
      <div class="col-4 offset-4 col-btn">
        <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Invia"><br/>
      </div>
    </form>
  </div>

  <!--Footer-->
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

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <script src="js/navbar.js" type="text/javascript"></script>

</body>
</html>
