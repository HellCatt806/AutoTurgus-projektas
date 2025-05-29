<?php
require_once '../phpScript/config.php';
require_once '../phpScript/funkc.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$page_errors = [];
$form_data = [
    'username' => '',
    'email' => '',
    'phone' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone_raw = $_POST['phone'] ?? '';
    $phone = preg_replace('/[^0-9+]/', '', $phone_raw);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $form_data['username'] = htmlspecialchars($username);
    $form_data['email'] = htmlspecialchars($email);
    $form_data['phone'] = htmlspecialchars($phone_raw);

    $validation_errors = validateRegistrationData($username, $email, $phone, $password, $confirm_password);
    if (!empty($validation_errors)) {
        $page_errors = array_merge($page_errors, $validation_errors);
    } else {
        if (userExists($conn, $email, $username)) {
            $page_errors[] = 'Vartotojas su tokiu el. paštu ar vardu jau egzistuoja';
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            if ($hashed_password === false) {
                $page_errors[] = 'Sistemos klaida. Bandykite vėliau.';
                error_log("Password hashing failed for user: " . $email . " in pages/register.php");
            } else {
                if (createUser($conn, $username, $hashed_password, $email, $phone)) {
                    $_SESSION['registration_success'] = 'Registracija sėkminga! Dabar galite prisijungti.';
                    $_SESSION['email'] = $email;
                    header('Location: ../phpScript/welcome_email.php');
                    exit;
                } else {
                    $page_errors[] = 'Registracijos klaida. Bandykite vėliau.';
                }
            }
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Registracija - AutoTurgus</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/register.css">
</head>
<body>
    <div class="top-menu">
        <div class="left">
            <a href="../index.php"><img id="logo" src="../img/logo.png" alt="logo"></a>
        </div>
        <div class="right">
            <button onclick="location.href='login.php'">Prisijungti</button>
            <button onclick="location.href='register.php'" class="active">Registruotis</button>
        </div>
    </div>
    <div class="auth-container">
        <div class="auth-box" id="register-box">
            <h1>Registracija</h1>
            
            <?php if (!empty($page_errors)): ?>
                <div class="error-message">
                    <?php foreach ($page_errors as $err): ?>
                        <p><?= htmlspecialchars($err) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form id="register-form" method="post" action="register.php">
                <div class="form-group">
                    <label for="username">Vartotojo vardas:</label>
                    <input type="text" id="username" name="username" value="<?= $form_data['username'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">El. paštas:</label>
                    <input type="email" id="email" name="email" value="<?= $form_data['email'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Telefono numeris:</label>
                    <input type="tel" id="phone" name="phone" value="<?= $form_data['phone'] ?>" required placeholder="+370xxxxxxxx">
                </div>
                
                <div class="form-group">
                    <label for="password">Slaptažodis:</label>
                    <input type="password" id="password" name="password" required minlength="8">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Pakartokite slaptažodį:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8">
                </div>
                
                <button type="submit" class="auth-btn">Registruotis</button>
            </form>
            
            <p class="auth-link">Jau turite paskyrą? <a href="login.php">Prisijunkite čia</a></p>
        </div>
    </div>
    <script src="../js/script.js"></script>
</body>
</html>