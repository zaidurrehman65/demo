<?php
require __DIR__ . '/auth.php';

// Handle delete
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header('Location: products.php?deleted=1');
    exit;
}

$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Products';
$activeNav = 'products';
include __DIR__ . '/_layout_top.php';
?>

<div class="flex items-center justify-between mb-1">
  <h1 class="font-display text-2xl font-bold">Products</h1>
  <a href="product-edit.php" class="btn-grad !py-2.5 !px-5 !text-sm">+ Add New Product</a>
</div>
<p class="text-sm text-[var(--slate)] mb-6">Changes here appear on the live site immediately — no code changes needed.</p>

<?php if (isset($_GET['deleted'])): ?>
  <div class="mb-5 p-3 border border-green-200 bg-green-50 text-green-800 text-sm">Product deleted.</div>
<?php endif; ?>
<?php if (isset($_GET['saved'])): ?>
  <div class="mb-5 p-3 border border-green-200 bg-green-50 text-green-800 text-sm">Product saved.</div>
<?php endif; ?>

<div class="overflow-x-auto border border-[var(--line)]">
  <table class="admin-table">
    <thead><tr><th>Product</th><th>Category</th><th>Featured</th><th>MOQ</th><th>Updated</th><th></th></tr></thead>
    <tbody>
      <?php if (!$products): ?>
        <tr><td colspan="6" class="text-[var(--slate)]">No products yet. Click "Add New Product" to create your first one.</td></tr>
      <?php endif; ?>
      <?php foreach ($products as $p): ?>
        <tr>
          <td>
            <div class="flex items-center gap-3">
              <span class="w-9 h-9 shrink-0 rounded-sm" style="background:linear-gradient(135deg,<?= htmlspecialchars($p['swatch_from']) ?>,<?= htmlspecialchars($p['swatch_to']) ?>)"></span>
              <div><span class="font-semibold"><?= htmlspecialchars($p['name']) ?></span><br><span class="text-xs text-[var(--slate)]"><?= htmlspecialchars($p['slug']) ?></span></div>
            </div>
          </td>
          <td><?= htmlspecialchars($p['category']) ?></td>
          <td><?= $p['featured'] ? '<span class="badge badge-reviewed">Featured</span>' : '—' ?></td>
          <td><?= htmlspecialchars($p['moq']) ?></td>
          <td><?= htmlspecialchars(date('M j, Y', strtotime($p['updated_at']))) ?></td>
          <td class="whitespace-nowrap">
            <a href="product-edit.php?id=<?= $p['id'] ?>" class="text-sm font-semibold grad-text mr-4">Edit</a>
            <a href="products.php?delete=<?= $p['id'] ?>" onclick="return confirm('Delete this product? This cannot be undone.')" class="text-sm font-semibold text-red-600">Delete</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>

<?php include __DIR__ . '/_layout_bottom.php'; ?>
