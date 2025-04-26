<?php
require_once '../phpScript/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Sukuriame uploads direktoriją jei jos nėra
$upload_dir = __DIR__ . '/uploads/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Failo įkėlimo apdorojimas
    $image_path = null;
    if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] === UPLOAD_ERR_OK) {
        $file_extension = strtolower(pathinfo($_FILES['image_upload']['name'], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (in_array($file_extension, $allowed_types)) {
            $new_filename = uniqid() . '.' . $file_extension;
            $target_file = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['image_upload']['tmp_name'], $target_file)) {
                $image_path = 'uploads/' . $new_filename;
            }
        }
    }

    // Formos duomenų apdorojimas
    $vehicle_type_id = intval($_POST['vehicle_type_id']);
    $make_id = intval($_POST['make_id']);
    $model_id = intval($_POST['model_id']);
    $year = intval($_POST['year']);
    $price = floatval($_POST['price']);
    $power = intval($_POST['power']);
    $mileage = intval($_POST['mileage']);
    $engine_capacity = floatval($_POST['engine_capacity']);
    $fuel_type = $_POST['fuel_type'];
    $body_type = ($vehicle_type_id == 1 && isset($_POST['body_type'])) ? $_POST['body_type'] : null;
    $transmission = ($vehicle_type_id == 1 && isset($_POST['transmission'])) ? $_POST['transmission'] : null;
    $phone = preg_replace('/[^0-9+]/', '', $_POST['phone']);
    $vin = isset($_POST['vin']) ? strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $_POST['vin'])) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;
    $image_url = filter_var($_POST['image_url'] ?? '', FILTER_VALIDATE_URL) ? $_POST['image_url'] : null;

    // Naudojame įkeltą nuotrauką, jei ji buvo įkelta
    $image_to_save = $image_path ?? $image_url;

    $sql = "INSERT INTO listings (
        user_id, vehicle_type_id, make_id, model_id, year, power, mileage, body_type, 
        fuel_type, price, image_url, phone, engine_capacity, 
        transmission, vin, description
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param(
            'iiiidisdssdsssss',
            $_SESSION['user_id'], $vehicle_type_id, $make_id, $model_id, $year, $power, $mileage, $body_type,
            $fuel_type, $price, $image_to_save, $phone, $engine_capacity,
            $transmission, $vin, $description
        );

        if ($stmt->execute()) {
            header('Location: skelbimas.php?id='.$stmt->insert_id);
            exit;
        } else {
            $error = 'Nepavyko išsaugoti skelbimo: ' . $stmt->error;
        }
    } else {
        $error = 'Klaida ruošiant užklausą: ' . $conn->error;
    }
}

$types = $conn->query("SELECT id, name FROM vehicle_types");
$makes = $conn->query("SELECT id, name FROM makes WHERE vehicle_type_id = 1 ORDER BY name");
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Naujas skelbimas - AutoTurgus</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="user-menu">
        <span>Sveikas, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
        <a href="naujas_skelbimas.php" class="add-listing-btn">Pridėti skelbimą</a>
        <a href="api/logout.php" class="logout-btn">Atsijungti</a>
    </div>

    <div class="container">
        <h1>Pridėti naują skelbimą</h1>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>
        
        <form id="new-listing-form" method="post" enctype="multipart/form-data">
            <div class="form-section">
                <h2>Pagrindinė informacija</h2>
                
                <div class="form-group">
                    <label for="vehicle_type_id">Transporto tipas:</label>
                    <select id="vehicle_type_id" name="vehicle_type_id" required>
                        <option value="">Pasirinkite</option>
                        <?php while ($type = $types->fetch_assoc()): ?>
                            <option value="<?= $type['id'] ?>" <?= isset($_POST['vehicle_type_id']) && $_POST['vehicle_type_id'] == $type['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="make_id">Markė:</label>
                    <select id="make_id" name="make_id" required>
                        <option value="">Pasirinkite markę</option>
                        <?php while ($make = $makes->fetch_assoc()): ?>
                            <option value="<?= $make['id'] ?>" <?= isset($_POST['make_id']) && $_POST['make_id'] == $make['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($make['name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="model_id">Modelis:</label>
                    <select id="model_id" name="model_id" required disabled>
                        <option value="">Pasirinkite modelį</option>
                        <?php if (isset($_POST['make_id'])): ?>
                            <?php 
                            $models = $conn->prepare("SELECT id, name FROM models WHERE make_id = ? ORDER BY name");
                            $models->bind_param('i', $_POST['make_id']);
                            $models->execute();
                            $model_result = $models->get_result();
                            ?>
                            <?php while ($model = $model_result->fetch_assoc()): ?>
                                <option value="<?= $model['id'] ?>" <?= isset($_POST['model_id']) && $_POST['model_id'] == $model['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($model['name']) ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="year">Gamybos metai:</label>
                    <input type="number" id="year" name="year" min="1980" max="<?= date('Y') ?>" required 
                           value="<?= isset($_POST['year']) ? htmlspecialchars($_POST['year']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="price">Kaina (€):</label>
                    <input type="number" id="price" name="price" min="0" step="100" required 
                           value="<?= isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '' ?>">
                </div>
            </div>
            
            <div class="form-section">
                <h2>Techninės specifikacijos</h2>
                
                <div class="form-group">
                    <label for="power">Galia (kW):</label>
                    <input type="number" id="power" name="power" min="0" required 
                           value="<?= isset($_POST['power']) ? htmlspecialchars($_POST['power']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="mileage">Rida (km):</label>
                    <input type="number" id="mileage" name="mileage" min="0" required 
                           value="<?= isset($_POST['mileage']) ? htmlspecialchars($_POST['mileage']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="engine_capacity">Variklio tūris (l):</label>
                    <input type="number" id="engine_capacity" name="engine_capacity" min="0" step="0.1" required 
                           value="<?= isset($_POST['engine_capacity']) ? htmlspecialchars($_POST['engine_capacity']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="fuel_type">Kuro tipas:</label>
                    <select id="fuel_type" name="fuel_type" required>
                        <option value="">Pasirinkite</option>
                        <option value="benzinas" <?= isset($_POST['fuel_type']) && $_POST['fuel_type'] == 'benzinas' ? 'selected' : '' ?>>Benzinas</option>
                        <option value="dyzelis" <?= isset($_POST['fuel_type']) && $_POST['fuel_type'] == 'dyzelis' ? 'selected' : '' ?>>Dyzelis</option>
                        <option value="elektra" <?= isset($_POST['fuel_type']) && $_POST['fuel_type'] == 'elektra' ? 'selected' : '' ?>>Elektra</option>
                        <option value="hibridas" <?= isset($_POST['fuel_type']) && $_POST['fuel_type'] == 'hibridas' ? 'selected' : '' ?>>Hibridas</option>
                        <option value="dujos" <?= isset($_POST['fuel_type']) && $_POST['fuel_type'] == 'dujos' ? 'selected' : '' ?>>Dujos</option>
                        <option value="benzinas/dujos" <?= isset($_POST['fuel_type']) && $_POST['fuel_type'] == 'benzinas/dujos' ? 'selected' : '' ?>>Benzinas/dujos</option>
                    </select>
                </div>
                
                <div id="car-specific-fields">
                    <div class="form-group">
                        <label for="body_type">Kėbulo tipas:</label>
                        <select id="body_type" name="body_type">
                            <option value="">Pasirinkite</option>
                            <option value="sedanas" <?= isset($_POST['body_type']) && $_POST['body_type'] == 'sedanas' ? 'selected' : '' ?>>Sedanas</option>
                            <option value="universalas" <?= isset($_POST['body_type']) && $_POST['body_type'] == 'universalas' ? 'selected' : '' ?>>Universalas</option>
                            <option value="hečbekas" <?= isset($_POST['body_type']) && $_POST['body_type'] == 'hečbekas' ? 'selected' : '' ?>>Hečbekas</option>
                            <option value="coupe" <?= isset($_POST['body_type']) && $_POST['body_type'] == 'coupe' ? 'selected' : '' ?>>Coupe</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="transmission">Pavarų dėžė:</label>
                        <select id="transmission" name="transmission">
                            <option value="">Pasirinkite</option>
                            <option value="mechaninė" <?= isset($_POST['transmission']) && $_POST['transmission'] == 'mechaninė' ? 'selected' : '' ?>>Mechaninė</option>
                            <option value="automatinė" <?= isset($_POST['transmission']) && $_POST['transmission'] == 'automatinė' ? 'selected' : '' ?>>Automatinė</option>
                            <option value="robotizuota" <?= isset($_POST['transmission']) && $_POST['transmission'] == 'robotizuota' ? 'selected' : '' ?>>Robotizuota</option>
                            <option value="variatorius" <?= isset($_POST['transmission']) && $_POST['transmission'] == 'variatorius' ? 'selected' : '' ?>>Variatorius</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h2>Kontaktinė informacija</h2>
                
                <div class="form-group">
                    <label for="phone">Telefono numeris:</label>
                    <input type="tel" id="phone" name="phone" required 
                           value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="vin">VIN kodas (neprivaloma):</label>
                    <input type="text" id="vin" name="vin" maxlength="17" 
                           value="<?= isset($_POST['vin']) ? htmlspecialchars($_POST['vin']) : '' ?>">
                </div>
            </div>
            
            <div class="form-section">
                <h2>Papildoma informacija</h2>
                
                <div class="form-group">
                    <label for="image_upload">Nuotrauka:</label>
                    <input type="file" id="image_upload" name="image_upload" accept="image/*">
                    <small class="form-text text-muted">Leidžiami formatai: JPG, JPEG, PNG, GIF (iki 5MB)</small>
                </div>
                
                <div class="form-group">
                    <label for="image_url">Arba nuotraukos URL:</label>
                    <input type="url" id="image_url" name="image_url" 
                           value="<?= isset($_POST['image_url']) ? htmlspecialchars($_POST['image_url']) : '' ?>">
                </div>
                
                <div class="form-group">
                    <label for="description">Aprašymas:</label>
                    <textarea id="description" name="description" rows="6"><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '' ?></textarea>
                </div>
            </div>
            
            <button type="submit" class="submit-btn">Paskelbti skelbimą</button>
        </form>
    </div>

    <script src="js/script.js"></script>
</body>
</html>

<?php
$conn->close();
?>