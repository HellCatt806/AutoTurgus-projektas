<?php
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = preg_replace('/[^0-9+]/', '', $_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($username) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        $error = 'Prašome užpildyti visus laukus';
    } elseif ($password !== $confirm_password) {
        $error = 'Slaptažodžiai nesutampa';
    } elseif (strlen($password) < 8) {
        $error = 'Slaptažodis turi būti bent 8 simbolių ilgio';
    } else {

        $sql = "SELECT id FROM users WHERE email = ? OR username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Vartotojas su tokiu el. paštu ar vardu jau egzistuoja';
        } else {
        
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
            $sql = "INSERT INTO users (username, password, email, phone) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssss', $username, $hashed_password, $email, $phone);
            
            if ($stmt->execute()) {
                $_SESSION['registration_success'] = true;
                header('Location: login.php');
                exit;
            } else {
                $error = 'Registracijos klaida: ' . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Registracija - AutoTurgus</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h1>Registracija</h1>
            
            <?php if ($error): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>
            
            <form id="register-form" method="post">
                <div class="form-group">
                    <label for="username">Vartotojo vardas:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="email">El. paštas:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Telefono numeris:</label>
                    <input type="tel" id="phone" name="phone" required>
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
</body>
</html>