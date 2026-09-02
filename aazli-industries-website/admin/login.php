<?php
session_start();
require __DIR__ . '/../php/config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['password'] ?? '') === ADMIN_PASSWORD) {
        $_SESSION['aazli_admin'] = true;
        header('Location: index.php');
        exit;
    }
    $error = 'Incorrect password. Please try again.';
}
if (!empty($_SESSION['aazli_admin'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Admin Login — AAZLI Industries</title>
<link rel="icon" href="../assets/aazli-logo.jpeg" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" href="admin.css" />
</head>
<body class="min-h-screen flex items-center justify-center bg-[var(--ink)] px-5">
  <div class="w-full max-w-sm">
    <div class="flex items-center gap-3 justify-center mb-8">
      <img src="../assets/aazli-logo.jpeg" alt="AAZLI Industries" class="h-14 w-14 rounded-full object-cover" />
      <span class="font-display font-bold text-white text-lg">AAZLI ADMIN</span>
    </div>
    <form method="post" class="bg-white p-8 cut-card">
      <h1 class="font-display text-xl font-bold mb-1">Admin Login</h1>
      <p class="text-sm text-[var(--slate)] mb-6">Manage products and view inquiries.</p>
      <?php if ($error): ?>
        <div class="mb-4 p-3 border border-red-200 bg-red-50 text-red-700 text-sm"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
      <label class="text-sm font-semibold block mb-1.5">Password</label>
      <input type="password" name="password" required autofocus class="w-full border border-[var(--line)] px-4 py-2.5 text-sm mb-5" />
      <button type="submit" class="btn-grad w-full justify-center">Log In</button>
    </form>
    <p class="text-center text-white/40 text-xs mt-6">Default password is set in php/config.php — change it before going live.</p>
  </div>
</body>
</html>
