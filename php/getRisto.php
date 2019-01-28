<?php
include("php/config.php");
$conn =new mysqli($servername, $username, $password, $db);
if ($conn->connect_errno) {
  echo "Connessione fallita";
} else {
    echo  <ul class="list-unstyled">
    echo    <li class="media my-4">
    $sql = "SELECT * FROM ristorante";
    $result = $conn->query($sql);
    while($row = $result->fetch_assoc()){
      if ($row['logo'] == null){
        echo "<img src="image/food.png" class="mr-5 image-risto" width="200px" height="auto" alt="ristorante">"
      }
        echo "<div class="media-body">"
        echo "<a href="ordine.php">"
        echo "<h3 class="mt-5 mb-1"><?php echo $row['nome']; ?></h3>"
        echo </a>
            $query_sql = "SELECT DISTINCT categoria FROM menu WHERE idRistorante = '".$row['idRistorante']."'";
            $ris = $conn->query($query_sql);
          echo "<p> Categorie:"
        while ($righe = $ris->fetch_assoc()){
          echo $righe['categorie'];
        }
      }
          echo </p>
        echo </li>
        echo </ul>
}
?>
