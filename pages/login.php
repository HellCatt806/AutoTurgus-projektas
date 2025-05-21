<?php
require_once '../phpScript/config.php';
require_once '../phpScript/funkc.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$page_errors = [];
$form_data = ['email' => ''];

$success_message = '';
if (isset($_SESSION['registration_success'])) {
    $success_message = $_SESSION['registration_success'];
    unset($_SESSION['registration_success']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $form_data['email'] = htmlspecialchars($email);

    $validation_errors = validateLoginData($email, $password);
    if (!empty($validation_errors)) {
        $page_errors = array_merge($page_errors, $validation_errors);
    } else {
        $user = verifyUserCredentials($conn, $email, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: ../index.php');
            exit;
        } else {
            $page_errors[] = 'Neteisingi prisijungimo duomenys';
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Prisijungimas - AutoTurgus</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="top-menu">
        <div class="left">
            <a href="../index.php"><img id="logo" src="../img/logo.svg" alt="logo"></a>
        </div>
        <div class="right">
            <button onclick="location.href='login.php'" class="active">Prisijungti</button>
            <button onclick="location.href='register.php'">Registruotis</button>
        </div>
    </div>
    <div class="auth-container">
        <div class="auth-box" id="login-box">
            <h1>Prisijungimas</h1>
            
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
            
            <form id="login-form" method="post" action="login.php">
                <div class="form-group">
                    <label for="email">El. paštas:</label>
                    <input type="email" id="email" name="email" value="<?= $form_data['email'] ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Slaptažodis:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="auth-btn">Prisijungti</button>
            </form>
            
            <p class="auth-link">Neturite paskyros? <a href="register.php">Registruokitės čia</a></p>
        </div>
    </div>
    <script src="../js/script.js"></script>
</body>
</html>