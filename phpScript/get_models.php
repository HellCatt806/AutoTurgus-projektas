<?php
require_once 'config.php';

header('Content-Type: application/json');

$make_id = isset($_GET['make_id']) ? intval($_GET['make_id']) : 0;

$sql = "SELECT id, name FROM models WHERE make_id = ? ORDER BY name";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $make_id);
$stmt->execute();
$result = $stmt->get_result();

$models = [];
while ($row = $result->fetch_assoc()) {
    $models[] = $row;
}

echo json_encode($models);
$stmt->close();
$conn->close();
?>