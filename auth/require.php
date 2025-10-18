<?php
require_once __DIR__ . '/../config/security.php';

function is_logged_in(): bool {
  return !empty($_SESSION['user']);
}

function require_login() {
  if (!is_logged_in()) {
    $next = urlencode($_SERVER['REQUEST_URI'] ?? '/pro-cleny/');
    redirect("/auth/login.php?next={$next}");
  }
}