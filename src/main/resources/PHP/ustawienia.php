<?php
session_start();
require_once('config.php');
$user_id = $_SESSION['user_id'];


?>

<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style1.css">
  </head>

<body>
    <nav class="menu">
      <ul>
        <li><a href="aktualnosci.php">Aktualności</a></li>
        <li><a href="projekty.php">Projekty</a></li>
        <li><a href="chat.php">Chat</a></li>
        <li><a href="profil.php">Profil</a></li>
        <li><a href="#" id="ustawienia-link">Ustawienia</a></li>
        <li class="right"><a href="logout.php">Wyloguj się</a></li>
      </ul>
    </nav>

<div class = "sections">
<section id="informacje">
<?php
      // pobranie imienia, nazwiska i roli użytkownika z bazy danych
      $user_id = $_SESSION['user_id'];
      $sql = "SELECT imie, nazwisko, rola.nazwa AS rola_nazwa, avatar_path,email FROM pracownicy INNER JOIN rola ON pracownicy.rola_id = rola.id WHERE pracownicy.id = $user_id";
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      $imie = $row['imie'];
      $nazwisko = $row['nazwisko'];
      $rola = $row['rola_nazwa'];
      $avatar_path = $row['avatar_path'];
      $email = $row['email'];
    ?>
    <details>
      <summary>Informacje <span>&#x25BC;</span></summary>
      <form>
        <label for="imie">Imię:</label>
        <input type="text" id="imie" name="imie" value="<?php echo $imie; ?>" readonly>
        <label for="nazwisko">Nazwisko:</label>
        <input type="text" id="nazwisko" name="nazwisko" value="<?php echo $nazwisko; ?>" readonly>
        <label for="id">ID:</label>
        <input type="text" id="id" name="id" value="<?php echo $user_id; ?>" readonly>
        <label for="rola">Rola:</label>
        <input type="text" id="rola" name="rola" value="<?php echo $rola; ?>" readonly>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>">
        <button type="submit" class = "buttons" >Zapisz</button>
      </form>
    </details>
  </section>
      
  <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // pobranie danych z formularza
  $stare_haslo = $_POST['stare-haslo'];
  $nowe_haslo = $_POST['nowe-haslo'];
  $powtorz_haslo = $_POST['powtorz-haslo'];

  // weryfikacja, czy stare hasło jest prawidłowe
  $user_id = $_SESSION['user_id'];
  $sql = "SELECT haslo FROM pracownicy WHERE id = $user_id";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $haslo_z_bazy = $row['haslo'];

  if (password_verify($stare_haslo, $haslo_z_bazy)) {
    // weryfikacja, czy nowe hasło spełnia wymagania
    if ($nowe_haslo === $powtorz_haslo && strlen($nowe_haslo) >= 8) {
      // zapisanie nowego hasła do bazy danych
      $nowe_haslo_hash = password_hash($nowe_haslo, PASSWORD_DEFAULT);
      $sql = "UPDATE pracownicy SET haslo = '$nowe_haslo_hash' WHERE id = $user_id";
      mysqli_query($conn, $sql);
      // wyświetlenie komunikatu o sukcesie
      echo "Hasło zostało zmienione.";
    } else {
      // wyświetlenie komunikatu o błędzie
      echo "Nowe hasło jest nieprawidłowe.";
    }
  } else {
    // wyświetlenie komunikatu o błędzie
    echo "Stare hasło jest nieprawidłowe.";
  }
}
?>
<section id="zmien-haslo">
  <details>
    <summary>Zmień hasło <span>&#x25BC;</span></summary>
    <form method="post">
      <label for="stare-haslo">Stare hasło:</label>
      <input type="password" id="stare-haslo" name="stare-haslo" required>
      <label for="nowe-haslo">Nowe hasło:</label>
      <input type="password" id="nowe-haslo" name="nowe-haslo" required>
      <label for="powtorz-haslo">Powtórz hasło:</label>
      <input type="password" id="powtorz-haslo" name="powtorz-haslo" required>
      <button type="submit">Zapisz</button>
    </form>
  </details>
</section>

      