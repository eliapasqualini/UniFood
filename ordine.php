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
              <a href="logout.php"><?php ?></a>
              <a href="" data-toggle="modal" data-target="#emailForm">Modifica email</a>
              <a href="" data-toggle="modal" data-target="#passwordForm">Modifica password</a>
              <a href="" data-toggle="modal" data-target="#deleteForm">Elimina Account</a>
            </div>
          </div>
        </li>
        <li class="header__menu__item"><a href="logout.php"><i class="fas fa-cart-arrow-down"></i></a></li>
      </ul>
    </header>


    <div class="container">
      <div class="row">
        <div class="col-4">
          <?php
      		if ($conn->connect_errno) {
      		?>
      			<p class="error">Connessione fallita: <?php echo $conn->connect_errno; ?> <?php echo $conn->connect_error; ?></p>
      		<?php
      		}
      		else{
            $query_sql="SELECT * FROM `ristorante` WHERE idRistorante = '" . $_GET['ristoranteID'] . "'";
            $result = $conn->query($query_sql);
            $row = $result->fetch_assoc();
            $id = $row["idAccount"];
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
            while($row = $result->fetch_assoc()) {
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
                <input type="number" value="0" class="form-control">
    	        </td>
    	        <td>
            		<div class="price-wrap">
            			<var class="price"><?php echo $row["prezzo"]; ?>&euro;</var><br>
            			<small class="text-muted">(&euro; per ogni pz.)</small>
            		</div> <!-- price-wrap .// -->
    	        </td>
    	        <td>
    	        <button class="btn btn-outline-success text-center"> <i class="fas fa-plus-circle"></i></button>
    	        </td>
            </tr>
            <?php
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
            </table>
          </div> <!-- card.// -->

        </div>
        <!--container end.//-->


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

  </body>
</html>
