<?php
require_once '../../phpScript/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$username = $_SESSION['username'] ?? 'Vartotojas';
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Mano Paskyra - AutoTurgus</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/paskyra.css">
</head>
<body>
    <div class="user-menu">
        <div class="left">
            <a href="../../index.php"><img id="logo" src="../../img/logo.svg" alt="logo"></a>
        </div>
        <div class="right">
            <a href="dashboard.php" class="active">Mano paskyra</a>
            <a href="../naujas_skelbimas.php">Pridėti skelbimą</a>
            <a href="../../phpScript/logout.php" class="logout-btn">Atsijungti</a>
        </div>
    </div>

    <div class="container">
        <h1>Sveiki, <?= htmlspecialchars($username) ?>!</h1>
        <p>Čia yra jūsų asmeninė "AutoTurgus" paskyra.</p>
        
        <div class="dashboard-menu">
            <div class="dashboard-menu-item">
                <h2><a href="mano_skelbimai.php">Mano skelbimai</a></h2>
                <p>Peržiūrėkite, redaguokite arba ištrinkite savo įkeltus skelbimus</p>
            </div>
            <div class="dashboard-menu-item">
                 <h2><a href="profilis.php">Profilio nustatymai</a></h2>
                <p>Atnaujinkite savo informaciją</p>
            </div>
             <div class="dashboard-menu-item">
                <h2><a href="megstami_skelbimai.php">Mėgstami skelbimai</a></h2>
                <p>Peržiūrėkite jūsų įsimintus skelbimus</p>
            </div>
        </div>
    </div>
    <script src="../../js/script.js"></script>
</body>
</html>