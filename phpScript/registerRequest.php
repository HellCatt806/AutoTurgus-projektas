<?php
require_once '../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Tik POST metodas leidžiamas']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$phone = preg_replace('/[^0-9+]/', '', $data['phone'] ?? '');
$password = $data['password'] ?? '';
$confirm_password = $data['confirm_password'] ?? '';

if (empty($username) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Prašome užpildyti visus laukus']);
    exit;
}

if ($password !== $confirm_password) {
    http_response_code(400);
    echo json_encode(['error' => 'Slaptažodžiai nesutampa']);
    exit;
}

if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['error' => 'Slaptažodis turi būti bent 8 simbolių ilgio']);
    exit;
}


$sql = "SELECT id FROM users WHERE email = ? OR username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $email, $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Vartotojas su tokiu el. paštu ar vardu jau egzistuoja']);
    exit;
}


$hashed_password = password_hash($password, PASSWORD_BCRYPT);


$sql = "INSERT INTO users (username, password, email, phone) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ssss', $username, $hashed_password, $email, $phone);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'redirect' => '../login.php']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Registracijos klaida: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>