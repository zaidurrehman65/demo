<?php
// AAZLI INDUSTRIES — public product feed, consumed by js/main.js on the live site.
header('Content-Type: application/json');
require __DIR__ . '/config.php';

$rows = $pdo->query("SELECT * FROM products ORDER BY created_at ASC")->fetchAll(PDO::FETCH_ASSOC);

$products = array_map(function ($r) {
    return [
        'id'            => $r['slug'],
        'name'          => $r['name'],
        'category'      => $r['category'],
        'categorySlug'  => $r['category_slug'],
        'featured'      => (bool) $r['featured'],
        'swatch'        => "linear-gradient(135deg,{$r['swatch_from']},{$r['swatch_to']})",
        'short'         => $r['short_desc'],
        'description'   => $r['description'],
        'material'      => $r['material'],
        'sizes'         => $r['sizes'],
        'moq'           => $r['moq'],
        'leadTime'      => $r['lead_time'],
        'features'      => array_values(array_filter(array_map('trim', explode("\n", (string) $r['features'])))),
        'customization' => array_values(array_filter(array_map('trim', explode(',', (string) $r['customization'])))),
    ];
}, $rows);

echo json_encode(['ok' => true, 'products' => $products]);
