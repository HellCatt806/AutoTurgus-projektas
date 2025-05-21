<?php
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$listing_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$redirect_url = '../index.php'; 
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    $current_host = $_SERVER['HTTP_HOST'];
    if ($referer_host == $current_host) {
        $redirect_url = $_SERVER['HTTP_REFERER'];
    }
}


if ($listing_id <= 0) {
    $_SESSION['favorite_action_error'] = "Neteisingas skelbimo ID.";
    header('Location: ' . $redirect_url);
    exit;
}

$stmt_check = $conn->prepare("SELECT id FROM user_favorites WHERE user_id = ? AND listing_id = ?");
if (!$stmt_check) {
    error_log("Pamegti.php: DB Prepare Error (check favorite): " . $conn->error);
    $_SESSION['favorite_action_error'] = "Sistemos klaida (1).";
    header('Location: ' . $redirect_url);
    exit;
}
$stmt_check->bind_param('ii', $user_id, $listing_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();
$is_favorite = $result_check->num_rows > 0;
$stmt_check->close();

if ($is_favorite) {
    $stmt_delete = $conn->prepare("DELETE FROM user_favorites WHERE user_id = ? AND listing_id = ?");
    if (!$stmt_delete) {
        error_log("Pamegti.php: DB Prepare Error (delete favorite): " . $conn->error);
        $_SESSION['favorite_action_error'] = "Sistemos klaida (2).";
        header('Location: ' . $redirect_url);
        exit;
    }
    $stmt_delete->bind_param('ii', $user_id, $listing_id);
    if ($stmt_delete->execute()) {
        $_SESSION['favorite_action_success'] = "Skelbimas pašalintas iš mėgstamų.";
    } else {
        error_log("Pamegti.php: DB Execute Error (delete favorite): " . $stmt_delete->error);
        $_SESSION['favorite_action_error'] = "Nepavyko pašalinti skelbimo iš mėgstamų.";
    }
    $stmt_delete->close();
} else {
    $stmt_check_listing = $conn->prepare("SELECT id FROM listings WHERE id = ?");
    if (!$stmt_check_listing) {
        error_log("Pamegti.php: DB Prepare Error (check listing exists): " . $conn->error);
        $_SESSION['favorite_action_error'] = "Sistemos klaida (3).";
        header('Location: ' . $redirect_url);
        exit;
    }
    $stmt_check_listing->bind_param('i', $listing_id);
    $stmt_check_listing->execute();
    $result_listing_exists = $stmt_check_listing->get_result();
    $stmt_check_listing->close();

    if ($result_listing_exists->num_rows > 0) {
        $stmt_insert = $conn->prepare("INSERT INTO user_favorites (user_id, listing_id) VALUES (?, ?)");
        if (!$stmt_insert) {
            error_log("Pamegti.php: DB Prepare Error (insert favorite): " . $conn->error);
            $_SESSION['favorite_action_error'] = "Sistemos klaida (4).";
            header('Location: ' . $redirect_url);
            exit;
        }
        $stmt_insert->bind_param('ii', $user_id, $listing_id);
        if ($stmt_insert->execute()) {
            $_SESSION['favorite_action_success'] = "Skelbimas pridėtas prie mėgstamų!";
        } else {
            if ($conn->errno == 1062) { 
                 $_SESSION['favorite_action_error'] = "Šis skelbimas jau yra jūsų mėgstamuose.";
            } else {
                error_log("Pamegti.php: DB Execute Error (insert favorite): " . $stmt_insert->error);
                $_SESSION['favorite_action_error'] = "Nepavyko pridėti skelbimo prie mėgstamų.";
            }
        }
        $stmt_insert->close();
    } else {
        $_SESSION['favorite_action_error'] = "Bandoma pažymėti neegzistuojanti skelbimą.";
    }
}

if ($conn) {
    $conn->close();
}
header('Location: ' . $redirect_url);
exit;
?>