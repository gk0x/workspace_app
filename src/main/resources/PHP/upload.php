<?php
session_start();
require_once('config.php');
$user_id = $_SESSION['user_id'];

// aktualizacja avatara użytkownika
if (isset($_FILES['awatar']) && $_FILES['awatar']['error'] == 0) {
  $avatar_file = $_FILES['awatar'];
  $avatar_path = 'uploads/' . basename($avatar_file['name']);

  if (move_uploaded_file($avatar_file['tmp_name'], $avatar_path)) {
    $user_id = $_SESSION['user_id'];
    $sql = "UPDATE pracownicy SET avatar_path = '$avatar_path' WHERE id = $user_id";
    mysqli_query($conn, $sql);
    header("Location: profil.php");
    exit();
  } else {
    echo "Wystąpił błąd podczas przesyłania pliku.";
  }
}
?>
