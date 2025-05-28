<?php
require_once '../phpScript/config.php';

$listings_per_page = 10; 
$current_page = isset($_GET['puslapis']) && is_numeric($_GET['puslapis']) ? intval($_GET['puslapis']) : 1;
if ($current_page < 1) {
    $current_page = 1;
}
$offset = ($current_page - 1) * $listings_per_page;

$search_params_get = $_GET; 
unset($search_params_get['puslapis']); 
$query_string_for_pages = http_build_query($search_params_get);
if (!empty($query_string_for_pages)) {
    $query_string_for_pages .= '&';
}

$vehicle_type = isset($_GET['vehicle_type']) ? intval($_GET['vehicle_type']) : 1;
$make_id = isset($_GET['make']) && !empty($_GET['make']) ? intval($_GET['make']) : null;
$model_id = isset($_GET['model']) && !empty($_GET['model']) ? intval($_GET['model']) : null;
$year_from = isset($_GET['year_from']) && !empty($_GET['year_from']) ? intval($_GET['year_from']) : null;
$year_to = isset($_GET['year_to']) && !empty($_GET['year_to']) ? intval($_GET['year_to']) : null;
$price_from = isset($_GET['price_from']) && !empty($_GET['price_from']) ? floatval($_GET['price_from']) : null;
$price_to = isset($_GET['price_to']) && !empty($_GET['price_to']) ? floatval($_GET['price_to']) : null;
$fuel_type = isset($_GET['fuel_type']) && !empty($_GET['fuel_type']) ? $_GET['fuel_type'] : null;
$body_type = isset($_GET['body_type']) && !empty($_GET['body_type']) ? $_GET['body_type'] : null;
$city = isset($_GET['city']) && !empty($_GET['city']) ? $_GET['city'] : null;

$sql_conditions = " WHERE l.vehicle_type_id = ? ";
$params = [$vehicle_type];
$types = 'i';

$sortOptions = [
    'price_asc' => ' ORDER BY l.price ASC',
    'price_desc' => ' ORDER BY l.price DESC',
    'date_desc' => ' ORDER BY l.created_at DESC'
];

$sortKey = $_POST['option'] ?? 'price_asc'; // default sort
$selectedOption = $sortOptions[$sortKey] ?? $sortOptions['date_desc'];

if ($make_id !== null) {
    $sql_conditions .= " AND l.make_id = ?";
    $params[] = $make_id;
    $types .= 'i';
}
if ($model_id !== null) {
    $sql_conditions .= " AND l.model_id = ?";
    $params[] = $model_id;
    $types .= 'i';
}
if ($year_from !== null) {
    $sql_conditions .= " AND l.year >= ?";
    $params[] = $year_from;
    $types .= 'i';
}
if ($year_to !== null) {
    $sql_conditions .= " AND l.year <= ?";
    $params[] = $year_to;
    $types .= 'i';
}
if ($price_from !== null) {
    $sql_conditions .= " AND l.price >= ?";
    $params[] = $price_from;
    $types .= 'd';
}
if ($price_to !== null) {
    $sql_conditions .= " AND l.price <= ?";
    $params[] = $price_to;
    $types .= 'd';
}
if ($fuel_type !== null) {
    $sql_conditions .= " AND l.fuel_type = ?";
    $params[] = $fuel_type;
    $types .= 's';
}
if ($body_type !== null && $vehicle_type == 1) { 
    $sql_conditions .= " AND l.body_type = ?";
    $params[] = $body_type;
    $types .= 's';
}
if ($city !== null) {
    $sql_conditions .= " AND l.city = ?";
    $params[] = $city;
    $types .= 's';
}

$sql_count = "SELECT COUNT(l.id) as total 
                 FROM listings l 
                 JOIN makes mk ON l.make_id = mk.id 
                 JOIN models md ON l.model_id = md.id "
                . $sql_conditions;

$stmt_count = $conn->prepare($sql_count);
$total_listings = 0;
$total_pages = 0;

if ($stmt_count) {
    if (count($params) > 0 && !empty($types)) {
        $stmt_count->bind_param($types, ...$params);
    }
    if ($stmt_count->execute()) {
        $result_count = $stmt_count->get_result();
        if ($result_count) {
            $total_listings_row = $result_count->fetch_assoc();
            $total_listings = $total_listings_row ? intval($total_listings_row['total']) : 0;
        }
    }
    if ($stmt_count) $stmt_count->close();
}

if ($total_listings > 0) {
    $total_pages = ceil($total_listings / $listings_per_page);
} else {
    $total_pages = 0; 
}

if ($current_page > $total_pages && $total_pages > 0) {
    $current_page = $total_pages;
    $offset = ($current_page - 1) * $listings_per_page;
} elseif ($current_page < 1) {
     $current_page = 1;
     $offset = 0;
}

$sql_listings = "SELECT l.id, l.year, l.price, l.mileage, l.body_type, l.fuel_type, l.phone, l.image_url, l.city,
                        mk.name as make_name, md.name as model_name 
                 FROM listings l 
                 JOIN makes mk ON l.make_id = mk.id 
                 JOIN models md ON l.model_id = md.id "
               . $sql_conditions 
               . $selectedOption
               . " LIMIT ? OFFSET ?";

$params_listings = $params;
$types_listings = $types;
$params_listings[] = $listings_per_page;
$types_listings .= 'i';
$params_listings[] = $offset;
$types_listings .= 'i';

$stmt_listings = $conn->prepare($sql_listings);
$result_listings_data = null; 

if ($stmt_listings) {
    if (count($params_listings) > 0 && !empty($types_listings)) { 
        $stmt_listings->bind_param($types_listings, ...$params_listings);
    }
    if ($stmt_listings->execute()) {
        $result_listings_data = $stmt_listings->get_result();
    }
}
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
                <a href="../index.php"><img id="logo" src="../img/logo.png" alt="logo"></a>
            </div>
            <div class="right">
                <a href="paskyra/dashboard.php">Mano paskyra</a>
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
        <h1><?= $vehicle_type == 1 ? 'Automobilių' : 'Motociklų' ?> paieškos rezultatai</h1>

    <form method="POST">
        <div class="radio-container">
            <div class="radio-header"><b id="sort_header">Skelbimų rušiavimas</b></div>
                <div class="radio-options">
                    <label class="radio-label">
                    <input type="radio" name="option" value="price_asc" onchange="this.form.submit()" <?= $sortKey == 'price_asc' ? 'checked' : '' ?>> Pigiausi viršuje
                    </label><br>
                    <label class="radio-label">
                    <input type="radio" name="option" value="price_desc" onchange="this.form.submit()" <?= $sortKey == 'price_desc' ? 'checked' : '' ?>> Brangiausi viršuje
                    </label><br>
                    <label class="radio-label">
                    <input type="radio" name="option" value="date_desc" onchange="this.form.submit()" <?= $sortKey == 'date_desc' ? 'checked' : '' ?>> Naujausi skelbimai viršuje
                    </label>
                </div>
        </div>
    </form>

        
        <?php if ($result_listings_data && $result_listings_data->num_rows > 0): ?>
            <div class="results-container">
                <?php while ($row = $result_listings_data->fetch_assoc()): ?>
                    <div class="car-item">
                        <?php 
                        $image_display_source = '../img/default.png'; 
                        if (!empty($row['image_url'])) {
                            $image_path_from_db_results = $row['image_url'];
                            $is_external_image_results = (strpos($image_path_from_db_results, 'http') === 0 || strpos($image_path_from_db_results, 'https') === 0);

                            if ($is_external_image_results) {
                                $image_display_source = htmlspecialchars($image_path_from_db_results);
                            } else {
                                $local_image_path_relative_to_root_results = ltrim($image_path_from_db_results, '/');
                                if (!empty($local_image_path_relative_to_root_results)) {
                                    $image_display_source = '../' . htmlspecialchars($local_image_path_relative_to_root_results);
                                }
                            }
                        }
                        ?>
                        <img src="<?= $image_display_source ?>" 
                             alt="<?= htmlspecialchars($row['make_name'].' '.$row['model_name']) ?>"
                             onerror="this.onerror=null; this.src='../img/default.png';">
                        
                        <div class="car-info">
                            <h2><?= htmlspecialchars($row['make_name'].' '.$row['model_name']) ?></h2>
                            <div class="car-specs">
                                <p><strong>Metai:</strong> <?= htmlspecialchars($row['year']) ?></p>
                                <p><strong>Kaina:</strong> <?= number_format((float)$row['price'], 0, ',', ' ') ?> €</p>
                                <p><strong>Rida:</strong> <?= $row['mileage'] !== null ? number_format((int)$row['mileage'], 0, ',', ' ') . ' km' : 'Nenurodyta' ?></p>
                                <?php if ($vehicle_type == 1 && !empty($row['body_type'])): ?>
                                    <p><strong>Kėbulo tipas:</strong> <?= htmlspecialchars($row['body_type']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($row['fuel_type'])): ?>
                                <p><strong>Kuro tipas:</strong> <?= htmlspecialchars($row['fuel_type']) ?></p>
                                <p><strong>Miestas:</strong> <?= htmlspecialchars($row['city']) ?></p>
                                <?php endif; ?>
                                
                            </div>
                            <?php if (!empty($row['phone'])): ?>
                            <p class="contact-info"><strong>Kontaktai:</strong> <?= htmlspecialchars($row['phone']) ?></p>
                            <?php endif; ?>
                            <a href="skelbimas.php?id=<?= $row['id'] ?>" class="details-btn">Peržiūrėti skelbimą</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-results">
                 <p>Pagal Jūsų paieškos kriterijus rezultatų nerasta.</p>
                <a href="../index.php" class="back-btn">Grįžti į paiešką</a>
            </div>
        <?php endif; ?>

       <?php if ($total_pages > 1): ?>
           <div class="pages">
               <?php if ($current_page > 1): ?>
                   <a href="?<?= $query_string_for_pages ?>puslapis=1">&laquo; Pirmas</a>
                   <a href="?<?= $query_string_for_pages ?>puslapis=<?= $current_page - 1 ?>">&lsaquo; Ankstesnis</a>
               <?php endif; ?>

               <?php 
               $num_links_around_current = 2;
               $start_page = max(1, $current_page - $num_links_around_current);
               $end_page = min($total_pages, $current_page + $num_links_around_current);

               if ($start_page > 1) {
                   echo '<a href="?'.$query_string_for_pages.'puslapis=1">1</a>';
                   if ($start_page > 2) echo '<span>...</span>';
               }

               for ($i = $start_page; $i <= $end_page; $i++): ?>
                   <?php if ($i == $current_page): ?>
                       <span class="current-page"><?= $i ?></span>
                   <?php else: ?>
                       <a href="?<?= $query_string_for_pages ?>puslapis=<?= $i ?>"><?= $i ?></a>
                   <?php endif; ?>
               <?php endfor; 

               if ($end_page < $total_pages) {
                    if ($end_page < $total_pages - 1) echo '<span>...</span>';
                    echo '<a href="?'.$query_string_for_pages.'puslapis='.$total_pages.'">'.$total_pages.'</a>';
               }
               ?>

               <?php if ($current_page < $total_pages): ?>
                   <a href="?<?= $query_string_for_pages ?>puslapis=<?= $current_page + 1 ?>">Kitas &rsaquo;</a>
                   <a href="?<?= $query_string_for_pages ?>puslapis=<?= $total_pages ?>">Paskutinis &raquo;</a>
               <?php endif; ?>
           </div>
       <?php endif; ?>

    </div>
</body>
</html>
<?php
if (isset($stmt_listings) && $stmt_listings instanceof mysqli_stmt) {
    $stmt_listings->close();
}
if (isset($conn) && $conn instanceof mysqli && $conn->thread_id) {
    $conn->close();
}
?>