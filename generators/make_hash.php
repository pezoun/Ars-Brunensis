<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $p = $_POST['password'] ?? '';
  if (strlen($p) < 10) { echo "Heslo alespoň 10 znaků"; exit; }
  echo "<pre>" . password_hash($p, PASSWORD_BCRYPT, ['cost'=>10]) . "</pre>";
  exit;
}
?>
<!doctype html><meta charset="utf-8">
<form method="post">
  <label>Heslo: <input type="text" name="password"></label>
  <button>Vygenerovat bcrypt</button>
</form>