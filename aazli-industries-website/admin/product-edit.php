<?php
require __DIR__ . '/auth.php';

$id = $_GET['id'] ?? null;
$product = [
    'slug' => '', 'name' => '', 'category' => 'T-Shirts', 'category_slug' => 'tshirts',
    'featured' => 0, 'swatch_from' => '#F0721F', 'swatch_to' => '#DE1E74',
    'short_desc' => '', 'description' => '', 'material' => '', 'sizes' => '', 'moq' => '',
    'lead_time' => '', 'features' => '', 'customization' => '',
];
if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $found = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($found) $product = $found;
}

$categories = [
    'tshirts' => 'T-Shirts', 'hoodies' => 'Hoodies & Sweatshirts', 'activewear' => 'Activewear & Sportswear',
    'streetwear' => 'Streetwear Essentials', 'bottoms' => 'Joggers & Bottoms', 'kids' => 'Kidswear',
];

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $slug = trim($_POST['slug'] ?: preg_replace('/[^a-z0-9]+/', '-', strtolower($_POST['name'])));
    $slug = trim($slug, '-');
    $name = trim($_POST['name'] ?? '');
    $categorySlug = $_POST['category_slug'] ?? 'tshirts';
    $category = $categories[$categorySlug] ?? 'T-Shirts';

    if (!$name) $errors[] = 'Product name is required.';
    if (!$slug) $errors[] = 'Slug could not be generated — please enter one manually.';

    if (!$errors) {
        $data = [
            'slug' => $slug, 'name' => $name, 'category' => $category, 'category_slug' => $categorySlug,
            'featured' => isset($_POST['featured']) ? 1 : 0,
            'swatch_from' => $_POST['swatch_from'] ?: '#F0721F',
            'swatch_to' => $_POST['swatch_to'] ?: '#DE1E74',
            'short_desc' => trim($_POST['short_desc'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'material' => trim($_POST['material'] ?? ''),
            'sizes' => trim($_POST['sizes'] ?? ''),
            'moq' => trim($_POST['moq'] ?? ''),
            'lead_time' => trim($_POST['lead_time'] ?? ''),
            'features' => trim($_POST['features'] ?? ''),
            'customization' => trim($_POST['customization'] ?? ''),
        ];

        try {
            if ($id) {
                $data['id'] = $id;
                $pdo->prepare("UPDATE products SET slug=:slug, name=:name, category=:category, category_slug=:category_slug,
                    featured=:featured, swatch_from=:swatch_from, swatch_to=:swatch_to, short_desc=:short_desc,
                    description=:description, material=:material, sizes=:sizes, moq=:moq, lead_time=:lead_time,
                    features=:features, customization=:customization WHERE id=:id")->execute($data);
            } else {
                $pdo->prepare("INSERT INTO products (slug, name, category, category_slug, featured, swatch_from, swatch_to,
                    short_desc, description, material, sizes, moq, lead_time, features, customization)
                    VALUES (:slug, :name, :category, :category_slug, :featured, :swatch_from, :swatch_to,
                    :short_desc, :description, :material, :sizes, :moq, :lead_time, :features, :customization)")->execute($data);
            }
            header('Location: products.php?saved=1');
            exit;
        } catch (PDOException $e) {
            $errors[] = str_contains($e->getMessage(), 'Duplicate') ? 'That slug is already used by another product — please choose a different one.' : 'Could not save: ' . $e->getMessage();
        }
    }
    $product = array_merge($product, $_POST, ['featured' => isset($_POST['featured']) ? 1 : 0]);
}

$pageTitle = $id ? 'Edit Product' : 'Add Product';
$activeNav = 'products';
include __DIR__ . '/_layout_top.php';
?>

<a href="products.php" class="text-sm text-[var(--slate)] hover:text-[var(--orange)] mb-4 inline-block">&larr; Back to Products</a>
<h1 class="font-display text-2xl font-bold mb-6"><?= $id ? 'Edit Product' : 'Add New Product' ?></h1>

<?php foreach ($errors as $e): ?>
  <div class="mb-3 p-3 border border-red-200 bg-red-50 text-red-700 text-sm"><?= htmlspecialchars($e) ?></div>
<?php endforeach; ?>

<form method="post" class="form-grid bg-white border border-[var(--line)] p-8 grid sm:grid-cols-2 gap-5 max-w-4xl">
  <div><label>Product Name *</label><input name="name" required value="<?= htmlspecialchars($product['name']) ?>" /></div>
  <div><label>URL Slug (leave blank to auto-generate)</label><input name="slug" value="<?= htmlspecialchars($product['slug']) ?>" placeholder="e.g. classic-crew-tshirt" /></div>

  <div>
    <label>Category *</label>
    <select name="category_slug">
      <?php foreach ($categories as $slug => $label): ?>
        <option value="<?= $slug ?>" <?= $product['category_slug'] === $slug ? 'selected' : '' ?>><?= $label ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="flex items-end pb-2">
    <label class="flex items-center gap-2 font-normal text-sm">
      <input type="checkbox" name="featured" value="1" <?= $product['featured'] ? 'checked' : '' ?> class="w-auto" />
      Show on homepage as a Featured Product
    </label>
  </div>

  <div><label>Card Color — From</label><input type="color" name="swatch_from" value="<?= htmlspecialchars($product['swatch_from']) ?>" class="h-11" /></div>
  <div><label>Card Color — To</label><input type="color" name="swatch_to" value="<?= htmlspecialchars($product['swatch_to']) ?>" class="h-11" /></div>

  <div class="sm:col-span-2"><label>Short Description (shown on product cards)</label><input name="short_desc" value="<?= htmlspecialchars($product['short_desc']) ?>" /></div>
  <div class="sm:col-span-2"><label>Full Description (shown on the product page)</label><textarea name="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea></div>

  <div><label>Material</label><input name="material" value="<?= htmlspecialchars($product['material']) ?>" /></div>
  <div><label>Sizes</label><input name="sizes" value="<?= htmlspecialchars($product['sizes']) ?>" placeholder="e.g. XS – 3XL" /></div>
  <div><label>MOQ</label><input name="moq" value="<?= htmlspecialchars($product['moq']) ?>" /></div>
  <div><label>Lead Time</label><input name="lead_time" value="<?= htmlspecialchars($product['lead_time']) ?>" /></div>

  <div class="sm:col-span-2"><label>Key Features (one per line)</label><textarea name="features" rows="4"><?= htmlspecialchars($product['features']) ?></textarea></div>
  <div class="sm:col-span-2"><label>Customization Options (comma-separated)</label><textarea name="customization" rows="2"><?= htmlspecialchars($product['customization']) ?></textarea></div>

  <div class="sm:col-span-2 flex gap-3 pt-2">
    <button type="submit" class="btn-grad">Save Product</button>
    <a href="products.php" class="btn-outline">Cancel</a>
  </div>
</form>

<?php include __DIR__ . '/_layout_bottom.php'; ?>
