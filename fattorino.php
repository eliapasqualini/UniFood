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
  <link rel="stylesheet" type="text/css" href="css/fattorino.css">
  <title>UniFood</title>
</head>
<body>
  <?php

  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  include("php/config.php");
  $conn =new mysqli($servername, $username, $password, $db);
  $stato = NULL;
  $id = NULL;

  $dbErr1 = "";
  $dbErr2 = "";

  $dispErr = "";
  if ($conn->connect_errno) {
  } else{

    $query_sql="SELECT * FROM `account` WHERE email = '".$_SESSION["email"]."'";
    $result = $conn->query($query_sql);
    $row = $result->fetch_assoc();
  if ($result->num_rows > 0) {
    $stato = $row["disponibilita"];
    $id = $row["idAccount"];

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
    if(isset($_POST['delete'])){
      $sql = "DELETE FROM consegna WHERE idAccount = '".$id."'";
      $result = $conn->query($sql);
      $sql = "DELETE FROM account WHERE idAccount = '".$id."'";
      $result = $conn->query($sql);
      header("location: index.php");
    }

    if(isset($_POST['change'])){
      if ($stato == 0){
      $sql = "UPDATE `account` SET `disponibilita`= '1' WHERE idAccount = '".$id."'";
      $result = $conn->query($sql);
      $stato = 1;
      } else {
        $sql = "UPDATE `account` SET `disponibilita`= '0' WHERE idAccount = '".$id."'";
        $result = $conn->query($sql);
        $stato = 0;
      }
    }
  ?>
  <!--header-->
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

  <!--Title-->
  <div class="container">
    <div class="row">
        <h1><?php echo $row["nome"]; ?> <?php echo $row["cognome"]; ?></h1>
    </div>
  </div>


  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-3 col-sm-12 col-disp" id="disponibilità">
        <h3>Modifica disponibilità:</h3>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <div class="form-group">
            <p><?php if ($stato == 1){ ?> Risulti<?php } else if ($stato == 0){ ?>Risulti non<?php } ?> disponibile a consegnare.</p>
          </div>
          <button type="submit" name="change" class="btn btn-primary mr-auto">Cambia</button>
        </form>
      </div>

      <div class="col-lg-7 col-sm-12 col-table" id="tabella">
        <div class="table-responsive">
          <h3>Consegne effettuate:</h3>
          <?php
          $sql = "SELECT * FROM `consegna` WHERE idAccount = ".$id;
          $result = $conn->query($sql);
          if ($result->num_rows > 0){
            ?>
            <table class="table table-hover table-bordered">
              <thead class="thead-dark">
              <tr>
                <th scope="col">Data</th>
                <th scope="col">Ora</th>
                <th scope="col">Ristorante</th>
                <th scope="col">Aula</th>
              </tr>
              </thead>
              <tbody>
            <?php
            while($row = $result->fetch_assoc()) {
                $query_sql = "SELECT idRistorante FROM `ordine` WHERE idOrdine = '".$row['idOrdine']."'";
                $res = $conn->query($query_sql);
                if ($res->num_rows > 0) {
                  $idRist = $res->fetch_assoc();
                  $query_sql = "SELECT nome FROM `ristorante` WHERE idRistorante = '".$idRist['idRistorante']."'";
                  $ris = $conn->query($query_sql);
                  if ($ris->num_rows > 0) {
                    $nome = $ris->fetch_assoc();
        						?>
        						<tr>
        							<td><?php echo $row["data"]; ?></td>
        							<td><?php echo $row["orario"]; ?></td>
        							<td><?php echo $nome["nome"]; ?></td>
                      <td><?php echo $row["aula"]; ?></td>
        						</tr>
        						<?php
                  }
                }
              }
            } else {
                    ?>
                    <p>Nessuna consegna effettuata</p>
                    <?php
              }
            }
      		?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-lg-2 col-sm-12 col-btn">
        <h3>Modifica account:</h3>
        <div class="buttons">
          <button type="button" class="btn btn-primary btn-md" data-toggle="modal" id="button" data-target="#emailForm">Email</button>
          <button type="button" class="btn btn-primary btn-md" data-toggle="modal" id="button" data-target="#passwordForm">Password</button>
          <button type="button" class="btn btn-primary btn-md" data-toggle="modal" id="button" data-target="#deleteForm">Elimina account</button></span>
      </div>
    </div>
  </div>

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

}
    $conn->close();
  ?>

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
      ?>

      <?php
      if($dbErr2 != ""){
      ?>
        $('#passwordForm').modal('show');
      <?php
    } else {
      $password1 = "";
      $password2 = "";
    }
      ?>

    });
  </script>
  </body>
  </html>
