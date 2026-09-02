<?php
// AAZLI INDUSTRIES — handles both the Contact form and the Request-a-Quote form.
// Receives a JSON POST body from js/main.js, validates it, stores it in MySQL,
// and returns a JSON response the frontend uses to show a success/error message.

header('Content-Type: application/json');
require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid submission']);
    exit;
}

function clean($v) { return isset($v) ? trim(strip_tags($v)) : null; }

$formType   = clean($data['formType'] ?? 'contact');
$fullName   = clean($data['fullName'] ?? '');
$email      = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL) ? $data['email'] : null;

if (!$fullName || !$email) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'Full name and a valid email are required.']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO inquiries
    (form_type, full_name, company_name, email, phone, country, product_category, product_name, quantity, timeline, requirements, message)
    VALUES (:form_type, :full_name, :company_name, :email, :phone, :country, :product_category, :product_name, :quantity, :timeline, :requirements, :message)");

$stmt->execute([
    ':form_type'        => $formType,
    ':full_name'        => $fullName,
    ':company_name'     => clean($data['companyName'] ?? ''),
    ':email'            => $email,
    ':phone'            => clean($data['phone'] ?? ''),
    ':country'          => clean($data['country'] ?? ''),
    ':product_category' => clean($data['productCategory'] ?? ''),
    ':product_name'     => clean($data['productName'] ?? ''),
    ':quantity'         => clean($data['quantity'] ?? ''),
    ':timeline'         => clean($data['timeline'] ?? ''),
    ':requirements'     => clean($data['requirements'] ?? ''),
    ':message'          => clean($data['message'] ?? ''),
]);

// Optional: email yourself a copy. On local XAMPP, mail() usually won't actually
// send unless you configure sendmail — safe to leave this in, it just won't fire locally.
// mail('[ADD AAZLI EMAIL]', "New $formType inquiry from $fullName", print_r($data, true));

echo json_encode(['ok' => true, 'message' => 'Inquiry received.']);
