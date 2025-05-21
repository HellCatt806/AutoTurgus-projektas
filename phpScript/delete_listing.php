<?php
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}

$listing_id_to_delete = isset($_GET['id']) ? intval($_GET['id']) : 0;
$current_user_id = $_SESSION['user_id'];

if ($listing_id_to_delete <= 0) {
    $_SESSION['delete_error'] = "Neteisingas skelbimo ID.";
    header('Location: ../pages/paskyra/mano_skelbimai.php');
    exit;
}

$sql_select = "SELECT user_id, image_url FROM listings WHERE id = ?";
$stmt_select = $conn->prepare($sql_select);

if (!$stmt_select) {
    error_log("Delete_listing: DB Prepare Error (select): " . $conn->error);
    $_SESSION['delete_error'] = "Duomenų bazės klaida tikrinant skelbimą.";
    header('Location: ../pages/paskyra/mano_skelbimai.php');
    exit;
}

$stmt_select->bind_param('i', $listing_id_to_delete);
if (!$stmt_select->execute()) {
    error_log("Delete_listing: DB Execute Error (select): " . $stmt_select->error);
    $stmt_select->close();
    $_SESSION['delete_error'] = "Duomenų bazės klaida gaunant skelbimo duomenis.";
    header('Location: ../pages/paskyra/mano_skelbimai.php');
    exit;
}

$result_select = $stmt_select->get_result();
if ($result_select->num_rows === 0) {
    $stmt_select->close();
    $_SESSION['delete_error'] = "Skelbimas nerastas.";
    header('Location: ../pages/paskyra/mano_skelbimai.php');
    exit;
}

$listing_to_delete = $result_select->fetch_assoc();
$stmt_select->close();

if ($listing_to_delete['user_id'] != $current_user_id) {
    $_SESSION['delete_error'] = "Jūs neturite teisės ištrinti šį skelbimą.";
    header('Location: ../pages/paskyra/mano_skelbimai.php');
    exit;
}

if (!empty($listing_to_delete['image_url']) && strpos($listing_to_delete['image_url'], 'http') !== 0) {
    $image_file_path_on_server = dirname(__DIR__) . DIRECTORY_SEPARATOR . $listing_to_delete['image_url'];
    
    if (file_exists($image_file_path_on_server)) {
        if (!@unlink($image_file_path_on_server)) {
            error_log("Delete_listing: Nepavyko ištrinti nuotraukos failo: " . $image_file_path_on_server);
        }
    }
}

$sql_delete = "DELETE FROM listings WHERE id = ? AND user_id = ?";
$stmt_delete = $conn->prepare($sql_delete);

if (!$stmt_delete) {
    error_log("Delete_listing: DB Prepare Error (delete): " . $conn->error);
    $_SESSION['delete_error'] = "Duomenų bazės klaida trinant skelbimą.";
    header('Location: ../pages/paskyra/mano_skelbimai.php');
    exit;
}

$stmt_delete->bind_param('ii', $listing_id_to_delete, $current_user_id);

if ($stmt_delete->execute()) {
    if ($stmt_delete->affected_rows > 0) {
        $_SESSION['delete_message'] = "Skelbimas sėkmingai ištrintas.";
    } else {
        $_SESSION['delete_error'] = "Skelbimas nebuvo ištrintas (įvyko klaida).";
    }
} else {
    error_log("Delete_listing: DB Execute Error (delete): " . $stmt_delete->error);
    $_SESSION['delete_error'] = "Nepavyko ištrinti skelbimo iš duomenų bazės.";
}

$stmt_delete->close();
$conn->close();

header('Location: ../pages/paskyra/mano_skelbimai.php');
exit;
?>