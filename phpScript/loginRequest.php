<?php
require_once '../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Tik POST metodas leidžiamas']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['error' => 'Prašome užpildyti visus laukus']);
    exit;
}

$sql = "SELECT id, username, password FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(401);
    echo json_encode(['error' => 'Neteisingi prisijungimo duomenys']);
    exit;
}

$user = $result->fetch_assoc();
if (!password_verify($password, $user['password'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Neteisingi prisijungimo duomenys']);
    exit;
}

session_start();
$_SESSION['user_id'] = $user['id'];
$_SESSION['username'] = $user['username'];

echo json_encode(['success' => true, 'redirect' => '../index.php']);
$stmt->close();
$conn->close();
?>