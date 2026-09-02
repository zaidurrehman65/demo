<?php
// Include after auth.php. Set $pageTitle and $activeNav before including this file.
$activeNav = $activeNav ?? '';
function navClass($key, $active) { return $key === $active ? 'active' : ''; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> — AAZLI Industries</title>
<link rel="icon" href="../assets/aazli-logo.jpeg" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" href="admin.css" />
</head>
<body>
<div class="admin-shell">
  <aside class="admin-sidebar">
    <div class="flex items-center gap-3 px-5 py-6 border-b border-white/10">
      <img src="../assets/aazli-logo.jpeg" alt="AAZLI Industries" class="h-10 w-10 rounded-full object-cover" />
      <span class="font-display font-bold text-sm">AAZLI ADMIN</span>
    </div>
    <nav class="flex-1 py-4">
      <a href="index.php" class="<?= navClass('dashboard', $activeNav) ?>">Dashboard</a>
      <a href="products.php" class="<?= navClass('products', $activeNav) ?>">Products</a>
      <a href="inquiries.php" class="<?= navClass('inquiries', $activeNav) ?>">Inquiries &amp; Quotes</a>
    </nav>
    <div class="px-5 py-5 border-t border-white/10 flex flex-col gap-3">
      <a href="../index.html" target="_blank" class="text-xs text-white/50 hover:text-white">View live site &rarr;</a>
      <a href="logout.php" class="text-xs text-white/50 hover:text-white">Log out</a>
    </div>
  </aside>
  <main class="admin-main">
