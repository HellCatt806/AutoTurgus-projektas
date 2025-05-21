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
$old_image_url = $form_data['image_url'];

$existing_images = [];
$stmt_images = $conn->prepare("SELECT id, image_path, is_primary FROM listing_images WHERE listing_id = ? ORDER BY is_primary DESC, id ASC");
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
    foreach ($form_data as $key => $value) {
        if (isset($_POST[$key]) && $key !== 'image_url') {
            $form_data[$key] = $_POST[$key];
        }
    }
    $form_data['city'] = $_POST['city'] ?? ($form_data['city'] ?? ''); 
    $form_data['body_type'] = $_POST['body_type'] ?? ($form_data['body_type'] ?? '');
    $form_data['transmission'] = $_POST['transmission'] ?? ($form_data['transmission'] ?? '');

    $newly_uploaded_paths = [];
    $new_primary_image_path_for_listing = $old_image_url;

    if (isset($_FILES['image_uploads']) && !empty($_FILES['image_uploads']['name'][0])) {
        $image_upload_results = handleMultipleImageUploads($_FILES['image_uploads'], $upload_dir_absolute);

        if (!empty($image_upload_results['errors'])) {
            $page_errors = array_merge($page_errors, $image_upload_results['errors']);
        }
        
        if (!empty($image_upload_results['paths'])) {
            $newly_uploaded_paths = $image_upload_results['paths'];
            if (count($newly_uploaded_paths) > 0) {
                $new_primary_image_path_for_listing = $newly_uploaded_paths[0];

                if (!empty($existing_images)) {
                    $stmt_delete_old_img_db = $conn->prepare("DELETE FROM listing_images WHERE listing_id = ?");
                    if ($stmt_delete_old_img_db) {
                        $stmt_delete_old_img_db->bind_param('i', $listing_id);
                        $stmt_delete_old_img_db->execute();
                        $stmt_delete_old_img_db->close();
                    }
                    foreach ($existing_images as $old_img_data) {
                        if (strpos($old_img_data['image_path'], 'uploads/') === 0) {
                            $old_img_file_on_server = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . $old_img_data['image_path'];
                            if (file_exists($old_img_file_on_server)) {
                                @unlink($old_img_file_on_server);
                            }
                        }
                    }
                }
                $existing_images = [];
            }
        }
    }

    $validation_data_input = $_POST;
    unset($validation_data_input['image_url']); 

    $is_car = (isset($_POST['vehicle_type_id']) && $_POST['vehicle_type_id'] == 1);
    $validation_errors = validateListingData($validation_data_input, $is_car);
    if (!empty($validation_errors)) {
        $page_errors = array_merge($page_errors, $validation_errors);
    }

    if (empty($page_errors)) {
        $vt_id_sql = intval($_POST['vehicle_type_id']);
        $mk_id_sql = intval($_POST['make_id']);
        $md_id_sql = intval($_POST['model_id']);
        $year_sql = intval($_POST['year']);
        $power_sql = intval($_POST['power']);
        $mileage_raw = $_POST['mileage'];
        $mileage_sql = ($mileage_raw === '' || $mileage_raw === null || !is_numeric($mileage_raw)) ? "NULL" : intval($mileage_raw);
        $price_sql = floatval($_POST['price']);
        $engine_capacity_sql = floatval($_POST['engine_capacity']);
        $fuel_type_sql = "'" . $conn->real_escape_string((string)$_POST['fuel_type']) . "'";
        $body_type_raw = ($vt_id_sql == 1 && isset($_POST['body_type'])) ? trim($_POST['body_type']) : '';
        $body_type_sql = empty($body_type_raw) ? "NULL" : "'" . $conn->real_escape_string($body_type_raw) . "'";
        $transmission_raw = ($vt_id_sql == 1 && isset($_POST['transmission'])) ? trim($_POST['transmission']) : '';
        $transmission_sql = empty($transmission_raw) ? "NULL" : "'" . $conn->real_escape_string($transmission_raw) . "'";
        $phone_sql = "'" . $conn->real_escape_string(preg_replace('/[^0-9+]/', '', $_POST['phone'])) . "'";
        $city_raw = isset($_POST['city']) ? trim($_POST['city']) : '';
        $city_sql = empty($city_raw) ? "NULL" : "'" . $conn->real_escape_string($city_raw) . "'";
        $vin_raw_val = isset($_POST['vin']) ? strtoupper(preg_replace('/[^A-Za-z0-9]/', '', trim($_POST['vin']))) : '';
        $vin_sql = empty($vin_raw_val) ? "NULL" : "'" . $conn->real_escape_string($vin_raw_val) . "'";
        $description_raw_val = isset($_POST['description']) ? trim($_POST['description']) : '';
        $description_sql = empty($description_raw_val) ? "NULL" : "'" . $conn->real_escape_string($description_raw_val) . "'";
        
        $primary_image_for_listings_sql_direct = ($new_primary_image_path_for_listing === null || trim((string)$new_primary_image_path_for_listing) === '') 
                                             ? "NULL" 
                                             : "'" . $conn->real_escape_string((string)$new_primary_image_path_for_listing) . "'";
        
        $update_sql_direct = "UPDATE listings SET 
            vehicle_type_id = $vt_id_sql, make_id = $mk_id_sql, model_id = $md_id_sql, year = $year_sql, 
            power = $power_sql, mileage = $mileage_sql, body_type = $body_type_sql, fuel_type = $fuel_type_sql,
            price = $price_sql, image_url = $primary_image_for_listings_sql_direct, phone = $phone_sql, city = $city_sql, 
            engine_capacity = $engine_capacity_sql, transmission = $transmission_sql, vin = $vin_sql,
            description = $description_sql
        WHERE id = $listing_id AND user_id = $user_id";
        
        if ($conn->query($update_sql_direct) === TRUE) {
            if (!empty($newly_uploaded_paths)) {
                $stmt_insert_new_image = $conn->prepare("INSERT INTO listing_images (listing_id, image_path, is_primary) VALUES (?, ?, ?)");
                if ($stmt_insert_new_image) {
                    foreach ($newly_uploaded_paths as $index => $image_path) {
                        $is_primary_flag = ($index === 0) ? 1 : 0;
                        $stmt_insert_new_image->bind_param("isi", $listing_id, $image_path, $is_primary_flag);
                        $stmt_insert_new_image->execute();
                    }
                    $stmt_insert_new_image->close();
                }
            }
            
            if (empty($page_errors)) {
                $_SESSION['success_message'] = 'Skelbimas sėkmingai atnaujintas!';
                header('Location: ../skelbimas.php?id='.$listing_id);
                exit;
            }
        } else {
            $page_errors[] = 'Nepavyko atnaujinti pagrindinių skelbimo duomenų: ' . $conn->error;
        }
    }
    
    if (isset($new_primary_image_path_for_listing)) {
        $form_data['image_url'] = $new_primary_image_path_for_listing;
    }
    if(!empty($newly_uploaded_paths) && !empty($page_errors)) {
        $existing_images = [];
        foreach($newly_uploaded_paths as $idx => $path) {
            $existing_images[] = ['image_path' => $path, 'is_primary' => ($idx === 0)];
        }
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
</head>
<body>
    <div class="user-menu">
        <div class="left">
            <a href="../../index.php"><img id="logo" src="../../img/logo.svg" alt="logo"></a>
        </div>
        <div class="right">
            <a href="dashboard.php">Mano Paskyra</a>
            <a href="../naujas_skelbimas.php">Pridėti skelbimą</a>
            <a href="../../phpScript/logout.php" class="logout-btn">Atsijungti</a>
        </div>
    </div>

    <div class="container">
        <h1>Redaguoti Skelbimą</h1>
        <a href="mano_skelbimai.php" class="back-link" style="margin-bottom: 20px; display: inline-block;">&larr; Grįžti į mano skelbimus</a>
        
        <?php if (!empty($page_errors)): ?>
            <div class="error-message">
                <strong>Įvyko klaida:</strong>
                <ul>
                    <?php foreach ($page_errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="success-message">
                <p><?= htmlspecialchars($success_message) ?></p>
            </div>
        <?php endif; ?>

        <form id="new-listing-form" method="post" action="redaguoti_skelbima.php?id=<?= $listing_id ?>" enctype="multipart/form-data">
            <div class="form-section">
                <h2>Pagrindinė informacija</h2>
                <div class="form-group">
                    <label for="vehicle_type_id">Transporto tipas:</label>
                    <select id="vehicle_type_id" name="vehicle_type_id" required>
                        <option value="">Pasirinkite</option>
                        <?php if ($vehicle_types_query_result && $vehicle_types_query_result->num_rows > 0): while ($type = $vehicle_types_query_result->fetch_assoc()): ?>
                            <option value="<?= $type['id'] ?>" <?= (isset($form_data['vehicle_type_id']) && $form_data['vehicle_type_id'] == $type['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['name']) ?>
                            </option>
                        <?php endwhile; @$vehicle_types_query_result->data_seek(0); endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="make_id">Markė:</label>
                    <select id="make_id" name="make_id" required>
                        <option value="">Pasirinkite markę</option>
                        <?php if ($makes_query_result && $makes_query_result->num_rows > 0): while ($make = $makes_query_result->fetch_assoc()): ?>
                            <option value="<?= $make['id'] ?>" <?= (isset($form_data['make_id']) && $form_data['make_id'] == $make['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($make['name']) ?>
                            </option>
                        <?php endwhile; @$makes_query_result->data_seek(0); endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="model_id">Modelis:</label>
                    <select id="model_id" name="model_id" required <?= empty($form_data['make_id']) ? 'disabled' : '' ?>>
                        <option value="">Pasirinkite modelį</option>
                        <?php
                        if ($models_query_result && $models_query_result->num_rows > 0):
                            while ($model_row = $models_query_result->fetch_assoc()):
                        ?>
                                <option value="<?= $model_row['id'] ?>" <?= (isset($form_data['model_id']) && $form_data['model_id'] == $model_row['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($model_row['name']) ?>
                                </option>
                        <?php
                            endwhile;
                        endif;
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="year">Gamybos metai:</label>
                    <input type="number" id="year" name="year" min="1900" max="<?= intval(date('Y')) + 1 ?>" required 
                           value="<?= htmlspecialchars((string)($form_data['year'] ?? date('Y'))) ?>">
                </div>
                <div class="form-group">
                    <label for="price">Kaina (€):</label>
                    <input type="number" id="price" name="price" min="0" step="0.01" required 
                           value="<?= htmlspecialchars((string)($form_data['price'] ?? '')) ?>">
                </div>
            </div>
            
            <div class="form-section">
                <h2>Techninės specifikacijos</h2>
                <div class="form-group">
                    <label for="power">Galia (kW):</label>
                    <input type="number" id="power" name="power" min="1" required 
                           value="<?= htmlspecialchars((string)($form_data['power'] ?? '')) ?>">
                </div>
                <div class="form-group">
                    <label for="mileage">Rida (km):</label>
                    <input type="number" id="mileage" name="mileage" min="0" 
                           value="<?= htmlspecialchars((string)($form_data['mileage'] ?? '')) ?>" required>
                </div>
                <div class="form-group">
                    <label for="engine_capacity">Variklio tūris (l):</label>
                    <input type="number" id="engine_capacity" name="engine_capacity" min="0.1" step="0.1" required 
                           value="<?= htmlspecialchars((string)($form_data['engine_capacity'] ?? '')) ?>">
                </div>
                <div class="form-group">
                    <label for="fuel_type">Kuro tipas:</label>
                    <select id="fuel_type" name="fuel_type" required>
                        <option value="">Pasirinkite</option>
                        <option value="benzinas" <?= (isset($form_data['fuel_type']) && $form_data['fuel_type'] == 'benzinas') ? 'selected' : '' ?>>Benzinas</option>
                        <option value="dyzelis" <?= (isset($form_data['fuel_type']) && $form_data['fuel_type'] == 'dyzelis') ? 'selected' : '' ?>>Dyzelis</option>
                        <option value="elektra" <?= (isset($form_data['fuel_type']) && $form_data['fuel_type'] == 'elektra') ? 'selected' : '' ?>>Elektra</option>
                        <option value="hibridas" <?= (isset($form_data['fuel_type']) && $form_data['fuel_type'] == 'hibridas') ? 'selected' : '' ?>>Hibridas</option>
                        <option value="dujos" <?= (isset($form_data['fuel_type']) && $form_data['fuel_type'] == 'dujos') ? 'selected' : '' ?>>Dujos</option>
                        <option value="benzinas/dujos" <?= (isset($form_data['fuel_type']) && $form_data['fuel_type'] == 'benzinas/dujos') ? 'selected' : '' ?>>Benzinas/dujos</option>
                    </select>
                </div>
                <div id="car-specific-fields" style="<?= (isset($form_data['vehicle_type_id']) && $form_data['vehicle_type_id'] != 1) ? 'display:none;' : '' ?>">
                    <div class="form-group">
                        <label for="body_type">Kėbulo tipas:</label>
                        <select id="body_type" name="body_type" <?= (isset($form_data['vehicle_type_id']) && $form_data['vehicle_type_id'] == 1) ? 'required' : '' ?>>
                            <option value="">Pasirinkite</option>
                            <option value="sedanas" <?= (isset($form_data['body_type']) && $form_data['body_type'] == 'sedanas') ? 'selected' : '' ?>>Sedanas</option>
                            <option value="universalas" <?= (isset($form_data['body_type']) && $form_data['body_type'] == 'universalas') ? 'selected' : '' ?>>Universalas</option>
                            <option value="hečbekas" <?= (isset($form_data['body_type']) && $form_data['body_type'] == 'hečbekas') ? 'selected' : '' ?>>Hečbekas</option>
                            <option value="coupe" <?= (isset($form_data['body_type']) && $form_data['body_type'] == 'coupe') ? 'selected' : '' ?>>Coupe</option>
                            <option value="visureigis" <?= (isset($form_data['body_type']) && $form_data['body_type'] == 'visureigis') ? 'selected' : '' ?>>Visureigis</option>
                            <option value="vienatūris" <?= (isset($form_data['body_type']) && $form_data['body_type'] == 'vienatūris') ? 'selected' : '' ?>>Vienatūris</option>
                            <option value="kabrioletas" <?= (isset($form_data['body_type']) && $form_data['body_type'] == 'kabrioletas') ? 'selected' : '' ?>>Kabrioletas</option>
                            <option value="kita" <?= (isset($form_data['body_type']) && $form_data['body_type'] == 'kita') ? 'selected' : '' ?>>Kita</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="transmission">Pavarų dėžė:</label>
                        <select id="transmission" name="transmission" <?= (isset($form_data['vehicle_type_id']) && $form_data['vehicle_type_id'] == 1) ? 'required' : '' ?>>
                            <option value="">Pasirinkite</option>
                            <option value="mechaninė" <?= (isset($form_data['transmission']) && $form_data['transmission'] == 'mechaninė') ? 'selected' : '' ?>>Mechaninė</option>
                            <option value="automatinė" <?= (isset($form_data['transmission']) && $form_data['transmission'] == 'automatinė') ? 'selected' : '' ?>>Automatinė</option>
                            <option value="robotizuota" <?= (isset($form_data['transmission']) && $form_data['transmission'] == 'robotizuota') ? 'selected' : '' ?>>Robotizuota</option>
                            <option value="variatorius" <?= (isset($form_data['transmission']) && $form_data['transmission'] == 'variatorius') ? 'selected' : '' ?>>Variatorius</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Kontaktinė informacija</h2>
                <div class="form-group">
                    <label for="phone">Telefono numeris:</label>
                    <input type="tel" id="phone" name="phone" required placeholder="+370xxxxxxxx"
                           value="<?= htmlspecialchars((string)($form_data['phone'] ?? '')) ?>">
                </div>

                <div class="form-group">
                    <label for="city">Miestas:</label>
                    <select id="city" name="city">
                        <option value="">Pasirinkite miestą</option>
                        <option value="Vilnius" <?= (isset($form_data['city']) && $form_data['city'] == 'Vilnius') ? 'selected' : '' ?>>Vilnius</option>
                        <option value="Kaunas" <?= (isset($form_data['city']) && $form_data['city'] == 'Kaunas') ? 'selected' : '' ?>>Kaunas</option>
                        <option value="Klaipėda" <?= (isset($form_data['city']) && $form_data['city'] == 'Klaipėda') ? 'selected' : '' ?>>Klaipėda</option>
                        <option value="Šiauliai" <?= (isset($form_data['city']) && $form_data['city'] == 'Šiauliai') ? 'selected' : '' ?>>Šiauliai</option>
                        <option value="Panevėžys" <?= (isset($form_data['city']) && $form_data['city'] == 'Panevėžys') ? 'selected' : '' ?>>Panevėžys</option>
                        <option value="Alytus" <?= (isset($form_data['city']) && $form_data['city'] == 'Alytus') ? 'selected' : '' ?>>Alytus</option>
                        <option value="Marijampolė" <?= (isset($form_data['city']) && $form_data['city'] == 'Marijampolė') ? 'selected' : '' ?>>Marijampolė</option>
                        <option value="Mažeikiai" <?= (isset($form_data['city']) && $form_data['city'] == 'Mažeikiai') ? 'selected' : '' ?>>Mažeikiai</option>
                        <option value="Jonava" <?= (isset($form_data['city']) && $form_data['city'] == 'Jonava') ? 'selected' : '' ?>>Jonava</option>
                        <option value="Utena" <?= (isset($form_data['city']) && $form_data['city'] == 'Utena') ? 'selected' : '' ?>>Utena</option>
                        <option value="Kėdainiai" <?= (isset($form_data['city']) && $form_data['city'] == 'Kėdainiai') ? 'selected' : '' ?>>Kėdainiai</option>
                        <option value="Telšiai" <?= (isset($form_data['city']) && $form_data['city'] == 'Telšiai') ? 'selected' : '' ?>>Telšiai</option>
                        <option value="Tauragė" <?= (isset($form_data['city']) && $form_data['city'] == 'Tauragė') ? 'selected' : '' ?>>Tauragė</option>
                        <option value="Ukmergė" <?= (isset($form_data['city']) && $form_data['city'] == 'Ukmergė') ? 'selected' : '' ?>>Ukmergė</option>
                        <option value="Visaginas" <?= (isset($form_data['city']) && $form_data['city'] == 'Visaginas') ? 'selected' : '' ?>>Visaginas</option>
                        <option value="Kita" <?= (isset($form_data['city']) && $form_data['city'] == 'Kita') ? 'selected' : '' ?>>Kita (nurodyti aprašyme)</option>
                    </select>
                </div>                  

                <div class="form-group">
                    <label for="vin">VIN kodas (neprivaloma, 17 simbolių):</label>
                    <input type="text" id="vin" name="vin" maxlength="17" pattern="[A-HJ-NPR-Z0-9]{17}" title="VIN kodas turi būti 17 simbolių ilgio ir gali turėti tik didžiąsias raides (išskyrus I, O, Q) ir skaičius."
                           value="<?= htmlspecialchars((string)($form_data['vin'] ?? '')) ?>">
                </div>
            </div>
            
            <div class="form-section">
                <h2>Nuotraukos</h2>
                <div class="form-group">
                    <label>Esamos nuotraukos:</label>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">
                        <?php if (!empty($existing_images)): ?>
                            <?php foreach ($existing_images as $img): ?>
                                <div style="border: 1px solid #ddd; padding: 5px; border-radius: 4px; text-align: center;">
                                    <img src="../../<?= htmlspecialchars($img['image_path']) ?>" alt="Skelbimo nuotrauka" style="max-width: 100px; max-height: 75px; display: block; margin-bottom: 5px;" onerror="this.style.display='none'; this.parentElement.innerHTML+='<small>Klaida</small>';">
                                    <?php if ($img['is_primary']): ?>
                                        <small style="color: green; font-weight: bold;">(Pagrindinė)</small>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Pridėtų nuotraukų nėra.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="image_uploads">Įkelti naujas nuotraukas:</label>
                    <input type="file" id="image_uploads" name="image_uploads[]" multiple accept="image/jpeg,image/png,image/gif">
                    <small class="form-text text-muted">Pasirinkus ir įkėlus naujas nuotraukas, visos senosios bus pašalintos ir pakeistos naujomis. Leidžiami formatai: JPG, JPEG, PNG, GIF (iki 5MB).</small>
                </div>
                <div class="form-group">
                    <label for="description">Aprašymas (neprivaloma):</label>
                    <textarea id="description" name="description" rows="6"><?= htmlspecialchars((string)($form_data['description'] ?? '')) ?></textarea>
                </div>
            </div>
            
            <button type="submit" class="submit-btn">Atnaujinti skelbimą</button>
        </form>
    </div>
    <script>
        const PHP_SCRIPTS_ROOT_PATH = '../../phpScript/';
    </script>
    <script src="../../js/script.js"></script>
</body>
</html>
<?php if(isset($conn) && $conn instanceof mysqli && $conn->thread_id) { @$conn->close(); } ?>