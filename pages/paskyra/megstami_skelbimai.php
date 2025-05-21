<?php
require_once '../../phpScript/config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$favorite_listings = [];

$sql = "SELECT l.id, l.year, l.price, l.mileage, l.body_type, l.fuel_type, l.phone, l.image_url, l.city, l.vehicle_type_id,
               mk.name as make_name, md.name as model_name, vt.name as vehicle_type_name
        FROM user_favorites uf
        JOIN listings l ON uf.listing_id = l.id
        JOIN makes mk ON l.make_id = mk.id
        JOIN models md ON l.model_id = md.id
        JOIN vehicle_types vt ON l.vehicle_type_id = vt.id
        WHERE uf.user_id = ?
        ORDER BY uf.created_at DESC";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('i', $user_id);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $favorite_listings[] = $row;
        }
    }
    $stmt->close();
}

$favorite_action_success = '';
if (isset($_SESSION['favorite_action_success'])) {
    $favorite_action_success = $_SESSION['favorite_action_success'];
    unset($_SESSION['favorite_action_success']);
}
$favorite_action_error = '';
if (isset($_SESSION['favorite_action_error'])) {
    $favorite_action_error = $_SESSION['favorite_action_error'];
    unset($_SESSION['favorite_action_error']);
}

?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Mėgstami Skelbimai - AutoTurgus</title>
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/paskyra.css">
    <link rel="stylesheet" href="../../css/rezultatai.css">
    <style>
        .action-btn {
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            margin-right: 5px;
            font-size: 0.9em;
            color: white;
            display: inline-block;
            margin-bottom: 5px;
        }
        .view-btn {
            background-color: #007bff;
        }
        .view-btn:hover {
            background-color: #0056b3;
        }
        .remove-fav-btn {
            background-color: #dc3545;
        }
        .remove-fav-btn:hover {
            background-color: #c82333;
        }
        .car-item .actions {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="user-menu">
        <div class="left">
            <a href="../../index.php"><img id="logo" src="../../img/logo.svg" alt="logo"></a>
        </div>
        <div class="right">
            <a href="dashboard.php" class="active">Mano Paskyra</a>
            <a href="../naujas_skelbimas.php">Pridėti skelbimą</a>
            <a href="../../phpScript/logout.php" class="logout-btn">Atsijungti</a>
        </div>
    </div>

    <div class="container">
        <h1>Mano Mėgstami Skelbimai</h1>
        <a href="dashboard.php" class="back-link" style="margin-bottom: 20px; display: inline-block;">&larr; Grįžti į paskyrą</a>

        <?php if (!empty($favorite_action_success)): ?>
            <div class="success-message">
                <p><?= htmlspecialchars($favorite_action_success) ?></p>
            </div>
        <?php endif; ?>
        <?php if (!empty($favorite_action_error)): ?>
            <div class="error-message">
                <p><?= htmlspecialchars($favorite_action_error) ?></p>
            </div>
        <?php endif; ?>

        <?php if (empty($favorite_listings)): ?>
            <div class="no-results" style="text-align:left; max-width:none; margin-left:0; margin-right:0;">
                 <p>Jūs neturite pažymėtų mėgstamų skelbimų.</p>
                <a href="../../index.php" class="details-btn" style="background-color: #8600af; color:white; display:inline-block; width:auto;">Ieškoti skelbimų</a>
            </div>
        <?php else: ?>
            <div class="results-container">
                <?php foreach ($favorite_listings as $listing): ?>
                    <div class="car-item">
                        <?php
                        $image_display_source = '../../img/default.png';
                        if (!empty($listing['image_url'])) {
                            $image_path_from_db = $listing['image_url'];
                            $is_external_image = (strpos($image_path_from_db, 'http') === 0 || strpos($image_path_from_db, 'https') === 0);
                            if ($is_external_image) {
                                $image_display_source = htmlspecialchars($image_path_from_db);
                            } else {
                                $local_image_path_relative_to_root = ltrim($image_path_from_db, '/');
                                if (!empty($local_image_path_relative_to_root)) {
                                    $image_display_source = '../../' . htmlspecialchars($local_image_path_relative_to_root);
                                }
                            }
                        }
                        ?>
                        <img src="<?= $image_display_source ?>" 
                             alt="<?= htmlspecialchars($listing['make_name'].' '.$listing['model_name']) ?>"
                             onerror="this.onerror=null; this.src='../../img/default.png';">
                        
                        <div class="car-info">
                            <h2><?= htmlspecialchars($listing['make_name'].' '.$listing['model_name']) ?> (<?= htmlspecialchars($listing['vehicle_type_name']) ?>)</h2>
                            <div class="car-specs">
                                <p><strong>Metai:</strong> <?= htmlspecialchars($listing['year']) ?></p>
                                <p><strong>Kaina:</strong> <?= number_format((float)$listing['price'], 0, ',', ' ') ?> €</p>
                                <?php if ($listing['mileage'] !== null): ?>
                                <p><strong>Rida:</strong> <?= number_format((int)$listing['mileage'], 0, ',', ' ') . ' km' ?></p>
                                <?php endif; ?>
                                <?php if ($listing['vehicle_type_id'] == 1 && !empty($listing['body_type'])): ?>
                                    <p><strong>Kėbulo tipas:</strong> <?= htmlspecialchars($listing['body_type']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($listing['fuel_type'])): ?>
                                <p><strong>Kuro tipas:</strong> <?= htmlspecialchars($listing['fuel_type']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($listing['city'])): ?>
                                <p><strong>Miestas:</strong> <?= htmlspecialchars($listing['city']) ?></p>
                                <?php endif; ?>
                            </div>
                             <?php if (!empty($listing['phone'])): ?>
                            <p class="contact-info"><strong>Kontaktai:</strong> <?= htmlspecialchars($listing['phone']) ?></p>
                            <?php endif; ?>
                            <div class="actions">
                                <a href="../skelbimas.php?id=<?= $listing['id'] ?>" class="action-btn view-btn">Peržiūrėti</a>
                                <a href="../../phpScript/pamegti.php?id=<?= $listing['id'] ?>" class="action-btn remove-fav-btn" onclick="return confirm('Ar tikrai norite pašalinti šį skelbimą iš mėgstamų?');">Pašalinti iš mėgstamų</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <script>
        const PHP_SCRIPTS_ROOT_PATH = '../../phpScript/';
    </script>
    <script src="../../js/script.js"></script>
</body>
</html>
<?php if(isset($conn) && $conn instanceof mysqli && $conn->thread_id) { $conn->close(); } ?>