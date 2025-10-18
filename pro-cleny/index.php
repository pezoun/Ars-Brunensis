<?php
require_once __DIR__ . '/../auth/require.php';
require_login();
$user = $_SESSION['user'] ?? 'uživatel';
?>
<!doctype html>
<html lang="cs">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pro členy | Ars Brunensis</title>
<link rel="stylesheet" href="/style.css">
</head>
<body>
<nav class="nav">…tvoje stávající navbar…</nav>

<main class="container">
  <h1>Pro členy</h1>
  <p>Ahoj, <b><?= htmlspecialchars($user, ENT_QUOTES) ?></b>.</p>
  <ul>
    <li><a href="/soubory/zpivame.pdf" target="_blank" rel="noopener">Materiály</a></li>
    <li><a href="/auth/logout.php">Odhlásit se</a></li>
  </ul>
</main>

<footer>…tvůj existující footer…</footer>
</body>
</html>