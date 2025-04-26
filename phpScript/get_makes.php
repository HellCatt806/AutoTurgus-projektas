<?php
require_once 'config.php';

header('Content-Type: application/json');

$vehicle_type_id = isset($_GET['vehicle_type_id']) ? intval($_GET['vehicle_type_id']) : 1;

$sql = "SELECT id, name FROM makes WHERE vehicle_type_id = ? ORDER BY name";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $vehicle_type_id);
$stmt->execute();
$result = $stmt->get_result();

$makes = [];
while ($row = $result->fetch_assoc()) {
    $makes[] = $row;
}

echo json_encode($makes);
$stmt->close();
$conn->close();
?>