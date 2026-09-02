<?php
require __DIR__ . '/auth.php';

$productCount   = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$inquiryCount   = $pdo->query("SELECT COUNT(*) FROM inquiries")->fetchColumn();
$newCount       = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'new'")->fetchColumn();
$recent         = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Dashboard';
$activeNav = 'dashboard';
include __DIR__ . '/_layout_top.php';
?>

<h1 class="font-display text-2xl font-bold mb-1">Dashboard</h1>
<p class="text-sm text-[var(--slate)] mb-8">Overview of your product catalog and incoming inquiries.</p>

<div class="grid sm:grid-cols-3 gap-5 mb-10">
  <div class="bg-white border border-[var(--line)] p-6">
    <span class="text-xs font-semibold text-[var(--slate)] uppercase tracking-wide">Products</span>
    <p class="font-display text-3xl font-bold mt-2"><?= $productCount ?></p>
    <a href="products.php" class="text-sm grad-text font-semibold mt-2 inline-block">Manage products &rarr;</a>
  </div>
  <div class="bg-white border border-[var(--line)] p-6">
    <span class="text-xs font-semibold text-[var(--slate)] uppercase tracking-wide">Total Inquiries</span>
    <p class="font-display text-3xl font-bold mt-2"><?= $inquiryCount ?></p>
    <a href="inquiries.php" class="text-sm grad-text font-semibold mt-2 inline-block">View all &rarr;</a>
  </div>
  <div class="bg-white border border-[var(--line)] p-6">
    <span class="text-xs font-semibold text-[var(--slate)] uppercase tracking-wide">New / Unreviewed</span>
    <p class="font-display text-3xl font-bold mt-2"><?= $newCount ?></p>
    <a href="inquiries.php?status=new" class="text-sm grad-text font-semibold mt-2 inline-block">Review now &rarr;</a>
  </div>
</div>

<h2 class="font-display text-lg font-bold mb-4">Recent Inquiries</h2>
<div class="overflow-x-auto border border-[var(--line)]">
  <table class="admin-table">
    <thead><tr><th>Type</th><th>Name</th><th>Company</th><th>Product</th><th>Date</th></tr></thead>
    <tbody>
      <?php if (!$recent): ?>
        <tr><td colspan="5" class="text-[var(--slate)]">No inquiries yet — they'll appear here once someone submits the Contact or Request a Quote form.</td></tr>
      <?php endif; ?>
      <?php foreach ($recent as $r): ?>
        <tr>
          <td><span class="badge badge-<?= $r['form_type'] === 'quote' ? 'quote' : 'contact' ?>"><?= htmlspecialchars(ucfirst($r['form_type'])) ?></span></td>
          <td><?= htmlspecialchars($r['full_name']) ?></td>
          <td><?= htmlspecialchars($r['company_name']) ?></td>
          <td><?= htmlspecialchars($r['product_name'] ?: '—') ?></td>
          <td><?= htmlspecialchars(date('M j, Y', strtotime($r['created_at']))) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/_layout_bottom.php'; ?>
