<?php
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Prašome užpildyti visus laukus';
    } else {
        $sql = "SELECT id, username, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Neteisingas slaptažodis';
            }
        } else {
            $error = 'Vartotojas su tokiu el. paštu nerastas';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Prisijungimas - AutoTurgus</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h1>Prisijungimas</h1>
            
            <?php if ($error): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>
            
            <form id="login-form" method="post">
                <div class="form-group">
                    <label for="email">El. paštas:</label>
                    <input type="email" id="email" name="email" required>
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
</body>
</html>