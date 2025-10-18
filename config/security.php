<?php
// Bezpečná session: nastav co nejdřív!
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Strict'); // pro cross-origin můžeš dát Lax
// Na HTTPS hostingu zapni secure cookie:
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
  ini_set('session.cookie_secure', 1);
}
session_name('ars_sess');
session_start();

// CSRF helpery
function csrf_token(): string {
  if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf'];
}
function csrf_check(string $token): bool {
  return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

// Opatrné přesměrování
function redirect(string $path) {
  header('Location: ' . $path, true, 302);
  exit;
}