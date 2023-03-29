<?php
$address = "localhost:3306";
$user = "root";
$sqlpassword = "";
$dbname = "workspace";
$conn = mysqli_connect($address, $user, $sqlpassword, $dbname);

if(isset($_POST["name"]) && isset($_POST["surname"]) && isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["password2"])) {
    $name = $_POST["name"];
    $surname = $_POST["surname"];
    $role_id = $_POST["role"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $password2 = $_POST["password2"];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if(!empty($name) && !empty($surname) && !empty($role_id) && !empty($email) && !empty($password) && !empty($password2)) {
        if (strlen($password) >= 8) {
            if($password == $password2) {
                $sql = "INSERT INTO pracownicy (imie, nazwisko, rola_id, email, haslo) VALUES ('$name', '$surname', '$role_id', '$email', '$hashed_password')";
                // uzytkownik dodany do bzy
                if (mysqli_query($conn, $sql)) {
                   $last_id = mysqli_insert_id($conn);
                    echo " <p class='message'>  Rejestracja przebiegła pomyślnie! Twoje id to: $last_id  <a href='login.php'>Zaloguj się</a> </p>";
                } else {
                    echo "Błąd podczas dodawania nowego użytkownika do bazy danych: " . $sql . "<br>" . mysqli_error($conn);
                }
            } else {
                echo "<p class='message'> Podane hasła nie są takie same. Proszę spróbować ponownie.";
            }
        } else {
            echo "<p class='message'> Hasło musi mieć conajmniej 8 znaków!";
        }
    } else {
        echo "<p class='message'>Proszę wypełnić wszystkie pola.";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pl">
  <head>
  <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style1.css">
    <title>Rejestracja</title>
  </head>
  <body>
    <h1>Rejestracja</h1>
    <form action="register.php" method="post">
      <label for="name">Imię:</label>
      <input type="text" id="name" name="name">
      <br>
      <label for="surname">Nazwisko:</label>
      <input type="text" id="surname" name="surname">
      <br>
      <label for="role">Rola:</label>
      <select id="role" name="role">
        <option value="1">programista</option>
        <option value="2">logistyk</option>
        <option value="3">konstruktor</option>
        <option value="5">stazysta</option>
        <option value="6">prezes</option>
        <option value="7">wiceprezes</option>
        <option value="8">pracownik produkcji</option>
        <option value="9">menadzer projektu</option>
        <option value="10">kierownik produkcji</option>
        <option value="11">kierownik Magazynu</option>
      </select>
    <br>
    <label for="email">Adres e-mail:</label>
    <input type="email" id="email" name="email">
    <br>
    <label for="password">Hasło:</label>
    <input type="password" id="password" name="password">
    <br>
    <label for="password2">Powtórz hasło:</label>
    <input type="password" id="password2" name="password2">
    <br>
    <input type="submit" value="Zarejestruj się">
  </form>
</body>
</html>
