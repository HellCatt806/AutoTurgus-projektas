<?php
require_once '../../phpScript/config.php';
require_once '../../phpScript/funkc.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$page_errors = [];
$success_message = '';

$stmt_user_data = $conn->prepare("SELECT username, email, phone FROM users WHERE id = ?");
$stmt_user_data->bind_param('i', $user_id);
$stmt_user_data->execute();
$result_user_data = $stmt_user_data->get_result();
$current_user_data = $result_user_data->fetch_assoc();
$stmt_user_data->close();

if (!$current_user_data) {
    $page_errors[] = "Klaida gaunant vartotojo duomenis. Bandykite vėliau.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile_data'])) {
    if ($current_user_data) {
        $new_username = trim($_POST['username'] ?? '');
        $new_email = trim($_POST['email'] ?? '');
        $new_phone_raw = $_POST['phone'] ?? '';
        $new_phone = preg_replace('/[^0-9+]/', '', $new_phone_raw);

        $username_changed_by_user = ($new_username !== $current_user_data['username']);
        $email_changed_by_user = ($new_email !== $current_user_data['email']);
        $phone_changed_by_user = ($new_phone !== $current_user_data['phone']);
        
        $updates_made_count = 0;
        $temp_page_errors = [];

        if ($username_changed_by_user) {
            if (empty($new_username)) {
                $temp_page_errors[] = "Vartotojo vardo laukas negali būti tuščias.";
            } elseif (strlen($new_username) < 3) { 
                $temp_page_errors[] = "Vartotojo vardas turi būti bent 3 simbolių ilgio.";
            } else {
                $stmt_check_username = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
                $stmt_check_username->bind_param('si', $new_username, $user_id);
                $stmt_check_username->execute();
                $result_check_username = $stmt_check_username->get_result();
                if ($result_check_username->num_rows > 0) {
                    $temp_page_errors[] = "Šis vartotojo vardas jau naudojamas.";
                } else {
                    $stmt_update_username = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
                    $stmt_update_username->bind_param('si', $new_username, $user_id);
                    if ($stmt_update_username->execute() && $stmt_update_username->affected_rows > 0) {
                        $current_user_data['username'] = $new_username; 
                        $_SESSION['username'] = $new_username; 
                        $updates_made_count++;
                    } else if ($stmt_update_username->affected_rows == 0 && empty($conn->error)) {
                    
                    } else {
                        $temp_page_errors[] = "Nepavyko atnaujinti vartotojo vardo.";
                    }
                    $stmt_update_username->close();
                }
                $stmt_check_username->close();
            }
        }

        if ($email_changed_by_user) {
            if (empty($new_email)) {
                $temp_page_errors[] = "El. pašto laukas negali būti tuščias.";
            } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
                $temp_page_errors[] = "Neteisingas el. pašto formatas.";
            } else {
                $stmt_check_email = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
                $stmt_check_email->bind_param('si', $new_email, $user_id);
                $stmt_check_email->execute();
                $result_check_email = $stmt_check_email->get_result();
                if ($result_check_email->num_rows > 0) {
                    $temp_page_errors[] = "Šis el. paštas jau naudojamas kito vartotojo.";
                } else {
                    $stmt_update_email = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
                    $stmt_update_email->bind_param('si', $new_email, $user_id);
                    if ($stmt_update_email->execute() && $stmt_update_email->affected_rows > 0) {
                        $current_user_data['email'] = $new_email;
                        $updates_made_count++;
                    } elseif ($stmt_update_email->affected_rows == 0 && empty($conn->error)) {
                    
                    } else {
                        $temp_page_errors[] = "Nepavyko atnaujinti el. pašto.";
                    }
                    $stmt_update_email->close();
                }
                $stmt_check_email->close();
            }
        }

        if ($phone_changed_by_user) {
            if (empty($new_phone)) {
                $temp_page_errors[] = "Telefono numerio laukas negali būti tuščias.";
            } elseif (strlen(preg_replace('/[^0-9]/', '', $new_phone)) < 7 && !empty($new_phone)) {
                $temp_page_errors[] = 'Telefono numeris per trumpas.';
            } else {
                $stmt_update_phone = $conn->prepare("UPDATE users SET phone = ? WHERE id = ?");
                $stmt_update_phone->bind_param('si', $new_phone, $user_id);
                if ($stmt_update_phone->execute() && $stmt_update_phone->affected_rows > 0) {
                    $current_user_data['phone'] = $new_phone;
                    $updates_made_count++;
                } elseif ($stmt_update_phone->affected_rows == 0 && empty($conn->error)) {
                
                } else {
                    $temp_page_errors[] = "Nepavyko atnaujinti telefono numerio.";
                }
                $stmt_update_phone->close();
            }
        }

        if (!empty($temp_page_errors)) {
            $page_errors = array_merge($page_errors, $temp_page_errors);
        } elseif ($updates_made_count > 0) {
            $success_message = "Duomenys sėkmingai atnaujinti!";
        } elseif (!$username_changed_by_user && !$email_changed_by_user && !$phone_changed_by_user) {
            $page_errors[] = "Nebuvo atlikta jokių pakeitimų.";
        }
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_new_password = $_POST['confirm_new_password'] ?? '';

    if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
        $page_errors[] = "Visi slaptažodžio keitimo laukai yra privalomi.";
    }
    if (strlen($new_password) < 8 && !empty($new_password)) {
        $page_errors[] = "Naujas slaptažodis turi būti bent 8 simbolių ilgio.";
    }
    if ($new_password !== $confirm_new_password) {
        $page_errors[] = "Nauji slaptažodžiai nesutampa.";
    }
    if ($new_password === $current_password && !empty($new_password) && !empty($current_password)) {
        $page_errors[] = "Naujas slaptažodis negali sutapti su dabartiniu slaptažodžiu.";
    }

    if (empty($page_errors)) {
        $stmt_check_pass = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt_check_pass->bind_param('i', $user_id);
        $stmt_check_pass->execute();
        $result_check_pass = $stmt_check_pass->get_result();
        $user_pass_data = $result_check_pass->fetch_assoc();
        $stmt_check_pass->close();

        if ($user_pass_data && password_verify($current_password, $user_pass_data['password'])) {
            $hashed_new_password = password_hash($new_password, PASSWORD_BCRYPT);
            if ($hashed_new_password === false) {
                $page_errors[] = 'Sistemos klaida generuojant slaptažodį.';
            } else {
                $stmt_update_pass = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt_update_pass->bind_param('si', $hashed_new_password, $user_id);
                if ($stmt_update_pass->execute() && $stmt_update_pass->affected_rows > 0) {
                    $success_message = "Slaptažodis sėkmingai pakeistas!";
                } else {
                    $page_errors[] = "Nepavyko pakeisti slaptažodžio (galbūt naujas sutapo su senu?).";
                }
                $stmt_update_pass->close();
            }
        } else {
            $page_errors[] = "Neteisingas dabartinis slaptažodis.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Profilio Nustatymai - AutoTurgus</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/paskyra.css">
    <link rel="stylesheet" href="../../css/register.css"> 
    <style>
        .profile-section {
            background-color: #fff;
            padding: 20px 25px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .profile-section h2 {
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

    </style>
</head>
<body>
    <div class="user-menu">
        <div class="left">
            <a href="../../index.php"><img id="logo" src="../../img/logo.png" alt="logo"></a>
        </div>
        <div class="right">
            <a href="dashboard.php" class="active">Mano Paskyra</a>
            <a href="../naujas_skelbimas.php">Pridėti skelbimą</a>
            <a href="../../phpScript/logout.php" class="logout-btn">Atsijungti</a>
        </div>
    </div>

    <div class="container">
        <h1>Profilio Nustatymai</h1>
        <a href="dashboard.php" class="back-link" style="margin-bottom: 20px; display: inline-block;">&larr; Grįžti į paskyrą</a>

        <?php if (!empty($page_errors)): ?>
            <div class="error-message">
                <?php foreach ($page_errors as $err): ?>
                    <p><?= htmlspecialchars($err) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="success-message">
                <p><?= htmlspecialchars($success_message) ?></p>
            </div>
        <?php endif; ?>

        <?php if (isset($current_user_data)): ?>
            <div class="profile-section">
                <h2>Mano Duomenys</h2>
                <form method="post" action="profilis.php">
                    <div class="form-group">
                        <label for="username_profile">Vartotojo vardas:</label>
                        <input type="text" id="username_profile" name="username" value="<?= htmlspecialchars($current_user_data['username'] ?? '') ?>" required minlength="3">
                    </div>
                    <div class="form-group">
                        <label for="email_profile">El. paštas:</label>
                        <input type="email" id="email_profile" name="email" value="<?= htmlspecialchars($current_user_data['email'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_profile">Telefono numeris:</label>
                        <input type="tel" id="phone_profile" name="phone" value="<?= htmlspecialchars($current_user_data['phone'] ?? '') ?>" required placeholder="+370xxxxxxxx">
                    </div>
                    <button type="submit" name="update_profile_data" class="auth-btn">Atnaujinti duomenis</button>
                </form>
            </div>

            <div class="profile-section">
                <h2>Pakeisti Slaptažodį</h2>
                <form method="post" action="profilis.php">
                    <div class="form-group">
                        <label for="current_password">Dabartinis slaptažodis:</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Naujas slaptažodis:</label>
                        <input type="password" id="new_password" name="new_password" required minlength="8">
                    </div>
                    <div class="form-group">
                        <label for="confirm_new_password">Pakartokite naują slaptažodį:</label>
                        <input type="password" id="confirm_new_password" name="confirm_new_password" required minlength="8">
                    </div>
                    <button type="submit" name="update_password" class="auth-btn">Pakeisti slaptažodį</button>
                </form>
            </div>
        <?php else: ?>
            <div class="error-message">
                 <p>Nepavyko įkelti vartotojo duomenų. Prašome pabandyti vėliau.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
<?php if(isset($conn) && $conn instanceof mysqli && $conn->thread_id) { $conn->close(); } ?>