<?php
require_once '../phpScript/config.php'; 

$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$listing_id = intval($_GET['id']);

$sql = "SELECT l.*, mk.name as make_name, md.name as model_name, vt.name as vehicle_type_name, u.username as seller_username
        FROM listings l
        JOIN makes mk ON l.make_id = mk.id
        JOIN models md ON l.model_id = md.id
        JOIN vehicle_types vt ON l.vehicle_type_id = vt.id
        JOIN users u ON l.user_id = u.id 
        WHERE l.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $listing_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    header('Location: index.php?error=not_found');
    exit;
}
$listing = $result->fetch_assoc();
$stmt->close();

$listing_gallery_images = [];
$stmt_gallery = $conn->prepare("SELECT image_path, is_primary FROM listing_images WHERE listing_id = ? ORDER BY is_primary DESC, id ASC");
if ($stmt_gallery) {
    $stmt_gallery->bind_param('i', $listing_id);
    $stmt_gallery->execute();
    $result_gallery = $stmt_gallery->get_result();
    while ($gallery_row = $result_gallery->fetch_assoc()) {
        $listing_gallery_images[] = $gallery_row;
    }
    $stmt_gallery->close();
}

$is_favorite = false;
$current_listing_id_for_fav_check = $listing_id; 

if (isset($_SESSION['user_id']) && isset($conn) && $conn instanceof mysqli) {
    $current_user_id_for_fav = $_SESSION['user_id'];
    $stmt_fav_check = $conn->prepare("SELECT id FROM user_favorites WHERE user_id = ? AND listing_id = ?");
    if ($stmt_fav_check) {
        $stmt_fav_check->bind_param('ii', $current_user_id_for_fav, $current_listing_id_for_fav_check);
        $stmt_fav_check->execute();
        $result_fav_check = $stmt_fav_check->get_result();
        if ($result_fav_check->num_rows > 0) {
            $is_favorite = true;
        }
        $stmt_fav_check->close();
    }
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
    <title><?= htmlspecialchars($listing['make_name'].' '.$listing['model_name']) ?> - AutoTurgus</title>
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/skelbimas.css">
   
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="user-menu">
            <div class="left">
                <a href="../index.php"><img id="logo" src="../img/logo.png" alt="logo"></a>
            </div>
            <div class="right">
                <a href="paskyra/dashboard.php">Mano Paskyra</a>
                <a href="naujas_skelbimas.php" class="add-listing-btn right_lean">Pridėti skelbimą</a>
                <a href="../phpScript/logout.php" class="logout-btn">Atsijungti</a>
            </div>
        </div>
    <?php else: ?>
        <div class="top-menu">
            <div class="left">
                <a href="../index.php"><img id="logo" src="../img/logo.png" alt="logo"></a>
            </div>
            <div class="right">
                <button onclick="location.href='login.php'">Prisijungti</button>
                <button onclick="location.href='register.php'">Registruotis</button>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <a href="javascript:history.back()" class="back-link">← Grįžti atgal</a>
        
        <?php if ($success_message): ?>
            <div class="success-message">
                <p><?= htmlspecialchars($success_message) ?></p>
            </div>
        <?php endif; ?>

        <div class="listing-details">
            <h1>
                <?= htmlspecialchars($listing['make_name'].' '.$listing['model_name']) ?> 
                <?php if (!empty($listing['vehicle_type_name'])): ?>
                    <small>(<?= htmlspecialchars($listing['vehicle_type_name']) ?>)</small>
                <?php endif; ?>
            </h1>
            
            <div class="listing-image-gallery-wrapper">
                <?php 
                $primary_image_for_display = null;
                if (!empty($listing['image_url'])) {
                    $primary_image_for_display = $listing['image_url'];
                } elseif (!empty($listing_gallery_images)) {
                    $primary_image_for_display = $listing_gallery_images[0]['image_path'];
                }

                $main_image_src = '../img/default.png';
                if ($primary_image_for_display) {
                    if (strpos($primary_image_for_display, 'http') === 0 || strpos($primary_image_for_display, 'https') === 0) {
                        $main_image_src = htmlspecialchars($primary_image_for_display);
                    } else {
                        $local_path_main = ltrim($primary_image_for_display, '/');
                        if (!empty($local_path_main)) {
                             $main_image_src = '../' . htmlspecialchars($local_path_main);
                        }
                    }
                }
                ?>
                <div class="main-listing-image-display">
                    <img id="mainDisplayedImage" src="<?= $main_image_src ?>" 
                         alt="Pagrindinė <?= htmlspecialchars($listing['make_name'].' '.$listing['model_name']) ?> nuotrauka"
                         onerror="this.onerror=null; this.src='../img/default.png';">
                </div>

                <?php if (!empty($listing_gallery_images) && count($listing_gallery_images) > 0): ?>
                    <div class="gallery-thumbnails-strip">
                        <?php foreach ($listing_gallery_images as $gallery_img): ?>
                            <?php
                                $thumb_src_path = '../img/default.png';
                                if (strpos($gallery_img['image_path'], 'http') === 0 || strpos($gallery_img['image_path'], 'https') === 0) {
                                    $thumb_src_path = htmlspecialchars($gallery_img['image_path']);
                                } else {
                                    $local_thumb_path = ltrim($gallery_img['image_path'], '/');
                                    if (!empty($local_thumb_path)) {
                                        $thumb_src_path = '../' . htmlspecialchars($local_thumb_path);
                                    }
                                }
                            ?>
                            <img src="<?= $thumb_src_path ?>" 
                                 alt="Skelbimo nuotraukos miniatiūra" 
                                 class="gallery-thumbnail-item <?= ($thumb_src_path == $main_image_src) ? 'active-thumbnail' : '' ?>"
                                 onerror="this.style.display='none';">
                        <?php endforeach; ?>
                    </div>
                <?php elseif (empty($listing_gallery_images) && $primary_image_for_display == null): ?>
                     <p style="text-align:center;">Nuotraukų nėra.</p>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($favorite_action_success)): ?>
                <div class="success-message" style="margin-top:15px; margin-bottom:15px;">
                    <p><?= htmlspecialchars($favorite_action_success) ?></p>
                </div>
            <?php endif; ?>
            <?php if (!empty($favorite_action_error)): ?>
                <div class="error-message" style="margin-top:15px; margin-bottom:15px;">
                    <p><?= htmlspecialchars($favorite_action_error) ?></p>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <div style="margin-top: 20px; margin-bottom: 20px;">
                    <?php
                        $pamegti_url = '../phpScript/pamegti.php?id=' . $current_listing_id_for_fav_check;
                    ?>
                    <a href="<?= htmlspecialchars($pamegti_url) ?>" class="details-btn" style="background-color: <?= $is_favorite ? '#6c757d' : '#28a745' ?>; color: white; text-decoration: none; padding: 10px 15px;">
                        <?= $is_favorite ? 'Pašalinti iš mėgstamų' : 'Pridėti prie mėgstamų' ?>
                    </a>
                </div>
            <?php endif; ?>

            <div class="listing-specs">
                <div class="spec-row">
                    <span class="spec-label">Metai:</span>
                    <span class="spec-value"><?= htmlspecialchars($listing['year']) ?></span>
                </div>
                <div class="spec-row">
                    <span class="spec-label">Kaina:</span>
                    <span class="spec-value"><?= number_format((float)$listing['price'], 0, ',', ' ') ?> €</span>
                </div>
                <div class="spec-row">
                    <span class="spec-label">Rida:</span>
                    <span class="spec-value"><?= $listing['mileage'] !== null ? number_format((int)$listing['mileage'], 0, ',', ' ') . ' km' : 'Nenurodyta' ?></span>
                </div>
                <div class="spec-row">
                    <span class="spec-label">Variklio galia:</span>
                    <span class="spec-value"><?= htmlspecialchars($listing['power']) ?> kW</span>
                </div>
                <?php if (isset($listing['engine_capacity']) && $listing['engine_capacity'] > 0): ?>
                <div class="spec-row">
                    <span class="spec-label">Variklio tūris:</span>
                    <span class="spec-value"><?= htmlspecialchars(number_format((float)$listing['engine_capacity'], 1)) ?> l</span>
                </div>
                <?php endif; ?>
                <div class="spec-row">
                    <span class="spec-label">Kuro tipas:</span>
                    <span class="spec-value"><?= htmlspecialchars($listing['fuel_type']) ?></span>
                </div>
                <div class="spec-row">
                    <span class="spec-label">Telefonas:</span>
                    <span class="spec-value"><?= htmlspecialchars($listing['phone']) ?></span>
                </div>
                <?php if ($listing['vehicle_type_id'] == 1): ?>
                    <?php if (!empty($listing['body_type'])): ?>
                    <div class="spec-row">
                        <span class="spec-label">Kėbulo tipas:</span>
                        <span class="spec-value"><?= htmlspecialchars($listing['body_type']) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($listing['transmission'])): ?>
                    <div class="spec-row">
                        <span class="spec-label">Pavarų dėžė:</span>
                        <span class="spec-value"><?= htmlspecialchars($listing['transmission']) ?></span>
                    </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php if (!empty($listing['vin'])): ?>
                    <div class="spec-row">
                        <span class="spec-label">VIN kodas:</span>
                        <span class="spec-value"><?= htmlspecialchars($listing['vin']) ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($listing['city'])): ?>
                     <div class="spec-row">
                         <span class="spec-label">Miestas:</span>
                         <span class="spec-value"><?= htmlspecialchars($listing['city']) ?></span>
                     </div>
               <?php endif; ?>
                
                 <div class="spec-row">
                    <span class="spec-label">Pardavėjas:</span>
                    <span class="spec-value"><?= htmlspecialchars($listing['seller_username']) ?></span>
                </div>
            </div>
            
            <?php if (!empty($listing['description'])): ?>
                <div class="listing-description">
                    <h2>Aprašymas</h2>
                    <p><?= nl2br(htmlspecialchars($listing['description'])) ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script>
        const PHP_SCRIPTS_ROOT_PATH = '../phpScript/';
    </script>
    <script src="../js/script.js"></script> 
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const mainImageElement = document.getElementById('mainDisplayedImage');
        const thumbnailElements = document.querySelectorAll('.gallery-thumbnail-item');

        if (mainImageElement && thumbnailElements.length > 0) {
            thumbnailElements.forEach(thumb => {
                thumb.addEventListener('click', function() {
                    const newImageSource = this.getAttribute('src');
                    if (newImageSource) {
                        mainImageElement.setAttribute('src', newImageSource);
                    }
                    thumbnailElements.forEach(t => t.classList.remove('active-thumbnail'));
                    this.classList.add('active-thumbnail');
                });
            });
        }
    });
    </script>
</body>
</html>
<?php if(isset($conn) && $conn instanceof mysqli && $conn->thread_id) { $conn->close(); } ?>