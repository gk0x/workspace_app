
<?php
// Połączenie z bazą danych
session_start();
require_once('config.php');

$user_id = $_SESSION['user_id'];
$pdo = new PDO('mysql:host=localhost:3306;dbname=workspace', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
?>

<!-- menu nawigacyjne -->
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
        <li><a href="profil.php" >Profil</a></li>
        <li><a href="ustawienia.php">Ustawienia</a></li>
        <li class="right"><a href="logout.php">Wyloguj się</a></li>

      </ul>
    </nav>

<!-- Formularz do dodawania posta -->
    <form method="post">
    <label for="tytul">Tytuł:</label>
    <input type="text" id="tytul" name="tytul" required>
    <label for="tresc">Treść:</label>
    <textarea id="tresc" name="tresc"></textarea>
    <button type="submit">Dodaj post</button>
</form>

<?php
// Obsługa formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['tytul']) && isset($_POST['tresc']) && isset($_SESSION['user_id'])) {
    // Pobranie danych z formularza
    $tytul = $_POST['tytul'];
    $tresc = $_POST['tresc'];
    $autor_id = $_SESSION['user_id'];
    }
  
// Dodanie posta do bazy danych
if (!empty($tytul) && !empty($tresc) && !empty($autor_id)) {
$stmt = $pdo->prepare("INSERT INTO posty (tytul, tresc, autor_id) VALUES(:tytul,:tresc,:autor_id)" );
$stmt->execute(['tytul' => $tytul, 'tresc'=>$tresc, 'autor_id'=>$autor_id]);
}
}

// Pobranie postów z bazy danych
$stmt = $pdo->query("SELECT a.id, a.tytul, a.tresc, a.data_publikacji, a.autor_id, p.imie, p.nazwisko, r.nazwa AS rola FROM posty a JOIN pracownicy p ON a.autor_id = p.id JOIN rola r ON p.rola_id = r.id ORDER BY a.data_publikacji DESC");
$posty = $stmt->fetchAll();


    // Wyświetlenie postów
foreach ($posty as $post) {
    echo '<div class="post">';
    echo '<h2>' . htmlspecialchars($post['tytul']) . '</h2>';
    echo '<p class="data">' . date('d.m.Y H:i', strtotime($post['data_publikacji'])) . '</p>';
    echo '<div class="autor">';
    echo '<h3>' .'Autor: ' . htmlspecialchars($post['imie']) . ' ' . htmlspecialchars($post['nazwisko']) .  '</h3>';
    echo '<h4>' . htmlspecialchars($post['rola']) . '<h4>';
    echo '</div>';
    echo '<p class="tresc">' . htmlspecialchars($post['tresc']) . '</p>';
?>
    <!-- formularz dodania komentarza --> 
    <form method="post">
        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
        <label for="tresc_komentarza">Komentarz:</label>
        <textarea id="tresc_komentarza" name="tresc_komentarza" required></textarea>
        <button type="submit" >Dodaj komentarz</button>
    </form>

    <?php
//Obsługa danych z formularza i dodawanie komentarza do bazy danych:
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id']) && isset($_POST['tresc_komentarza'])&& !isset($_SESSION['form_sent'])) {
  $_SESSION['form_sent'] = true; // ustawienie zmiennej sesyjnej, aby oznaczyć formularz jako wysłany
  $post_id = $_POST['post_id'];
  $tresc_komentarza = $_POST['tresc_komentarza'];
  $autor_id = $_SESSION['user_id'];

  $stmt = $pdo->prepare("INSERT INTO komentarze (tresc, autor_id, post_id) VALUES (:tresc, :autor_id, :post_id)");
  $stmt->execute(['tresc' => $tresc_komentarza, 'autor_id' => $autor_id, 'post_id' => $post_id]);

  // Przekierowanie do strony aktualności po dodaniu komentarza
  header('Location: aktualnosci.php');
  exit();
}

//Usuwanie zmiennej sesyjnej, gdy użytkownik wyświetla ponownie formularz dodawania komentarza
if (isset($_SESSION['form_sent'])) {
  unset($_SESSION['form_sent']);
}

//Wyświetlanie komentarzy dla danego posta:
$stmt = $pdo->prepare("SELECT k.tresc, k.data_publikacji, p.imie, p.nazwisko, r.nazwa AS rola FROM komentarze k JOIN pracownicy p ON k.autor_id = p.id JOIN rola r ON p.rola_id = r.id WHERE k.post_id = :post_id ORDER BY k.data_publikacji ASC");
$stmt->execute(['post_id' => $post['id']]);
$komentarze = $stmt->fetchAll();
foreach ($komentarze as $komentarz) {
  echo '<div class="komentarz">';
  echo '<p>' . $komentarz['tresc'] . '</p>';
  echo '<p>Autor: ' . $komentarz['imie'] . ' ' . $komentarz['nazwisko'] . ', ' . $komentarz['rola'] . '</p>';
  echo '<p>Data publikacji: ' . $komentarz['data_publikacji'] . '</p>';
  echo '</div>';
}
?>

<?php
      // Dodanie przycisku usuwania dla autora posta
      if ($post['autor_id'] == $_SESSION['user_id']) {
        echo '<form method="post">';
        echo '<input type="hidden" name="post_id" value="' . $post['id'] . '">';
        echo '<button type="submit">Usuń post</button>';
        echo '</form>';
    }
    echo '</div>';
}

//usuwanie posta z bazy danych
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = $_POST['post_id'];
 // Usuń komentarze dla danego posta
    $stmt = $pdo->prepare("DELETE FROM komentarze WHERE post_id = :post_id");
    $stmt->execute(['post_id' => $post_id]);
    $stmt = $pdo->prepare("DELETE FROM posty WHERE id = :post_id AND autor_id = :user_id");
    $stmt->execute(['post_id' => $post_id, 'user_id' => $user_id]);
      // Przekierowanie do strony aktualności po usunięciu posta
      header('Location: aktualnosci.php');
      exit();
  }
?>



