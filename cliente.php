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
    $dbErr1 = $dbErr2 = "";

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

    <div class ="container">
      <!--TITLE-->
      <div class="row">
        <div class="col-sm-12">
          <?php
      		if ($conn->connect_errno) {
      		?>
      			<p class="error">Connessione fallita: <?php echo $conn->connect_errno; ?> <?php echo $conn->connect_error; ?></p>
      		<?php
      		}
      		else{
            $query_sql="SELECT * FROM `account` WHERE email = '" . $_SESSION['email'] . "'";
            $result = $conn->query($query_sql);
            $row = $result->fetch_assoc();
            $id = $row["idAccount"];
            if(isset($_POST['delete'])){
              $sql = "DELETE FROM ordine WHERE idAccount = '".$id."'";
              $result = $conn->query($sql);
              $sql = "DELETE FROM account WHERE idAccount = '".$id."'";
              $result = $conn->query($sql);
              header("location: index.php");
            }
          ?>
          <h1>Benvenuto, <?php echo $row['nome']; ?>  <?php echo $row['cognome']; ?></h1>
          <p>Comincia a compilare il tuo ordine!</p>
        </div>
      </div>


      <div class="row">
        <div class="col-md-6">
          <p>Scegli il ristorante che fa per te!</p>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="sort" class="control-label"> Filtra per categorie:</label>
            <select name="categoria" onchange="showRisto(this.value)">
              <option value="tutte">Seleziona categoria</option>
              <?php
                $sql = "SELECT * FROM categoria";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()){
                echo "<option value='".$row['categoria']."'>".$row['categoria']."</option>";
                }
                ?>
            </select>
          </div>
        </div>
      </div>



      <div id="categoryHint">
        <?php
        $sql="SELECT DISTINCT idRistorante FROM menu";
        $result = $conn->query($sql);
        echo "<ul class='list-unstyled'>";
        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          foreach ($row as $rist){
            $query_sql="SELECT *  FROM ristorante WHERE idRistorante = '" . $rist . "'";
            $result = $conn->query($query_sql);
            $row2 = $result->fetch_assoc();
            echo "<li class='media my-4'>";
            if ($row2['logo'] == null){
              echo "<img src='image/food.png' class='mr-5 image-risto' width='200px' height='auto' alt='ristorante'>";
            }
            else{
              echo '<img width="200px" height="auto" class="mr-5 image-risto" alt="ristorante"src="data:image/jpeg;base64,'.base64_encode( $row2['logo'] ).'"/>';
            }
            echo "<div class='media-body'>";
            echo "<a href='ordine.php'>";
            echo "<h3 class='mt-5 mb-1'> '" . $row2['nome'] . "' </h3>";
            echo "</a>";

            $sql = "SELECT DISTINCT categoria FROM menu WHERE idRistorante = '".$rist."'";
            $ris = $conn->query($sql);
            echo "<p> Categorie: <br>";
              while ($righe = $ris->fetch_assoc()){
                echo  $righe['categoria'];

              }
              echo "</p>";
              echo "</div>";
              echo "</li>";
            }
          }
            echo "</ul>";
         ?>
<!--       <ul class="list-unstyled">
          <li class="media my-4">
            <img src="image/food.png" class="mr-5 image-risto" width="200px" height="auto" alt="ristorante">
            <div class="media-body">
              <a href="ordine.php">
                <h3 class="mt-5 mb-1">Nome ristorante</h3>
              </a>
              <p>Categoria : pizza, bevande ecc.</p>
            </div>

          </li>
          <li class="media my-4">
            <img src="image/food.png" class="mr-5 image-risto" width="200px" height="auto" alt="ristorante">
            <div class="media-body">
              <a href="ordine.php">
                <h3 class="mt-5 mb-1">Nome ristorante</h3>
              </a>
              <p>Categoria : pizza, bevande ecc.</p>
            </div>
          </li>
          <li class="media my-4">
            <img src="image/food.png" class="mr-5 image-risto" width="200px" height="auto" alt="ristorante">
            <div class="media-body">
              <a href="ordine.php">
                <h3 class="mt-5 mb-1">Nome ristorante</h3>
              </a>
              <p>Categoria : pizza, bevande ecc.</p>
            </div>
          </li>
        </ul>-->
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
        ?>
      });
    </script>
    <script>
      function showRisto(str) {
        if (str=="") {
          return;
        }
        if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp=new XMLHttpRequest();
        } else { // code for IE6, IE5
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function() {
          if (this.readyState==4 && this.status==200) {
            document.getElementById("categoryHint").innerHTML=this.responseText;
          }
        }
        xmlhttp.open("GET","php/getRisto.php?q="+str,true);
        xmlhttp.send();
      }
    </script>
  </body>
</html>
