<?php
require __DIR__ . '/auth.php';

if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM inquiries WHERE id = ?")->execute([$_GET['delete']]);
    header('Location: inquiries.php?deleted=1');
    exit;
}
if (isset($_GET['mark_reviewed'])) {
    $pdo->prepare("UPDATE inquiries SET status = 'reviewed' WHERE id = ?")->execute([$_GET['mark_reviewed']]);
    header('Location: inquiries.php');
    exit;
}

$filterType = $_GET['type'] ?? 'all';
$filterStatus = $_GET['status'] ?? 'all';

$sql = "SELECT * FROM inquiries WHERE 1=1";
$params = [];
if ($filterType !== 'all') { $sql .= " AND form_type = ?"; $params[] = $filterType; }
if ($filterStatus !== 'all') { $sql .= " AND status = ?"; $params[] = $filterStatus; }
$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Inquiries & Quotes';
$activeNav = 'inquiries';
include __DIR__ . '/_layout_top.php';
?>

<h1 class="font-display text-2xl font-bold mb-1">Inquiries &amp; Quotes</h1>
<p class="text-sm text-[var(--slate)] mb-6">Every Contact and Request-a-Quote submission from the live site lands here.</p>

<?php if (isset($_GET['deleted'])): ?>
  <div class="mb-5 p-3 border border-green-200 bg-green-50 text-green-800 text-sm">Inquiry deleted.</div>
<?php endif; ?>

<div class="flex flex-wrap gap-3 mb-6">
  <select onchange="location.href='?type='+this.value+'&status=<?= $filterStatus ?>'" class="border border-[var(--line)] px-3 py-2 text-sm bg-white">
    <option value="all" <?= $filterType==='all'?'selected':'' ?>>All Types</option>
    <option value="quote" <?= $filterType==='quote'?'selected':'' ?>>Quote Requests</option>
    <option value="contact" <?= $filterType==='contact'?'selected':'' ?>>Contact Messages</option>
  </select>
  <select onchange="location.href='?status='+this.value+'&type=<?= $filterType ?>'" class="border border-[var(--line)] px-3 py-2 text-sm bg-white">
    <option value="all" <?= $filterStatus==='all'?'selected':'' ?>>All Statuses</option>
    <option value="new" <?= $filterStatus==='new'?'selected':'' ?>>New</option>
    <option value="reviewed" <?= $filterStatus==='reviewed'?'selected':'' ?>>Reviewed</option>
  </select>
</div>

<div class="space-y-3">
  <?php if (!$inquiries): ?>
    <div class="p-6 border border-[var(--line)] bg-white text-sm text-[var(--slate)]">No inquiries match this filter.</div>
  <?php endif; ?>
  <?php foreach ($inquiries as $r): ?>
    <details class="border border-[var(--line)] bg-white">
      <summary class="p-4 cursor-pointer flex flex-wrap items-center gap-3 list-none">
        <span class="badge badge-<?= $r['form_type'] === 'quote' ? 'quote' : 'contact' ?>"><?= htmlspecialchars(ucfirst($r['form_type'])) ?></span>
        <span class="badge badge-<?= $r['status'] === 'new' ? 'new' : 'reviewed' ?>"><?= htmlspecialchars(ucfirst($r['status'])) ?></span>
        <span class="font-semibold"><?= htmlspecialchars($r['full_name']) ?></span>
        <span class="text-sm text-[var(--slate)]"><?= htmlspecialchars($r['company_name']) ?></span>
        <?php if ($r['product_name']): ?><span class="text-sm text-[var(--slate)]">&middot; <?= htmlspecialchars($r['product_name']) ?></span><?php endif; ?>
        <span class="text-xs text-[var(--slate)] ml-auto"><?= htmlspecialchars(date('M j, Y g:ia', strtotime($r['created_at']))) ?></span>
      </summary>
      <div class="p-5 border-t border-[var(--line)] grid sm:grid-cols-2 gap-4 text-sm">
        <div><span class="font-semibold block text-xs text-[var(--slate)] uppercase mb-0.5">Email</span><?= htmlspecialchars($r['email']) ?></div>
        <div><span class="font-semibold block text-xs text-[var(--slate)] uppercase mb-0.5">Phone</span><?= htmlspecialchars($r['phone']) ?: '—' ?></div>
        <div><span class="font-semibold block text-xs text-[var(--slate)] uppercase mb-0.5">Country</span><?= htmlspecialchars($r['country']) ?: '—' ?></div>
        <div><span class="font-semibold block text-xs text-[var(--slate)] uppercase mb-0.5">Quantity</span><?= htmlspecialchars($r['quantity']) ?: '—' ?></div>
        <div><span class="font-semibold block text-xs text-[var(--slate)] uppercase mb-0.5">Category</span><?= htmlspecialchars($r['product_category']) ?: '—' ?></div>
        <div><span class="font-semibold block text-xs text-[var(--slate)] uppercase mb-0.5">Timeline</span><?= htmlspecialchars($r['timeline']) ?: '—' ?></div>
        <div class="sm:col-span-2"><span class="font-semibold block text-xs text-[var(--slate)] uppercase mb-0.5">Custom Requirements</span><?= nl2br(htmlspecialchars($r['requirements'])) ?: '—' ?></div>
        <div class="sm:col-span-2"><span class="font-semibold block text-xs text-[var(--slate)] uppercase mb-0.5">Message</span><?= nl2br(htmlspecialchars($r['message'])) ?: '—' ?></div>
        <div class="sm:col-span-2 flex gap-4 pt-2 border-t border-[var(--line)]">
          <?php if ($r['status'] === 'new'): ?>
            <a href="?mark_reviewed=<?= $r['id'] ?>" class="text-sm font-semibold grad-text">Mark as Reviewed</a>
          <?php endif; ?>
          <a href="?delete=<?= $r['id'] ?>" onclick="return confirm('Delete this inquiry?')" class="text-sm font-semibold text-red-600">Delete</a>
        </div>
      </div>
    </details>
  <?php endforeach; ?>
</div>

<?php include __DIR__ . '/_layout_bottom.php'; ?>
