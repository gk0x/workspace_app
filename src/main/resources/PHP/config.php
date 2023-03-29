<?php
    // Połączenie z bazą danych
    $address = "localhost:3306";
    $user = "root";
    $sqlpassword = "";
    $dbname = "workspace";
    $conn = mysqli_connect($address, $user, $sqlpassword, $dbname);

  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
?>