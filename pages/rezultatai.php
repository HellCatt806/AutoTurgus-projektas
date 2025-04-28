<?php
require_once '../phpScript/config.php';

// Paieskos parametrai
$vehicle_type = isset($_GET['vehicle_type']) ? intval($_GET['vehicle_type']) : 1;
$make_id = isset($_GET['make']) ? intval($_GET['make']) : null;
$model_id = isset($_GET['model']) ? intval($_GET['model']) : null;
$year_from = isset($_GET['year_from']) ? intval($_GET['year_from']) : null;
$year_to = isset($_GET['year_to']) ? intval($_GET['year_to']) : null;
$price_from = isset($_GET['price_from']) ? floatval($_GET['price_from']) : null;
$price_to = isset($_GET['price_to']) ? floatval($_GET['price_to']) : null;
$fuel_type = isset($_GET['fuel_type']) ? $_GET['fuel_type'] : null;
$body_type = isset($_GET['body_type']) ? $_GET['body_type'] : null;

// SQL uzklausos formavimas
$sql = "SELECT l.*, mk.name as make_name, md.name as model_name 
        FROM listings l
        JOIN makes mk ON l.make_id = mk.id
        JOIN models md ON l.model_id = md.id
        WHERE l.vehicle_type_id = ?";

$params = [$vehicle_type];
$types = 'i';

if ($make_id) {
    $sql .= " AND l.make_id = ?";
    $params[] = $make_id;
    $types .= 'i';
}

if ($model_id) {
    $sql .= " AND l.model_id = ?";
    $params[] = $model_id;
    $types .= 'i';
}

if ($year_from) {
    $sql .= " AND l.year >= ?";
    $params[] = $year_from;
    $types .= 'i';
}

if ($year_to) {
    $sql .= " AND l.year <= ?";
    $params[] = $year_to;
    $types .= 'i';
}

if ($price_from) {
    $sql .= " AND l.price >= ?";
    $params[] = $price_from;
    $types .= 'd';
}

if ($price_to) {
    $sql .= " AND l.price <= ?";
    $params[] = $price_to;
    $types .= 'd';
}

if ($fuel_type) {
    $sql .= " AND l.fuel_type = ?";
    $params[] = $fuel_type;
    $types .= 's';
}

if ($body_type && $vehicle_type == 1) {
    $sql .= " AND l.body_type = ?";
    $params[] = $body_type;
    $types .= 's';
}

$sql .= " ORDER BY l.created_at DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Paieškos rezultatai - AutoTurgus</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/rezultatai.css">
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="user-menu">
            <div class="left">
                <a href="../index.php"><img id="logo" src="../img/logo.svg" alt="logo"></a>
            </div>
            <div class="right">
                <a href="naujas_skelbimas.php" class="right_lean">Pridėti skelbimą</a>
                <a href="../phpScript/logout.php" class="logout-btn">Atsijungti</a>
            </div>
        </div>
    <?php else: ?>
        <div class="top-menu">
            <div class="left">
                <a href="../index.php"><img id="logo" src="../img/logo.svg" alt="logo"></a>
            </div>
            <div class="right">
                <button onclick="location.href='login.php'">Prisijungti</button>
                <button onclick="location.href='register.php'">Registruotis</button>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <h1><?= $vehicle_type == 1 ? 'Automobilių' : 'Motociklų' ?> paieškos rezultatai</h1>
        
        <?php if ($result->num_rows > 0): ?>
            <div class="results-container">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="car-item">
                        <?php if ($row['image_url']): ?>
                            <img src="<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['make_name'].' '.htmlspecialchars($row['model_name'])) ?>">
                        <?php endif; ?>
                        <div class="car-info">
                            <h2><?= htmlspecialchars($row['make_name'].' '.htmlspecialchars($row['model_name'])) ?></h2>
                            <div class="car-specs">
                                <p><strong>Metai:</strong> <?= htmlspecialchars($row['year']) ?></p>
                                <p><strong>Kaina:</strong> <?= number_format($row['price'], 0, ',', ' ') ?> €</p>
                                <p><strong>Rida:</strong> <?= number_format($row['mileage'], 0, ',', ' ') ?> km</p>
                                <?php if ($vehicle_type == 1): ?>
                                    <p><strong>Kėbulo tipas:</strong> <?= htmlspecialchars($row['body_type']) ?></p>
                                <?php endif; ?>
                                <p><strong>Kuro tipas:</strong> <?= htmlspecialchars($row['fuel_type']) ?></p>
                            </div>
                            <p class="contact-info"><strong>Kontaktai:</strong> <?= htmlspecialchars($row['phone']) ?></p>
                            <a href="skelbimas.php?id=<?= $row['id'] ?>" class="details-btn">Peržiūrėti skelbimą</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                <p>Pagal Jūsų paieškos kriterijus rezultatų nerasta.</p>
                <a href="../index.php" class="back-btn">Grįžti atgal</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>