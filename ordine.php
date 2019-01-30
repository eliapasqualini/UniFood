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


    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h1><?php echo $_GET['ristoranteID']; ?></h1>
        </div>
      </div>
    </div>


    <div class="container">
      <br><h2> Menu </h2> <hr>



    <div class="card table-responsive">
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

  <input type="number" value="1" class="form-control">

    	        </td>
    	        <td>
            		<div class="price-wrap">
            			<var class="price">5.5&euro;</var><br>
            			<small class="text-muted">(&euro; per ogni pz.)</small>
            		</div> <!-- price-wrap .// -->
    	        </td>
    	        <td>
    	        <a title="" href="" class="btn btn-outline-success" data-toggle="tooltip" data-original-title="Save to Wishlist"> <i class="fas fa-plus-circle"></i></a>
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
                    <input type="number" value="1" class="form-control">
      	        </td>
      	        <td>
              		<div class="price-wrap">
              			<var class="price">5.5&euro;</var><br>
              			<small class="text-muted">(&euro; per ogni pz.)</small>
              		</div> <!-- price-wrap .// -->
      	        </td>
      	        <td>
      	        <a title="" href="" class="btn btn-outline-success" data-toggle="tooltip" data-original-title="Save to Wishlist"> <i class="fas fa-plus-circle"></i></a>
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
                      <input type="number" value="1" class="form-control">
                  </td>
                  <td>
                    <div class="price-wrap">
                      <var class="price">5.5&euro;</var><br>
                      <small class="text-muted">(&euro; per ogni pz.)</small>
                    </div> <!-- price-wrap .// -->
                  </td>
                  <td>
                  <button class="btn btn-outline-success text-center"> <i class="fas fa-plus-circle"></i></button>

                  </td>
                </tr>
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

  </body>
</html>
