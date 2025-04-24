<?php
require_once '../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Tik POST metodas leidžiamas']);
    exit;
}

session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Norėdami skelbti, turite prisijungti']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Privalomi laukai
$required = ['make_id', 'model_id', 'year', 'price', 'power', 'mileage', 
             'engine_capacity', 'fuel_type', 'transmission', 'body_type', 'phone'];

foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['error' => 'Užpildyti ne visi privalomi laukai']);
        exit;
    }
}


$make_id = intval($data['make_id']);
$model_id = intval($data['model_id']);
$year = intval($data['year']);
$price = floatval($data['price']);
$power = intval($data['power']);
$mileage = intval($data['mileage']);
$engine_capacity = floatval($data['engine_capacity']);
$fuel_type = $conn->real_escape_string($data['fuel_type']);
$transmission = $conn->real_escape_string($data['transmission']);
$body_type = $conn->real_escape_string($data['body_type']);
$phone = preg_replace('/[^0-9+]/', '', $data['phone']);
$vin = isset($data['vin']) ? strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $data['vin'])) : null;
$description = isset($data['description']) ? $conn->real_escape_string(trim($data['description'])) : null;
$image_url = filter_var($data['image_url'] ?? '', FILTER_VALIDATE_URL) ? $data['image_url'] : null;

// VIN kodas
if ($vin && strlen($vin) !== 17) {
    http_response_code(400);
    echo json_encode(['error' => 'VIN kodas turi būti 17 simbolių']);
    exit;
}


$sql = "INSERT INTO listings (
    user_id, make_id, model_id, year, power, mileage, body_type, 
    fuel_type, price, image_url, phone, engine_capacity, 
    transmission, vin, description
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    'iiiidisdssdssss', 
    $_SESSION['user_id'], $make_id, $model_id, $year, $power, $mileage, $body_type,
    $fuel_type, $price, $image_url, $phone, $engine_capacity,
    $transmission, $vin, $description
);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'listing_id' => $stmt->insert_id
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'error' => 'Nepavyko išsaugoti skelbimo',
        'db_error' => $conn->error
    ]);
}

$stmt->close();
$conn->close();
?>