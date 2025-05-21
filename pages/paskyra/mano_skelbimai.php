<?php
require_once '../../phpScript/config.php';

$delete_message = '';
if (isset($_SESSION['delete_message'])) {
    $delete_message = $_SESSION['delete_message'];
    unset($_SESSION['delete_message']);
}
$delete_error = '';
if (isset($_SESSION['delete_error'])) {
    $delete_error = $_SESSION['delete_error'];
    unset($_SESSION['delete_error']);
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$listings = [];

$sql= "SELECT l.id, l.year, l.price, l.mileage, l.body_type, l.fuel_type, l.phone, l.image_url, l.city, l.vehicle_type_id, l.created_at,
               mk.name as make_name, md.name as model_name, vt.name as vehicle_type_name
        FROM listings l
        JOIN makes mk ON l.make_id = mk.id
        JOIN models md ON l.model_id = md.id
        JOIN vehicle_types vt ON l.vehicle_type_id = vt.id
        WHERE l.user_id = ?
        ORDER BY l.created_at DESC";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $listings[] = $row;
    }
    $stmt->close();
} else {
    error_log("SQL prepare failed: " . $conn->error);
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Mano Skelbimai - AutoTurgus</title>
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
        }
        .edit-btn {
            background-color: #28a745;
            color: white;
        }
        .edit-btn:hover {
            background-color: #218838;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        .car-item .actions {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="user-menu">
        <div class="left">
            <a href="../../index.php"><img id="logo" src="../../img/logo.svg" alt="logo"></a>
        </div>
        <div class="right">
            <a href="dashboard.php">Mano Paskyra</a>
            <a href="../naujas_skelbimas.php">Pridėti skelbimą</a>
            <a href="../../phpScript/logout.php" class="logout-btn">Atsijungti</a>
        </div>
    </div>

    <div class="container">
        <h1>Mano Skelbimai</h1>
        
        <a href="dashboard.php" class="back-link" style="margin-bottom: 20px; display: inline-block;">&larr; Grįžti į paskyrą</a>

        <?php if ($delete_message): ?>
        <div class="success-message">
        <p><?= htmlspecialchars($delete_message) ?></p>
        </div>
         <?php endif; ?>
         <?php if ($delete_error): ?>
        <div class="error-message">
         <p><?= htmlspecialchars($delete_error) ?></p>
        </div>
         <?php endif; ?>

        <?php if (empty($listings)): ?>
            <p>Jūs neturite įkėlę jokių skelbimų.</p>
            <a href="../naujas_skelbimas.php" class="details-btn" style="background-color: #8600af; color:white;">Pridėti naują skelbimą</a>
        <?php else: ?>
            <div class="results-container">
                <?php foreach ($listings as $listing): ?>
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
                            <?php if (isset($listing['vehicle_type_id']) && $listing['vehicle_type_id'] == 1 && !empty($listing['body_type'])): ?>
                                <p><strong>Kėbulo tipas:</strong> <?= htmlspecialchars($listing['body_type']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($listing['fuel_type'])): ?>
                            <p><strong>Kuro tipas:</strong> <?= htmlspecialchars($listing['fuel_type']) ?></p>
                            <?php endif; ?>
                            <?php if (!empty($listing['city'])): ?>
                                <p><strong>Miestas:</strong> <?= htmlspecialchars($listing['city']) ?></p>
                            <?php endif; ?>
                                <p><strong>Įkelta:</strong> <?= date('Y-m-d', strtotime($listing['created_at'])) ?></p>
                
                            </div>
                            <div class="actions">
                                <a href="../skelbimas.php?id=<?= $listing['id'] ?>" class="action-btn" style="background-color:#007bff; color:white;">Peržiūrėti</a>
                                <a href="redaguoti_skelbima.php?id=<?= $listing['id'] ?>" class="action-btn edit-btn">Redaguoti</a>
                                <a href="../../phpScript/delete_listing.php?id=<?= $listing['id'] ?>" class="action-btn delete-btn" onclick="return confirm('Ar tikrai norite ištrinti šį skelbimą?');">Ištrinti</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="../../js/script.js"></script>
</body>
</html>