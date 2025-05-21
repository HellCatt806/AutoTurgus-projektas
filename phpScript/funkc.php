<?php

function validateRegistrationData(string $username, string $email, string $phone, string $password, string $confirm_password): array {
    $errors = [];
    if (empty($username) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        $errors[] = 'Prašome užpildyti visus laukus';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) {
        $errors[] = 'Neteisingas el. pašto formatas';
    }
    if (strlen(preg_replace('/[^0-9]/', '', $phone)) < 7 && !empty($phone)) {
        $errors[] = 'Telefono numeris atrodo per trumpas';
    }
    if (strlen($password) < 8 && !empty($password)) {
        $errors[] = 'Slaptažodis turi būti bent 8 simbolių ilgio';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Slaptažodžiai nesutampa';
    }
    return $errors;
}

function userExists(mysqli $conn, string $email, string $username): bool {
    $sql = "SELECT id FROM users WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("DB prepare error in userExists: " . $conn->error);
        return true; 
    }
    $stmt->bind_param('ss', $email, $username);
    if (!$stmt->execute()){
        error_log("DB execute error in userExists: " . $stmt->error);
        $stmt->close();
        return true;
    }
    $result = $stmt->get_result();
    $stmt->close();
    return $result->num_rows > 0;
}

function createUser(mysqli $conn, string $username, string $hashed_password, string $email, string $phone): bool {
    $sql = "INSERT INTO users (username, password, email, phone) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("DB prepare error in createUser: " . $conn->error);
        return false;
    }
    $stmt->bind_param('ssss', $username, $hashed_password, $email, $phone);
    $success = $stmt->execute();
    if (!$success) {
        error_log("DB execute error in createUser: " . $stmt->error);
    }
    $stmt->close();
    return $success;
}

function sendJsonResponse(int $statusCode, array $data): void {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function validateLoginData(string $email, string $password): array {
    $errors = [];
    if (empty($email) || empty($password)) {
        $errors[] = 'Prašome užpildyti visus laukus';
    }
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Neteisingas el. pašto formatas';
    }
    return $errors;
}

function verifyUserCredentials(mysqli $conn, string $email, string $password): ?array {
    $sql = "SELECT id, username, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("DB prepare error in verifyUserCredentials: " . $conn->error);
        return null;
    }
    $stmt->bind_param('s', $email);
    if (!$stmt->execute()) {
        error_log("DB execute error in verifyUserCredentials: " . $stmt->error);
        $stmt->close();
        return null;
    }
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        $stmt->close();
        return null;
    }
    $user = $result->fetch_assoc();
    $stmt->close();
    if (password_verify($password, $user['password'])) {
        return $user;
    }
    return null;
}

function validateListingData(array $data, bool $is_car = true): array {
    $errors = [];
    $required_fields = [
        'vehicle_type_id' => 'Transporto tipas',
        'make_id' => 'Markė',
        'model_id' => 'Modelis',
        'year' => 'Gamybos metai',
        'price' => 'Kaina',
        'power' => 'Galia',
        'mileage' => 'Rida',
        'engine_capacity' => 'Variklio tūris',
        'fuel_type' => 'Kuro tipas',
        'phone' => 'Telefono numeris'
    ];

    if ($is_car) {
        $required_fields['body_type'] = 'Kėbulo tipas';
        $required_fields['transmission'] = 'Pavarų dėžė';
    }

    foreach ($required_fields as $field => $label) {
        if (empty($data[$field]) && $data[$field] !== '0') {
             if ($field === 'mileage' && isset($data[$field]) && $data[$field] === '0') {
            } elseif ($field === 'price' && isset($data[$field]) && $data[$field] === '0') {
            }
             else {
                $errors[] = "Laukas \"{$label}\" yra privalomas.";
            }
        }
    }
    
    if (!empty($data['year']) && (!is_numeric($data['year']) || $data['year'] < 1900 || $data['year'] > intval(date('Y')) + 1)) {
        $errors[] = 'Neteisingi gamybos metai.';
    }
    if (isset($data['price']) && $data['price'] !== '' && (!is_numeric($data['price']) || floatval($data['price']) < 0)) {
        $errors[] = 'Kaina turi būti teigiamas skaičius arba 0.';
    }
    if (!empty($data['power']) && (!is_numeric($data['power']) || intval($data['power']) <= 0)) {
        $errors[] = 'Galia turi būti teigiamas skaičius.';
    }
    if (isset($data['mileage']) && $data['mileage'] !== '' && (!is_numeric($data['mileage']) || intval($data['mileage']) < 0)) {
        $errors[] = 'Rida negali būti neigiama.';
    }
    if (!empty($data['engine_capacity']) && (!is_numeric($data['engine_capacity']) || floatval($data['engine_capacity']) <= 0)) {
        $errors[] = 'Variklio tūris turi būti teigiamas skaičius.';
    }
    
    if (isset($data['vin']) && !empty(trim($data['vin'])) && strlen(trim($data['vin'])) !== 17) {
        $errors[] = 'VIN kodas turi būti 17 simbolių ilgio.';
    }
    return $errors;
}

function handleImageUpload(array $file_data, string $upload_dir_absolute): string|array {
    if (isset($file_data['error']) && $file_data['error'] === UPLOAD_ERR_OK) {
        $file_extension = strtolower(pathinfo($file_data['name'], PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $max_file_size = 5 * 1024 * 1024; 

        if (!in_array($file_extension, $allowed_types)) {
            return ['error' => 'Netinkamas failo formatas. Leidžiami: JPG, JPEG, PNG, GIF.'];
        }
        if ($file_data['size'] > $max_file_size) {
            return ['error' => 'Failas per didelis. Maksimalus dydis: 5MB.'];
        }
        
        $new_filename = uniqid('img_', true) . '.' . $file_extension;
        $target_file_absolute = rtrim($upload_dir_absolute, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $new_filename;

        if (move_uploaded_file($file_data['tmp_name'], $target_file_absolute)) {

            return 'uploads/' . $new_filename; 
        } else {
            error_log("Failed to move uploaded file: " . $file_data['tmp_name'] . " to " . $target_file_absolute . ". Check permissions for " . $upload_dir_absolute);
            return ['error' => 'Nepavyko įkelti failo. Patikrinkite serverio leidimus ir kelio teisingumą.'];
        }
    } elseif (isset($file_data['error']) && $file_data['error'] !== UPLOAD_ERR_NO_FILE) {
        $upload_errors = [
            UPLOAD_ERR_INI_SIZE   => "Failas viršija upload_max_filesize direktyvą php.ini.",
            UPLOAD_ERR_FORM_SIZE  => "Failas viršija MAX_FILE_SIZE direktyvą HTML formoje.",
            UPLOAD_ERR_PARTIAL    => "Failas buvo įkeltas tik dalinai.",
            UPLOAD_ERR_CANT_WRITE => "Nepavyko įrašyti failo į diską.",
            UPLOAD_ERR_EXTENSION  => "PHP plėtinys sustabdė failo įkėlimą.",
        ];
        return ['error' => $upload_errors[$file_data['error']] ?? 'Nežinoma failo įkėlimo klaida. Kodas: '.$file_data['error']];
    }
    return ''; 
}

function handleMultipleImageUploads(array $files_array_entry, string $upload_dir_absolute): array {
    $uploaded_paths = [];
    $errors = [];
    $processed_files = [];
    if (isset($files_array_entry['name']) && is_array($files_array_entry['name'])) {
        foreach ($files_array_entry['name'] as $key => $name) {
            if ($files_array_entry['error'][$key] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            $processed_files[] = [
                'name' => $name,
                'type' => $files_array_entry['type'][$key],
                'tmp_name' => $files_array_entry['tmp_name'][$key],
                'error' => $files_array_entry['error'][$key],
                'size' => $files_array_entry['size'][$key]
            ];
        }
    } elseif (isset($files_array_entry['name']) && $files_array_entry['error'] !== UPLOAD_ERR_NO_FILE) {
         $processed_files[] = $files_array_entry;
    }

    if (empty($processed_files)) {
        return ['paths' => [], 'errors' => []];
    }
    if (!file_exists($upload_dir_absolute)) {
        if (!mkdir($upload_dir_absolute, 0755, true)) {
            $errors[] = 'Nepavyko sukurti nuotraukų saugojimo direktorijos.';
            return ['paths' => [], 'errors' => $errors];
        }
    }
    if (!is_writable($upload_dir_absolute)) {
        $errors[] = 'Uploads direktorija neturi rašymo teisių.';
        return ['paths' => [], 'errors' => $errors];
    }

    foreach ($processed_files as $file_data) {
        $upload_result = handleImageUpload($file_data, $upload_dir_absolute);

        if (is_string($upload_result) && !empty($upload_result)) {
            $uploaded_paths[] = $upload_result;
        } elseif (is_array($upload_result) && isset($upload_result['error'])) {
            $errors[] = $upload_result['error'];
        }
    }

    return ['paths' => $uploaded_paths, 'errors' => $errors];
}

?>