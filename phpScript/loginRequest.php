<?php
require_once 'config.php';
require_once 'funkc.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(405, ['error' => 'Tik POST metodas leidžiamas']);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    sendJsonResponse(400, ['error' => 'Neteisingas užklausos formatas']);
}

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

$validation_errors = validateLoginData($email, $password);
if (!empty($validation_errors)) {
    sendJsonResponse(400, ['error' => implode(', ', $validation_errors)]);
}

$user = verifyUserCredentials($conn, $email, $password);

if ($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    sendJsonResponse(200, ['success' => true, 'message' => 'Prisijungimas sėkmingas!', 'redirect' => '../index.php']);
} else {
    sendJsonResponse(401, ['error' => 'Neteisingi prisijungimo duomenys']);
}

$conn->close();
?>