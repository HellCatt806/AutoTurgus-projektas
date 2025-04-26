<?php
require_once '../phpScript/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$listing_id = intval($_GET['id']);

$sql = "SELECT l.*, mk.name as make_name, md.name as model_name, vt.name as vehicle_type_name
        FROM listings l
        JOIN makes mk ON l.make_id = mk.id
        JOIN models md ON l.model_id = md.id
        JOIN vehicle_types vt ON l.vehicle_type_id = vt.id
        WHERE l.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $listing_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php');
    exit;
}

$listing = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($listing['make_name'].' '.$listing['model_name']) ?> - AutoTurgus</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="user-menu">
            <span>Sveiki, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
            <a href="naujas_skelbimas.php" class="add-listing-btn">Pridėti skelbimą</a>
            <a href="api/logout.php" class="logout-btn">Atsijungti</a>
        </div>
    <?php else: ?>
        <div class="top-menu">
            <button onclick="location.href='index.php'">Skelbimai</button>
            <button onclick="location.href='login.php'">Prisijungti</button>
            <button onclick="location.href='register.php'">Registruotis</button>
        </div>
    <?php endif; ?>

    <div class="container">
        <a href="javascript:history.back()" class="back-link">← Grįžti atgal</a>
        
        <div class="listing-details">
            <h1><?= htmlspecialchars($listing['make_name'].' '.$listing['model_name']) ?> 
                <small>(<?= htmlspecialchars($listing['vehicle_type_name']) ?>)</small>
            </h1>
            
            <?php if (!empty($listing['image_url'])): ?>
                <div class="listing-image">
                    <?php if (strpos($listing['image_url'], 'http') === 0): ?>
                        <!-- Nuotrauka iš interneto -->
                        <img src="<?= htmlspecialchars($listing['image_url']) ?>" 
                             alt="<?= htmlspecialchars($listing['make_name'].' '.$listing['model_name']) ?>"
                             onerror="this.src='images/default.jpg';">
                    <?php else: ?>
                        <!-- Lokali nuotrauka -->
                        <img src="<?= htmlspecialchars($listing['image_url']) ?>" 
                             alt="<?= htmlspecialchars($listing['make_name'].' '.$listing['model_name']) ?>"
                             onerror="this.src='images/default.jpg';">
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <!-- Jei nuotraukos nėra -->
                <div class="listing-image">
                    <img src="images/default.jpg" alt="Numatytoji nuotrauka">
                </div>
            <?php endif; ?>
            
            <div class="listing-specs">
                <div class="spec-row">
                    <span class="spec-label">Metai:</span>
                    <span class="spec-value"><?= htmlspecialchars($listing['year']) ?></span>
                </div>
                <div class="spec-row">
                    <span class="spec-label">Kaina:</span>
                    <span class="spec-value"><?= number_format($listing['price'], 0, ',', ' ') ?> €</span>
                </div>
                <div class="spec-row">
                    <span class="spec-label">Rida:</span>
                    <span class="spec-value"><?= number_format($listing['mileage'], 0, ',', ' ') ?> km</span>
                </div>
                <div class="spec-row">
                    <span class="spec-label">Variklio galia:</span>
                    <span class="spec-value"><?= htmlspecialchars($listing['power']) ?> kW</span>
                </div>
                <div class="spec-row">
                    <span class="spec-label">Variklio tūris:</span>
                    <span class="spec-value"><?= htmlspecialchars($listing['engine_capacity']) ?> l</span>
                </div>
                <div class="spec-row">
                    <span class="spec-label">Kuro tipas:</span>
                    <span class="spec-value"><?= htmlspecialchars($listing['fuel_type']) ?></span>
                </div>
                <div class="spec-row">
                    <span class="spec-label">Telefonas:</span>
                    <span class="spec-value"><?= htmlspecialchars($listing['phone']) ?></span>
                </div>
                
                <?php if ($listing['vehicle_type_id'] == 1): ?>
                    <div class="spec-row">
                        <span class="spec-label">Kėbulo tipas:</span>
                        <span class="spec-value"><?= $listing['body_type'] ? htmlspecialchars($listing['body_type']) : 'Nenurodyta' ?></span>
                    </div>
                    <div class="spec-row">
                        <span class="spec-label">Pavarų dėžė:</span>
                        <span class="spec-value"><?= $listing['transmission'] ? htmlspecialchars($listing['transmission']) : 'Nenurodyta' ?></span>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($listing['vin'])): ?>
                    <div class="spec-row">
                        <span class="spec-label">VIN kodas:</span>
                        <span class="spec-value"><?= htmlspecialchars($listing['vin']) ?></span>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($listing['description'])): ?>
                <div class="listing-description">
                    <h2>Aprašymas</h2>
                    <p><?= nl2br(htmlspecialchars($listing['description'])) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>