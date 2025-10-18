<?php
require_once __DIR__ . '/../config/security.php';

$errors = [];
// Rate-limit v session (jednoduché, pro hosting stačí)
$_SESSION['fail_count'] = $_SESSION['fail_count'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = trim($_POST['username'] ?? '');
  $pass = $_POST['password'] ?? '';
  $tok  = $_POST['csrf'] ?? '';

  if (!csrf_check($tok)) {
    $errors[] = 'Neplatný CSRF token, zkuste to znovu.';
  } elseif ($_SESSION['fail_count'] > 10) {
    $errors[] = 'Příliš mnoho pokusů, zkuste to později.';
  } else {
    $users = require __DIR__ . '/../config/users.php';
    if (isset($users[$user]) && password_verify($pass, $users[$user])) {
      // úspěch
      session_regenerate_id(true);
      $_SESSION['user'] = $user;
      $_SESSION['fail_count'] = 0;

      $next = $_GET['next'] ?? '/pro-cleny/';
      if (!preg_match('~^/~', $next)) $next = '/pro-cleny/'; // ochrana před open redirect
      redirect($next);
    } else {
      $_SESSION['fail_count']++;
      $errors[] = 'Neplatné přihlašovací údaje.';
    }
  }
}
$csrf = csrf_token();
$next = htmlspecialchars($_GET['next'] ?? '/pro-cleny/', ENT_QUOTES);
?>
<!doctype html>
<html lang="cs">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Přihlášení | Ars Brunensis</title>
<link rel="stylesheet" href="/style.css">
<style>
.login-wrap{max-width:420px;margin:10vh auto;padding:22px;border:1px solid #e8e8e8;border-radius:14px;background:#fff;box-shadow:0 12px 28px rgba(0,0,0,.06)}
.login-wrap h1{font-family:"Playfair Display",serif;font-weight:800;margin:0 0 12px}
.form-row{display:grid;gap:8px;margin:10px 0}
input[type=text],input[type=password]{border:1px solid #dcdcdc;border-radius:10px;padding:12px;font-size:16px}
.btn{display:inline-flex;align-items:center;justify-content:center;padding:10px 18px;border:2px solid #111;border-radius:12px;background:#fff;font-weight:700}
.error{color:#b00020;margin-top:8px}
</style>
</head>
<body>
<div class="login-wrap">
  <h1>Přihlášení</h1>
  <?php if ($errors): ?>
    <div class="error"><?= htmlspecialchars(implode(' ', $errors), ENT_QUOTES) ?></div>
  <?php endif; ?>
  <form method="post" action="/auth/login.php?next=<?= $next ?>">
    <input type="hidden" name="csrf" value="<?= $csrf ?>">
    <div class="form-row">
      <label for="u">Uživatelské jméno</label>
      <input id="u" name="username" type="text" autocomplete="username" required>
    </div>
    <div class="form-row">
      <label for="p">Heslo</label>
      <input id="p" name="password" type="password" autocomplete="current-password" required>
    </div>
    <button class="btn" type="submit">Přihlásit</button>
  </form>
</div>
</body>
</html>