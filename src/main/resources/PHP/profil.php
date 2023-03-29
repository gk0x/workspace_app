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
  
    <div class="profile-container">
  <div id="user-info">
    <?php
      // pobranie imienia, nazwiska i roli użytkownika z bazy danych
      $user_id = $_SESSION['user_id'];
      $sql = "SELECT imie, nazwisko, rola.nazwa AS rola_nazwa, avatar_path FROM pracownicy INNER JOIN rola ON pracownicy.rola_id = rola.id WHERE pracownicy.id = $user_id";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      $imie = $row['imie'];
      $nazwisko = $row['nazwisko'];
      $rola = $row['rola_nazwa'];
      $avatar_path = $row['avatar_path'];
    ?>
    <h2 class="name"><?php echo $imie . " " . $nazwisko; ?></h2>
    <p class="role"><?php echo $rola; ?></p>
    <?php
      // wyświetlenie awatara użytkownika, jeśli jest dostępny
      if (!empty($avatar_path)) {
        echo '<img src="' . $avatar_path . '" alt="Avatar" class = "awatar">';
      }
    ?>
    <form method="post" action="upload.php" enctype="multipart/form-data">
  <label for="awatar">Wybierz plik z avatarem:</label>
  <input type="file" id="awatar" name="awatar" accept="image/*">
  <input type="submit" value="Zapisz awatar">
</form>



  </div>
  <section id="czas-pracy">
    <details>
      <summary>Czas pracy <span>&#x25BC;</span></summary>
      <p>Tutaj będą informacje o czasie pracy.</p>
    </details>
  </section>
</div>
