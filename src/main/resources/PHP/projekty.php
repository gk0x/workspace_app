<?php
session_start();
require_once('config.php');

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="pl">
  <head>
  <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
  </head>

<body>
    <nav class="menu">
      <ul>
        <li><a href="aktualnosci.php">Aktualności</a></li>
        <li><a href="projekty.php">Projekty</a></li>
        <li><a href="chat.php">Chat</a></li>
        <li><a href="#" id="profil-link">Profil</a></li>
        <li><a href="ustawienia.php">Ustawienia</a></li>
        <li class="right"><a href="logout.php">Wyloguj się</a></li>

      </ul>
    </nav>
    <?php
// pobranie projektów z bazy danych
$sql = "SELECT * FROM projekty ORDER BY data_rozpoczecia DESC";
$result = mysqli_query($conn, $sql);

// wyświetlenie projektów w formie tabeli
echo "<table>";
echo "<tr><th>Nazwa projektu</th><th>Data rozpoczęcia</th><th>Deadline</th><th>Status</th></tr>";
while($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td><a href='projekt.php?id=" . $row['id'] . "'>" . $row['nazwa'] . "</a></td>";
    echo "<td>" . $row['data_rozpoczecia'] . "</td>";
    echo "<td>" . $row['deadline'] . "</td>";
    echo "<td>" . $row['status'] . "</td>";
    echo "</tr>";
}
echo "</table>";


?>

