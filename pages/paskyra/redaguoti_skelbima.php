<?php
require_once '../../phpScript/config.php';
require_once '../../phpScript/funkc.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$listing_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$page_errors = [];
$success_message = '';
$upload_dir_absolute = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

if ($listing_id <= 0) {
    header('Location: mano_skelbimai.php?error=invalid_id');
    exit;
}

$stmt_fetch = $conn->prepare("SELECT * FROM listings WHERE id = ? AND user_id = ?");
$stmt_fetch->bind_param('ii', $listing_id, $user_id);
$stmt_fetch->execute();
$result_fetch = $stmt_fetch->get_result();
$form_data = $result_fetch->fetch_assoc();
$stmt_fetch->close();

if (!$form_data) {
    header('Location: mano_skelbimai.php?error=not_found_or_not_owner');
    exit;
}

$existing_images = [];
$stmt_images = $conn->prepare("SELECT id, image_path, is_primary FROM listing_images WHERE listing_id = ? ORDER BY id ASC");
if ($stmt_images) {
    $stmt_images->bind_param('i', $listing_id);
    if ($stmt_images->execute()) {
        $result_images = $stmt_images->get_result();
        while ($img_row = $result_images->fetch_assoc()) {
            $existing_images[] = $img_row;
        }
    }
    $stmt_images->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (['vehicle_type_id', 'make_id', 'model_id', 'year', 'price', 'power', 'mileage', 'engine_capacity', 'fuel_type', 'phone', 'city', 'vin', 'description', 'body_type', 'transmission'] as $key) {
        if (isset($_POST[$key])) {
            $form_data[$key] = $_POST[$key];
        }
    }
    
    $current_primary_image_path_for_db = $form_data['image_url'];
    $primary_new_image_identifier_from_post = isset($_POST['primary_new_image_identifier_edit']) ? $_POST['primary_new_image_identifier_edit'] : null;
    $chosen_existing_primary_id = isset($_POST['primary_image_id']) ? intval($_POST['primary_image_id']) : null;
    $image_paths_to_delete_on_server = [];
    $ids_marked_for_deletion = (isset($_POST['delete_images']) && is_array($_POST['delete_images'])) ? array_map('intval', $_POST['delete_images']) : [];

    if (!empty($ids_marked_for_deletion)) {
        $placeholdersDel = implode(',', array_fill(0, count($ids_marked_for_deletion), '?'));
        $stmt_get_paths_del = $conn->prepare("SELECT image_path, is_primary FROM listing_images WHERE listing_id = ? AND id IN ($placeholdersDel)");
        if ($stmt_get_paths_del) {
            $types_del = 'i' . str_repeat('i', count($ids_marked_for_deletion));
            $params_del = array_merge([$listing_id], $ids_marked_for_deletion);
            $stmt_get_paths_del->bind_param($types_del, ...$params_del);
            $stmt_get_paths_del->execute();
            $result_paths_del = $stmt_get_paths_del->get_result();
            while ($row_path_del = $result_paths_del->fetch_assoc()) {
                if (strpos($row_path_del['image_path'], 'uploads/') === 0) {
                    $image_paths_to_delete_on_server[] = $upload_dir_absolute . basename($row_path_del['image_path']);
                }
                if ($row_path_del['is_primary'] && $row_path_del['image_path'] == $current_primary_image_path_for_db) {
                    $current_primary_image_path_for_db = null;
                }
            }
            $stmt_get_paths_del->close();
        }
        $stmt_delete_db_del = $conn->prepare("DELETE FROM listing_images WHERE listing_id = ? AND id IN ($placeholdersDel)");
        if ($stmt_delete_db_del) {
            $stmt_delete_db_del->bind_param($types_del, ...$params_del);
            $stmt_delete_db_del->execute();
            $stmt_delete_db_del->close();
        }
        $existing_images = array_filter($existing_images, fn($img) => !in_array($img['id'], $ids_marked_for_deletion));
        if ($chosen_existing_primary_id !== null && in_array($chosen_existing_primary_id, $ids_marked_for_deletion)) {
            $chosen_existing_primary_id = null; 
        }
    }

    $new_images_were_uploaded = false;
    $actual_newly_uploaded_paths = [];

    if (isset($_FILES['image_uploads']) && is_array($_FILES['image_uploads']['name']) && count(array_filter($_FILES['image_uploads']['name'])) > 0) {
        $image_upload_results = handleMultipleImageUploads($_FILES['image_uploads'], $upload_dir_absolute); //
        if (!empty($image_upload_results['errors'])) {
            $page_errors = array_merge($page_errors, $image_upload_results['errors']);
        }
        if (!empty($image_upload_results['paths'])) {
            $actual_newly_uploaded_paths = $image_upload_results['paths'];
            $new_images_were_uploaded = true;
        }
    }
    
    $final_primary_image_path = $current_primary_image_path_for_db;
    $final_primary_listing_image_id = null;
    $is_new_image_primary = false;

    if ($new_images_were_uploaded) {
        $stmt_reset_primary_all = $conn->prepare("UPDATE listing_images SET is_primary = 0 WHERE listing_id = ?");
        if ($stmt_reset_primary_all) { $stmt_reset_primary_all->bind_param('i', $listing_id); $stmt_reset_primary_all->execute(); $stmt_reset_primary_all->close(); }

        $temp_primary_new_path_candidate = null;
        if (!empty($primary_new_image_identifier_from_post) && isset($_FILES['image_uploads']['tmp_name'])) {
            $temp_primary_new_path_candidate = $actual_newly_uploaded_paths[0];
            $is_new_image_primary = true;
        } elseif (empty($chosen_existing_primary_id) && count($existing_images) == 0) {
            $temp_primary_new_path_candidate = $actual_newly_uploaded_paths[0];
            $is_new_image_primary = true;
        }


        $stmt_insert_new_image = $conn->prepare("INSERT INTO listing_images (listing_id, image_path, is_primary) VALUES (?, ?, ?)");
        if ($stmt_insert_new_image) {
            foreach ($actual_newly_uploaded_paths as $index => $image_path) {
                $is_primary_flag = 0;
                if ($is_new_image_primary && $image_path === $temp_primary_new_path_candidate) {
                    $is_primary_flag = 1;
                    $final_primary_image_path = $image_path;
                }
                $stmt_insert_new_image->bind_param("isi", $listing_id, $image_path, $is_primary_flag);
                $stmt_insert_new_image->execute();
                if ($is_primary_flag) {
                    $final_primary_listing_image_id = $stmt_insert_new_image->insert_id;
                }
            }
            $stmt_insert_new_image->close();
        }
        if ($is_new_image_primary) {
            $chosen_existing_primary_id = null;
        }

    }
    
    if (!$is_new_image_primary && $chosen_existing_primary_id !== null) {
        $found_existing_primary_path = null;
        foreach ($existing_images as $ex_img) {
            if ($ex_img['id'] == $chosen_existing_primary_id) {
                $found_existing_primary_path = $ex_img['image_path'];
                break;
            }
        }
        if ($found_existing_primary_path) {
            $final_primary_image_path = $found_existing_primary_path;
            $final_primary_listing_image_id = $chosen_existing_primary_id;

            $stmt_reset_primary = $conn->prepare("UPDATE listing_images SET is_primary = 0 WHERE listing_id = ?");
            if ($stmt_reset_primary) { $stmt_reset_primary->bind_param('i', $listing_id); $stmt_reset_primary->execute(); $stmt_reset_primary->close(); }
            
            $stmt_set_primary = $conn->prepare("UPDATE listing_images SET is_primary = 1 WHERE listing_id = ? AND id = ?");
            if ($stmt_set_primary) { $stmt_set_primary->bind_param('ii', $listing_id, $chosen_existing_primary_id); $stmt_set_primary->execute(); $stmt_set_primary->close(); }
        }
    }
    
    if ($final_primary_image_path === null) {
        $all_images_after_ops = [];
        $stmt_get_all = $conn->prepare("SELECT id, image_path FROM listing_images WHERE listing_id = ? ORDER BY id ASC");
        if($stmt_get_all) {
            $stmt_get_all->bind_param('i', $listing_id);
            $stmt_get_all->execute();
            $res_all = $stmt_get_all->get_result();
            while($r = $res_all->fetch_assoc()) $all_images_after_ops[] = $r;
            $stmt_get_all->close();
        }

        if (!empty($all_images_after_ops)) {
            $first_available_image = $all_images_after_ops[0];
            $final_primary_image_path = $first_available_image['image_path'];
            $final_primary_listing_image_id = $first_available_image['id'];
            
            $stmt_reset_primary_f = $conn->prepare("UPDATE listing_images SET is_primary = 0 WHERE listing_id = ?");
            if ($stmt_reset_primary_f) { $stmt_reset_primary_f->bind_param('i', $listing_id); $stmt_reset_primary_f->execute(); $stmt_reset_primary_f->close(); }

            $stmt_set_primary_f = $conn->prepare("UPDATE listing_images SET is_primary = 1 WHERE listing_id = ? AND id = ?");
            if ($stmt_set_primary_f) { $stmt_set_primary_f->bind_param('ii', $listing_id, $final_primary_listing_image_id); $stmt_set_primary_f->execute(); $stmt_set_primary_f->close(); }
        }
    }

    $is_car = (isset($form_data['vehicle_type_id']) && $form_data['vehicle_type_id'] == 1);
    $validation_errors = validateListingData($form_data, $is_car);
    if (!empty($validation_errors)) {
        $page_errors = array_merge($page_errors, $validation_errors);
    }

    if (empty($page_errors)) {
        $vt_id_sql = intval($form_data['vehicle_type_id']);
        $mk_id_sql = intval($form_data['make_id']);
        $md_id_sql = intval($form_data['model_id']);
        $year_sql = intval($form_data['year']);
        $power_sql = intval($form_data['power']);
        $mileage_raw = $form_data['mileage'];
        $mileage_sql = ($mileage_raw === '' || $mileage_raw === null || !is_numeric($mileage_raw)) ? "NULL" : intval($mileage_raw);
        $price_sql = floatval($form_data['price']);
        $engine_capacity_sql = floatval($form_data['engine_capacity']);
        $fuel_type_sql = "'" . $conn->real_escape_string((string)$form_data['fuel_type']) . "'";
        $body_type_raw = ($vt_id_sql == 1 && isset($form_data['body_type'])) ? trim($form_data['body_type']) : '';
        $body_type_sql = empty($body_type_raw) ? "NULL" : "'" . $conn->real_escape_string($body_type_raw) . "'";
        $transmission_raw = ($vt_id_sql == 1 && isset($form_data['transmission'])) ? trim($form_data['transmission']) : '';
        $transmission_sql = empty($transmission_raw) ? "NULL" : "'" . $conn->real_escape_string($transmission_raw) . "'";
        $phone_sql = "'" . $conn->real_escape_string(preg_replace('/[^0-9+]/', '', $form_data['phone'])) . "'";
        $city_raw = isset($form_data['city']) ? trim($form_data['city']) : '';
        $city_sql = empty($city_raw) ? "NULL" : "'" . $conn->real_escape_string($city_raw) . "'";
        $vin_raw_val = isset($form_data['vin']) ? strtoupper(preg_replace('/[^A-Za-z0-9]/', '', trim($form_data['vin']))) : '';
        $vin_sql = empty($vin_raw_val) ? "NULL" : "'" . $conn->real_escape_string($vin_raw_val) . "'";
        $description_raw_val = isset($form_data['description']) ? trim($form_data['description']) : '';
        $description_sql = empty($description_raw_val) ? "NULL" : "'" . $conn->real_escape_string($description_raw_val) . "'";
        $primary_image_for_listings_sql_direct = ($final_primary_image_path === null || trim((string)$final_primary_image_path) === '') 
                                             ? "NULL" 
                                             : "'" . $conn->real_escape_string((string)$final_primary_image_path) . "'";
        
        $update_sql_direct = "UPDATE listings SET 
            vehicle_type_id = $vt_id_sql, make_id = $mk_id_sql, model_id = $md_id_sql, year = $year_sql, 
            power = $power_sql, mileage = $mileage_sql, body_type = $body_type_sql, fuel_type = $fuel_type_sql,
            price = $price_sql, image_url = $primary_image_for_listings_sql_direct, phone = $phone_sql, city = $city_sql, 
            engine_capacity = $engine_capacity_sql, transmission = $transmission_sql, vin = $vin_sql,
            description = $description_sql
        WHERE id = $listing_id AND user_id = $user_id";
        
        if ($conn->query($update_sql_direct) === TRUE) {
            foreach ($image_paths_to_delete_on_server as $file_path_on_server) {
                if (file_exists($file_path_on_server)) {
                    @unlink($file_path_on_server);
                }
            }
            $_SESSION['success_message'] = 'Skelbimas sėkmingai atnaujintas!';
            header('Location: ../skelbimas.php?id='.$listing_id);
            exit;
        } else {
            $page_errors[] = 'Nepavyko atnaujinti skelbimo duomenų: ' . $conn->error;
        }
    }

    if (!empty($page_errors)) {
        $existing_images = [];
        $stmt_images_err = $conn->prepare("SELECT id, image_path, is_primary FROM listing_images WHERE listing_id = ? ORDER BY id ASC");
        if ($stmt_images_err) {
            $stmt_images_err->bind_param('i', $listing_id);
            if ($stmt_images_err->execute()) {
                $result_images_err = $stmt_images_err->get_result();
                while ($img_row_err = $result_images_err->fetch_assoc()) {
                    $existing_images[] = $img_row_err;
                    if ($img_row_err['is_primary']) $form_data['image_url'] = $img_row_err['image_path'];
                }
            }
            $stmt_images_err->close();
        }
         if (empty(array_filter($existing_images, fn($ei) => $ei['is_primary']))) $form_data['image_url'] = null;
    }
}

$current_vehicle_type_id_for_makes = isset($form_data['vehicle_type_id']) ? intval($form_data['vehicle_type_id']) : 1;
$vehicle_types_query_result = $conn->query("SELECT id, name FROM vehicle_types ORDER BY id");
$makes_query_result = null;
$models_query_result = null;
$stmt_makes_form = $conn->prepare("SELECT id, name FROM makes WHERE vehicle_type_id = ? ORDER BY name");
if($stmt_makes_form) {
    $stmt_makes_form->bind_param('i', $current_vehicle_type_id_for_makes);
    $stmt_makes_form->execute();
    $makes_query_result = $stmt_makes_form->get_result();
    $stmt_makes_form->close();
}
if (!empty($form_data['make_id'])) {
    $stmt_models_form = $conn->prepare("SELECT id, name FROM models WHERE make_id = ? ORDER BY name");
    if ($stmt_models_form) {
        $current_make_id_for_models = intval($form_data['make_id']);
        $stmt_models_form->bind_param('i', $current_make_id_for_models);
        $stmt_models_form->execute();
        $models_query_result = $stmt_models_form->get_result();
        $stmt_models_form->close();
    }
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Redaguoti Skelbimą - AutoTurgus</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/naujas_skelbimas.css">
    <link rel="stylesheet" href="../../css/paskyra.css">
    <style>
        .img-preview-wrapper.primary { border: 2px solid purple !important; }
        .img-preview-wrapper .primary-indicator { position: absolute; top: -10px; right: -10px; background-color: purple; color: white; font-size: 0.7em; padding: 2px 5px; border-radius: 3px; font-weight: bold; display: none; }
        .img-preview-wrapper.primary .primary-indicator { display: block; }
        .delete-new-preview-btn { position: absolute; top: -8px; right: -8px; background-color: rgba(220,53,69,0.8); color:white; border:1px solid rgba(255,255,255,0.5); border-radius:50%; width:20px; height:20px; font-size:13px; line-height:18px; text-align:center; cursor:pointer; opacity:0.85; font-weight:bold; }
        .delete-new-preview-btn:hover { background-color: #dc3545; opacity: 1; }
        .img-preview-wrapper-existing { position: relative; }
    </style>
</head>
<body>
    <div class="user-menu">
        <div class="left"><a href="../../index.php"><img id="logo" src="../../img/logo.png" alt="logo"></a></div>
        <div class="right">
            <a href="dashboard.php">Mano Paskyra</a>
            <a href="../naujas_skelbimas.php">Pridėti skelbimą</a>
            <a href="../../phpScript/logout.php" class="logout-btn">Atsijungti</a>
        </div>
    </div>
    <div class="container">
        <h1>Redaguoti Skelbimą</h1>
        <a href="mano_skelbimai.php" class="back-link" style="margin-bottom: 20px; display: inline-block;">&larr; Grįžti</a>
        
        <?php if (!empty($page_errors)): ?>
            <div class="error-message"><strong>Klaida:</strong><ul><?php foreach ($page_errors as $err): ?><li><?= htmlspecialchars($err) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <?php if ($success_message): ?><div class="success-message"><p><?= htmlspecialchars($success_message) ?></p></div><?php endif; ?>

        <form id="edit-listing-form" method="post" action="redaguoti_skelbima.php?id=<?= $listing_id ?>" enctype="multipart/form-data">
            <div class="form-section">
                <h2>Pagrindinė informacija</h2>
                <div class="form-group">
                    <label for="vehicle_type_id">Tipas:</label>
                    <select id="vehicle_type_id" name="vehicle_type_id" required>
                        <?php if ($vehicle_types_query_result): while ($type = $vehicle_types_query_result->fetch_assoc()): ?>
                            <option value="<?= $type['id'] ?>" <?= ($form_data['vehicle_type_id'] == $type['id']) ? 'selected' : '' ?>><?= htmlspecialchars($type['name']) ?></option>
                        <?php endwhile; @$vehicle_types_query_result->data_seek(0); endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="make_id">Markė:</label>
                    <select id="make_id" name="make_id" required>
                         <?php if ($makes_query_result): while ($make = $makes_query_result->fetch_assoc()): ?>
                            <option value="<?= $make['id'] ?>" <?= ($form_data['make_id'] == $make['id']) ? 'selected' : '' ?>><?= htmlspecialchars($make['name']) ?></option>
                        <?php endwhile; @$makes_query_result->data_seek(0); endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="model_id">Modelis:</label>
                    <select id="model_id" name="model_id" required <?= empty($form_data['make_id']) ? 'disabled' : '' ?>>
                        <?php if ($models_query_result): while ($model_row = $models_query_result->fetch_assoc()): ?>
                            <option value="<?= $model_row['id'] ?>" <?= ($form_data['model_id'] == $model_row['id']) ? 'selected' : '' ?>><?= htmlspecialchars($model_row['name']) ?></option>
                        <?php endwhile; if(mysqli_num_rows($models_query_result) > 0) @$models_query_result->data_seek(0); endif; ?>
                    </select>
                </div>
                 <div class="form-group"><label for="year">Metai:</label><input type="number" id="year" name="year" min="1900" max="<?= intval(date('Y')) + 1 ?>" required value="<?= htmlspecialchars($form_data['year'] ?? '') ?>"></div>
                <div class="form-group"><label for="price">Kaina (€):</label><input type="number" id="price" name="price" min="0" step="0.01" required value="<?= htmlspecialchars($form_data['price'] ?? '') ?>"></div>
            </div>
            
            <div class="form-section">
                <h2>Techninės specifikacijos</h2>
                <div class="form-group"><label for="power">Galia (kW):</label><input type="number" id="power" name="power" min="1" required value="<?= htmlspecialchars($form_data['power'] ?? '') ?>"></div>
                <div class="form-group"><label for="mileage">Rida (km):</label><input type="number" id="mileage" name="mileage" min="0" required value="<?= htmlspecialchars($form_data['mileage'] ?? '') ?>"></div>
                <div class="form-group"><label for="engine_capacity">Variklio tūris (l):</label><input type="number" id="engine_capacity" name="engine_capacity" min="0.1" step="0.1" required value="<?= htmlspecialchars($form_data['engine_capacity'] ?? '') ?>"></div>
                <div class="form-group">
                    <label for="fuel_type">Kuro tipas:</label>
                    <select id="fuel_type" name="fuel_type" required>
                        <option value="benzinas" <?= ($form_data['fuel_type'] == 'benzinas') ? 'selected' : '' ?>>Benzinas</option>
                        <option value="dyzelis" <?= ($form_data['fuel_type'] == 'dyzelis') ? 'selected' : '' ?>>Dyzelis</option>
                        <option value="elektra" <?= ($form_data['fuel_type'] == 'elektra') ? 'selected' : '' ?>>Elektra</option>
                        <option value="hibridas" <?= ($form_data['fuel_type'] == 'hibridas') ? 'selected' : '' ?>>Hibridas</option>
                        <option value="dujos" <?= ($form_data['fuel_type'] == 'dujos') ? 'selected' : '' ?>>Dujos</option>
                        <option value="benzinas/dujos" <?= ($form_data['fuel_type'] == 'benzinas/dujos') ? 'selected' : '' ?>>Benzinas/dujos</option>
                    </select>
                </div>
                <div id="car-specific-fields" style="<?= ($form_data['vehicle_type_id'] != 1) ? 'display:none;' : '' ?>">
                    <div class="form-group">
                        <label for="body_type">Kėbulo tipas:</label>
                        <select id="body_type" name="body_type" <?= ($form_data['vehicle_type_id'] == 1) ? 'required' : '' ?>>
                            <option value="sedanas" <?= ($form_data['body_type'] == 'sedanas') ? 'selected' : '' ?>>Sedanas</option>
                            <option value="universalas" <?= ($form_data['body_type'] == 'universalas') ? 'selected' : '' ?>>Universalas</option>
                            <option value="hečbekas" <?= ($form_data['body_type'] == 'hečbekas') ? 'selected' : '' ?>>Hečbekas</option>
                            <option value="coupe" <?= ($form_data['body_type'] == 'coupe') ? 'selected' : '' ?>>Coupe</option>
                            <option value="visureigis" <?= ($form_data['body_type'] == 'visureigis') ? 'selected' : '' ?>>Visureigis</option>
                            <option value="vienatūris" <?= ($form_data['body_type'] == 'vienatūris') ? 'selected' : '' ?>>Vienatūris</option>
                            <option value="kabrioletas" <?= ($form_data['body_type'] == 'kabrioletas') ? 'selected' : '' ?>>Kabrioletas</option>
                            <option value="kita" <?= ($form_data['body_type'] == 'kita') ? 'selected' : '' ?>>Kita</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="transmission">Pavarų dėžė:</label>
                        <select id="transmission" name="transmission" <?= ($form_data['vehicle_type_id'] == 1) ? 'required' : '' ?>>
                            <option value="mechaninė" <?= ($form_data['transmission'] == 'mechaninė') ? 'selected' : '' ?>>Mechaninė</option>
                            <option value="automatinė" <?= ($form_data['transmission'] == 'automatinė') ? 'selected' : '' ?>>Automatinė</option>
                            <option value="robotizuota" <?= ($form_data['transmission'] == 'robotizuota') ? 'selected' : '' ?>>Robotizuota</option>
                            <option value="variatorius" <?= ($form_data['transmission'] == 'variatorius') ? 'selected' : '' ?>>Variatorius</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Kontaktinė informacija</h2>
                <div class="form-group"><label for="phone">Telefonas:</label><input type="tel" id="phone" name="phone" required value="<?= htmlspecialchars($form_data['phone'] ?? '') ?>"></div>
                <div class="form-group">
                    <label for="city">Miestas:</label>
                    <select id="city" name="city">
                        <option value="Vilnius" <?= ($form_data['city'] == 'Vilnius') ? 'selected' : '' ?>>Vilnius</option>
                        <option value="Kaunas" <?= ($form_data['city'] == 'Kaunas') ? 'selected' : '' ?>>Kaunas</option>
                        <option value="Klaipėda" <?= ($form_data['city'] == 'Klaipėda') ? 'selected' : '' ?>>Klaipėda</option>
                        <option value="Šiauliai" <?= ($form_data['city'] == 'Šiauliai') ? 'selected' : '' ?>>Šiauliai</option>
                        <option value="Panevėžys" <?= ($form_data['city'] == 'Panevėžys') ? 'selected' : '' ?>>Panevėžys</option>
                        <option value="Kita" <?= ($form_data['city'] == 'Kita') ? 'selected' : '' ?>>Kita</option>
                    </select>
                </div>                  
                <div class="form-group"><label for="vin">VIN:</label><input type="text" id="vin" name="vin" maxlength="17" value="<?= htmlspecialchars($form_data['vin'] ?? '') ?>"></div>
            </div>
            
            <div class="form-section">
                <h2>Nuotraukos</h2>
                <div class="form-group">
                    <label>Esamos nuotraukos:</label>
                    <div class="image-previews" style="flex-wrap:wrap; gap:15px; margin-bottom:15px;">
                        <?php if (!empty($existing_images)): ?>
                            <?php foreach ($existing_images as $img): ?>
                                <div class="img-preview-wrapper-existing <?= $img['is_primary'] ? 'primary' : '' ?>" style="padding:10px; border-radius:4px; text-align:center;">
                                    <img src="../../<?= htmlspecialchars($img['image_path']) ?>" alt="Skelbimo nuotrauka" style="max-width:100px; max-height:75px; display:block; margin-bottom:5px;" onerror="this.style.display='none';">
                                    <span class="primary-indicator">Pagrindinė</span>
                                    <label style="font-weight:normal; display:block; margin-bottom:5px; cursor:pointer;"><input type="radio" name="primary_image_id" value="<?= $img['id'] ?>" <?= $img['is_primary'] ? 'checked' : '' ?>> Pagrindinė</label>
                                    <label style="font-weight:normal; display:block; font-size:0.9em; cursor:pointer;"><input type="checkbox" name="delete_images[]" value="<?= $img['id'] ?>"> Pašalinti</label>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?><p>Pridėtų nuotraukų nėra.</p><?php endif; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="image_uploads_edit">Pridėti naujas nuotraukas:</label>
                    <input type="file" id="image_uploads_edit" name="image_uploads[]" multiple accept="image/jpeg,image/png,image/gif">
                </div>
                <div id="new_image_previews_container_edit" class="image-previews" style="margin-top:10px; margin-bottom:10px; min-height:50px; border:1px dashed #ccc; padding:10px; border-radius:4px;">
                    <p>Nepasirinkta naujų nuotraukų.</p>
                </div>
                <input type="hidden" name="primary_new_image_identifier_edit" id="primary_new_image_identifier_edit" value="">
                
                <div class="form-group"><label for="description">Aprašymas:</label><textarea id="description" name="description" rows="4"><?= htmlspecialchars($form_data['description'] ?? '') ?></textarea></div>
            </div>
            
            <button type="button" id="submit-edit-form-btn" class="submit-btn">Atnaujinti skelbimą</button>
        </form>
    </div>
    <script>const PHP_SCRIPTS_ROOT_PATH = '../../phpScript/';</script>
    <script src="../../js/script.js"></script>
</body>
</html>
<?php if(isset($conn) && $conn instanceof mysqli && $conn->thread_id) { @$conn->close(); } ?>