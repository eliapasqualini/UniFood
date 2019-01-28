<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php
include("config.php");
$conn =new mysqli($servername, $username, $password, $db);
if ($conn->connect_errno) {
  echo "Connessione fallita";
} else {
    $q = $_GET['q'];
    $sql="SELECT DISTINCT idRistorante FROM menu WHERE categoria = '".$q."'";
    $result = $conn->query($sql);
    echo "<ul class='list-unstyled'>";
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()){
        $query_sql="SELECT *  FROM ristorante WHERE idRistorante = '" . $row['idRistorante'] . "'";
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

        $sql = "SELECT DISTINCT categoria FROM menu WHERE idRistorante = '".$row['idRistorante']."'";
        $ris = $conn->query($sql);
        echo "<p> Categorie: <br>";
          while ($righe = $ris->fetch_assoc()){
            echo  $righe['categoria'];
          }
          echo "</p>";
          echo "</li>";
        }
      }
        echo "</ul>";
}
$conn->close();
?>
</body>
</html>
