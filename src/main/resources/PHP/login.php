<?php
    // Połączenie z bazą danych
    $address = "localhost:3306";
    $user = "root";
    $sqlpassword = "";
    $dbname = "workspace";
    $conn = mysqli_connect($address, $user, $sqlpassword, $dbname);

    // Sprawdzenie czy dane logowania zostały przesłane
    if(isset($_POST["user-id"]) && isset($_POST["password"])) {
      $user_id = $_POST["user-id"];
      $password = $_POST["password"];

      // Zabezpieczenie przed atakami typu SQL Injection
      $user_id = mysqli_real_escape_string($conn, $user_id);
      $password = mysqli_real_escape_string($conn, $password);

      // Zapytanie do bazy danych w celu pobrania hasła dla podanego ID użytkownika
      $sql = "SELECT haslo FROM pracownicy WHERE id=$user_id";
      $result = mysqli_query($conn, $sql);

      if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $stored_password = $row["haslo"];

        // Porównanie hasła wprowadzonego przez użytkownika z hasłem w bazie danych
        if(password_verify($password, $stored_password)) {
          session_start();
    $_SESSION['user_id'] = $user_id;
          header('Location: aktualnosci.php');
          exit();
        } else {
          echo "<p class='message'>Nieprawidłowe dane logowania. Proszę spróbować ponownie.</p>";
        }
      } else {
        echo "<p class='message'>Nieprawidłowe dane logowania. Proszę spróbować ponownie.</p>";
      }
    }

    // Zakończenie połączenia z bazą danych
    mysqli_close($conn);
    ?>


<!DOCTYPE html>
<html lang="pl">
  <head>
  <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style1.css">

    <title>Logowanie</title>
  </head>
  <body>
    <h1>Witamy w pracy</h1>
    <div class="form-container">
      <h2>Logowanie</h2>
      <form action="login.php" method="post">
        <label for="user-id">ID:</label>
        <input type="text" id="user-id" name="user-id">
        <br>
        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password">
        <br>
        <input type="submit" value="Zaloguj się">
      </form>
      <p>Nie masz konta? <a href="register.php">Rejestracja</a></p>
    </div>
  </body>
</html>
