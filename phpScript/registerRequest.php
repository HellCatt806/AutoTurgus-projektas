<?php
require_once 'config.php';
require_once 'funkc.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(405, ['error' => '']);
}

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    sendJsonResponse(400, ['error' => 'Neteisingas formatas']);
}

$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$phone = preg_replace('/[^0-9+]/', '', $data['phone'] ?? '');
$password = $data['password'] ?? '';
$confirm_password = $data['confirm_password'] ?? '';

$validation_errors = validateRegistrationData($username, $email, $phone, $password, $confirm_password);
if (!empty($validation_errors)) {
    sendJsonResponse(400, ['error' => implode(', ', $validation_errors)]);
}

if (userExists($conn, $email, $username)) {
    sendJsonResponse(400, ['error' => 'Vartotojas su tokiu el. paštu ar vardu jau egzistuoja']);
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);
if ($hashed_password === false) {
    error_log("Password hashing failed for user: " . $email);
    sendJsonResponse(500, ['error' => 'Sistemos klaida kuriant slaptažodį']);
}

if (createUser($conn, $username, $hashed_password, $email, $phone)) {
    sendJsonResponse(200, ['success' => true, 'message' => 'Registracija sėkminga!', 'redirect' => '../login.php']);
} else {
    sendJsonResponse(500, ['error' => 'Registracijos klaida. Bandykite vėliau.']);
}

$conn->close();
?>