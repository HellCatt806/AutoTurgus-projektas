<?php
require_once '../phpScript/config.php';
require_once '../phpScript/funkc.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit;
}

$page_errors = [];
$form_data = [ 
    'vehicle_type_id' => '1', 
    'make_id' => '',
    'model_id' => '',
    'year' => date('Y'),
    'price' => '',
    'power' => '',
    'mileage' => '',
    'engine_capacity' => '',
    'fuel_type' => '',
    'body_type' => '',
    'transmission' => '',
    'phone' => '',
    'city' => '',
    'vin' => '',
    'description' => ''
];

$upload_dir_absolute = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR; //

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form_data as $key => $value) {
        if (isset($_POST[$key])) {
            $form_data[$key] = $_POST[$key];
        }
    }

    $uploaded_image_paths = [];
    $primary_image_path_for_listing_table = null;
    $selected_primary_image_index = isset($_POST['primary_image_index']) ? intval($_POST['primary_image_index']) : 0;

    if (isset($_FILES['image_uploads']) && is_array($_FILES['image_uploads']['name']) && !empty(array_filter($_FILES['image_uploads']['name']))) {
        $nonEmptyFiles = 0;
        foreach ($_FILES['image_uploads']['error'] as $error) {
            if ($error !== UPLOAD_ERR_NO_FILE) {
                $nonEmptyFiles++;
            }
        }

        if ($nonEmptyFiles > 0) {
            $image_upload_results = handleMultipleImageUploads($_FILES['image_uploads'], $upload_dir_absolute);

            if (!empty($image_upload_results['errors'])) {
                foreach ($image_upload_results['errors'] as $error) {
                    $page_errors[] = $error;
                }
            }
            if (!empty($image_upload_results['paths'])) {
                $uploaded_image_paths = $image_upload_results['paths'];
                if (count($uploaded_image_paths) > 0) {
                    if ($selected_primary_image_index >= 0 && $selected_primary_image_index < count($uploaded_image_paths)) {
                        $primary_image_path_for_listing_table = $uploaded_image_paths[$selected_primary_image_index];
                    } else {
                        $primary_image_path_for_listing_table = $uploaded_image_paths[0];
                        $selected_primary_image_index = 0;
                    }
                }
            }
        }
    }
    $validation_data_input = $form_data;
    
    $is_car = (isset($form_data['vehicle_type_id']) && $form_data['vehicle_type_id'] == 1);
    $validation_errors = validateListingData($validation_data_input, $is_car); //
    if (!empty($validation_errors)) {
        $page_errors = array_merge($page_errors, $validation_errors);
    }

    if (empty($page_errors)) {
        $user_id_sql = intval($_SESSION['user_id']);
        $vehicle_type_id_sql = intval($form_data['vehicle_type_id']);
        $make_id_sql = intval($form_data['make_id']);
        $model_id_sql = intval($form_data['model_id']);
        $year_sql = intval($form_data['year']);
        $power_sql = intval($form_data['power']);
        $mileage_raw = $form_data['mileage'];
        $mileage_sql = ($mileage_raw === '' || $mileage_raw === null || !is_numeric($mileage_raw)) ? "NULL" : intval($mileage_raw);
        $price_sql = floatval($form_data['price']);
        $engine_capacity_sql = floatval($form_data['engine_capacity']);
        $fuel_type_sql = "'" . $conn->real_escape_string((string)$form_data['fuel_type']) . "'";
        $body_type_raw = ($vehicle_type_id_sql == 1 && isset($form_data['body_type'])) ? trim($form_data['body_type']) : '';
        $body_type_sql = empty($body_type_raw) ? "NULL" : "'" . $conn->real_escape_string((string)$body_type_raw) . "'";
        $transmission_raw = ($vehicle_type_id_sql == 1 && isset($form_data['transmission'])) ? trim($form_data['transmission']) : '';
        $transmission_sql = empty($transmission_raw) ? "NULL" : "'" . $conn->real_escape_string((string)$transmission_raw) . "'";
        $phone_sql = "'" . $conn->real_escape_string(preg_replace('/[^0-9+]/', '', $form_data['phone'])) . "'";
        $city_raw = isset($form_data['city']) ? trim($form_data['city']) : '';
        $city_sql = empty($city_raw) ? "NULL" : "'" . $conn->real_escape_string((string)$city_raw) . "'";
        $vin_raw = isset($form_data['vin']) ? strtoupper(preg_replace('/[^A-Za-z0-9]/', '', trim($form_data['vin']))) : '';
        $vin_sql = empty($vin_raw) ? "NULL" : "'" . $conn->real_escape_string((string)$vin_raw) . "'";
        $description_raw = isset($form_data['description']) ? trim($form_data['description']) : '';
        $description_sql = empty($description_raw) ? "NULL" : "'" . $conn->real_escape_string((string)$description_raw) . "'";
        $primary_image_for_listings_sql = ($primary_image_path_for_listing_table === null || trim((string)$primary_image_path_for_listing_table) === '') 
                                          ? "NULL" 
                                          : "'" . $conn->real_escape_string((string)$primary_image_path_for_listing_table) . "'";

        $sql_direct_insert_listing = "INSERT INTO listings (
            user_id, vehicle_type_id, make_id, model_id, year, power, mileage, body_type, 
            fuel_type, price, image_url, phone, engine_capacity, 
            transmission, vin, description, city
        ) VALUES (
            $user_id_sql, $vehicle_type_id_sql, $make_id_sql, $model_id_sql, $year_sql, $power_sql, $mileage_sql,
            $body_type_sql, $fuel_type_sql, $price_sql, $primary_image_for_listings_sql, 
            $phone_sql, $engine_capacity_sql, $transmission_sql, $vin_sql, $description_sql, $city_sql
        )";

        if ($conn->query($sql_direct_insert_listing) === TRUE) {
            $new_listing_id = $conn->insert_id;

           if (!empty($uploaded_image_paths) && $new_listing_id > 0) {
                $stmt_insert_image = $conn->prepare("INSERT INTO listing_images (listing_id, image_path, is_primary) VALUES (?, ?, ?)");
                if ($stmt_insert_image) {
                    foreach ($uploaded_image_paths as $index => $image_path) {
                        $is_primary_flag = ($index === $selected_primary_image_index) ? 1 : 0;
                        $stmt_insert_image->bind_param("isi", $new_listing_id, $image_path, $is_primary_flag);
                        if (!$stmt_insert_image->execute()) {
                            $page_errors[] = "Klaida saugant nuotrauką: " . htmlspecialchars($image_path) . " - " . $stmt_insert_image->error;
                        }
                    }
                    $stmt_insert_image->close();
                } else {
                     $page_errors[] = "Sistemos klaida ruošiant nuotraukų įrašymą: " . $conn->error;
                }
            }
            
            if (empty($page_errors)) {
                $_SESSION['success_message'] = 'Skelbimas sėkmingai pridėtas!';
                if (isset($conn) && $conn instanceof mysqli && $conn->thread_id) { $conn->close(); }
                header('Location: skelbimas.php?id='.$new_listing_id);
                exit;
            }
        } else {
            $page_errors[] = 'Nepavyko išsaugoti skelbimo. Klaida: ' . $conn->error;
             error_log("Naujas skelbimas - SQL Insert Klaida: " . $conn->error . " Query: " . $sql_direct_insert_listing);
        }
    }
}

$current_vehicle_type_id_for_makes = isset($form_data['vehicle_type_id']) ? intval($form_data['vehicle_type_id']) : 1;
$vehicle_types_query_result = null;
$makes_query_result = null;
$models_query_result = null;

if ($conn) {
    $vehicle_types_query_result = $conn->query("SELECT id, name FROM vehicle_types ORDER BY id");
    if ($vehicle_types_query_result === false) { $page_errors[] = "Klaida gaunant transporto tipus: " . $conn->error; }

    $makes_query_result = $conn->query("SELECT id, name FROM makes WHERE vehicle_type_id = ".$current_vehicle_type_id_for_makes." ORDER BY name"); 
    if ($makes_query_result === false) { $page_errors[] = "Klaida gaunant markes: " . $conn->error; }


    if (!empty($form_data['make_id'])) {
        $stmt_models_form = $conn->prepare("SELECT id, name FROM models WHERE make_id = ? ORDER BY name");
        if ($stmt_models_form) {
            $current_make_id_for_models = intval($form_data['make_id']);
            $stmt_models_form->bind_param('i', $current_make_id_for_models);
            if ($stmt_models_form->execute()) {
                $models_query_result = $stmt_models_form->get_result();
            } else {
                 $page_errors[] = "Klaida gaunant modelius: " . $stmt_models_form->error;
            }
            $stmt_models_form->close();
        } else {
             $page_errors[] = "Klaida ruošiant modelių užklausą: " . $conn->error;
        }
    }
} else {
    if(empty($page_errors)) $page_errors[] = "Klaida jungiantis prie duomenų bazės.";
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Naujas skelbimas - AutoTurgus</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/naujas_skelbimas.css">
</head>
<body>
    <div class="user-menu">
            <div class="left">
                <a href="../index.php"><img id="logo" src="../img/logo.png" alt="logo"></a>
            </div>
            <div class="right">
                <a href="paskyra/dashboard.php">Mano Paskyra</a>
                <a href="naujas_skelbimas.php" class="add-listing-btn active">Pridėti skelbimą</a>
                <a href="../phpScript/logout.php" class="logout-btn">Atsijungti</a>
            </div>
        </div>

    <div class="container">
        <h1>Pridėti naują skelbimą</h1>
        
        <?php if (!empty($page_errors)): ?>
            <div class="error-message">
                <strong>Įvyko klaida (-os):</strong>
                <ul>
                    <?php foreach ($page_errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form id="new-listing-form" method="post" action="naujas_skelbimas.php" enctype="multipart/form-data">
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
                        <?php if ($models_query_result && $models_query_result->num_rows > 0): while ($model_row = $models_query_result->fetch_assoc()): ?>
                            <option value="<?= $model_row['id'] ?>" <?= (isset($form_data['model_id']) && $form_data['model_id'] == $model_row['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($model_row['name']) ?>
                            </option>
                        <?php endwhile; @$models_query_result->data_seek(0); endif; ?>
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
                <h2>Papildoma informacija</h2>
                
                <div class="form-group">
                    <label for="image_upload">Nuotrauka:</label>
                    <input type="file" id="image_uploads" name="image_uploads[]" multiple accept="image/jpeg,image/png,image/gif">
                    <div id="image_previews_container" class="image-previews">
                        </div>
                    <input type="hidden" name="primary_image_index" id="primary_image_index" value="0">
                    <small class="form-text text-muted">Pasirinkite pagrindinę nuotrauką</small>
                    <small class="form-text text-muted">Leidžiami formatai: JPG, JPEG, PNG, GIF (iki 5MB)</small>
                </div>
                
                <div class="form-group">
                    <label for="description">Aprašymas (neprivaloma):</label>
                    <textarea id="description" name="description" rows="6"><?= htmlspecialchars((string)($form_data['description'] ?? '')) ?></textarea>
                </div>
            </div>
            
            <button type="submit" class="submit-btn">Paskelbti skelbimą</button>
        </form>
    </div>
    <script>
    const PHP_SCRIPTS_ROOT_PATH = '../phpScript/';
</script>
    <script src="../js/script.js"></script>
</body>
</html>
<?php if(isset($conn) && $conn instanceof mysqli && $conn->thread_id) { @$conn->close(); } ?>