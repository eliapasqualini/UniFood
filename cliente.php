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
  <link rel="stylesheet" type="text/css" href="css/cliente.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <title>UniFood</title>
</head>

  <body>
    <?php
    include("php/config.php");
    $conn =new mysqli($servername, $username, $password, $db);
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



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/navbar.js" type="text/javascript"></script>
  </body>
</html>
