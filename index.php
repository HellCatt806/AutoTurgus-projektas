<?php
require_once 'phpScript/config.php';
?>
<?php
$latest_listings = [];
$sql_latest = "SELECT l.id, l.year, l.price, l.image_url,
                      mk.name as make_name, md.name as model_name,
                      vt.name as vehicle_type_name
               FROM listings l
               JOIN makes mk ON l.make_id = mk.id
               JOIN models md ON l.model_id = md.id
               JOIN vehicle_types vt ON l.vehicle_type_id = vt.id
               ORDER BY l.created_at DESC
               LIMIT 5";

if (isset($conn)) {
    $result_latest = $conn->query($sql_latest);
    if ($result_latest && $result_latest->num_rows > 0) {
        while ($row_latest = $result_latest->fetch_assoc()) {
            $latest_listings[] = $row_latest;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/main.css">
    <title>AutoTurgus - Automobilių ir motociklų skelbimai</title>
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="user-menu">
            <div class="left">
                <a href="index.php"><img id="logo" src="img/logo.png" alt="logo"></a>
                <span id="greeting"><b>Sveikas, <?= htmlspecialchars($_SESSION['username']) ?>!</b></span>
            </div>
            <div class="right">
                 <a href="pages/paskyra/dashboard.php" class="right_lean"><b>Mano paskyra</b></a> 
                <a href="pages/naujas_skelbimas.php" class="right_lean"><b>Pridėti skelbimą</b></a>
                <a href="phpScript/logout.php" class="logout-btn"><b>Atsijungti</b></a>
            </div>
        </div>
    <?php else: ?>
        <div class="top-menu">
            <div class="left">
                <a href="index.php"><img id="logo" src="img/logo.png" alt="logo"></a>
            </div>
            <div class="right">
                <button onclick="location.href='pages/login.php'">Prisijungti</button>
                <button onclick="location.href='pages/register.php'">Registruotis</button>
            </div>
        </div>
    <?php endif; ?>

    <div class="search-container">
        <div class="search-tabs">
            <button class="active" data-vehicle-type="1">Automobiliai</button>
            <button data-vehicle-type="2">Motociklai</button>
        </div>
        
        <form action="pages/rezultatai.php" method="get" id="search-form">
            <input type="hidden" name="vehicle_type" id="vehicle_type" value="1">
            
            <div class="form-row">
                <select class="search-options" id="make" name="make">
                    <option value="">Pasirinkite markę</option>
                    <?php
                    $result = $conn->query("SELECT id, name FROM makes WHERE vehicle_type_id = 1 ORDER BY name");
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                    }
                    ?>
                </select>

                <select class="search-options" id="model" name="model" disabled>
                    <option value="">Pasirinkite modelį</option>
                </select>
            </div>

            <div class="form-row">
                <input type="number" class="search-options" name="year_from" placeholder="Metai nuo" min="1980">
                <input type="number" class="search-options" name="year_to" placeholder="Metai iki" min="1980">
            </div>

            <div class="form-row">
                <input type="number" class="search-options" name="price_from" placeholder="Kaina nuo €" min="0">
                <input type="number" class="search-options" name="price_to" placeholder="Kaina iki €" min="0">
            </div>

            <div class="form-row">
                <select class="search-options" name="fuel_type">
                    <option value="">Kuro tipas</option>
                    <option value="benzinas">Benzinas</option>
                    <option value="dyzelis">Dyzelis</option>
                    <option value="elektra">Elektra</option>
                    <option value="hibridas">Hibridas</option>
                </select>

                <div id="car-specific-fields">
                    <select class="search-options" name="body_type">
                        <option value="">Kėbulo tipas</option>
                        <option value="sedanas">Sedanas</option>
                        <option value="universalas">Universalas</option>
                        <option value="hečbekas">Hečbekas</option>
                        <option value="coupe">Coupe</option>
                    </select>
                </div>
            </div>

                <div class="form-row">
                <select class="search-options" name="city">
                    <option value="">Visi miestai</option>
                    <option value="Vilnius">Vilnius</option>
                    <option value="Kaunas">Kaunas</option>
                    <option value="Klaipėda">Klaipėda</option>
                    <option value="Šiauliai">Šiauliai</option>
                    <option value="Panevėžys">Panevėžys</option>
                    <option value="Alytus">Alytus</option>
                    <option value="Marijampolė">Marijampolė</option>
                    <option value="Mažeikiai">Mažeikiai</option>
                    <option value="Jonava">Jonava</option>
                    <option value="Utena">Utena</option>
                    <option value="Kėdainiai">Kėdainiai</option>
                    <option value="Telšiai">Telšiai</option>
                    <option value="Tauragė">Tauragė</option>
                    <option value="Ukmergė">Ukmergė</option>
                    <option value="Visaginas">Visaginas</option>
                    <option value="Kita">Kita</option>
                </select>
                </div>

            <button type="submit" class="search-button">Ieškoti</button>
        </form>
    </div>
    <?php if (!empty($latest_listings)): ?>
    <div class="container latest-listings-row-section">
        <h2 style="text-align: center; color: #2c3e50; margin-top: 30px; margin-bottom: 25px;">Naujausi skelbimai</h2>
        <div class="latest-listings-row-container">
            <?php foreach ($latest_listings as $listing): ?>
                <div class="latest-listing-item">
                    <?php
                    $image_display_source = 'img/default.png';
                    if (!empty($listing['image_url'])) {
                        $image_path_from_db = $listing['image_url'];
                        $is_external_image = (strpos($image_path_from_db, 'http') === 0 || strpos($image_path_from_db, 'https') === 0);
                        if ($is_external_image) {
                            $image_display_source = htmlspecialchars($image_path_from_db);
                        } else {
                            $local_image_path = ltrim($image_path_from_db, '/');
                            if (!empty($local_image_path) && file_exists($local_image_path)) {
                                $image_display_source = htmlspecialchars($local_image_path);
                            } elseif (!empty($local_image_path) && file_exists('uploads/' . basename($local_image_path))) {
                               $image_display_source = htmlspecialchars('uploads/' . basename($local_image_path));
                            }
                        }
                    }
                    ?>
                    <a href="pages/skelbimas.php?id=<?= $listing['id'] ?>" class="latest-listing-link">
                        <img src="<?= $image_display_source ?>"
                             alt="<?= htmlspecialchars($listing['make_name'].' '.$listing['model_name']) ?>"
                             class="latest-listing-image"
                             onerror="this.onerror=null; this.src='img/default.png';">
                        <div class="latest-listing-info">
                            <p class="latest-listing-title" title="<?= htmlspecialchars($listing['make_name'].' '.$listing['model_name']) ?>"><?= htmlspecialchars($listing['make_name'].' '.$listing['model_name']) ?></p>
                            <p class="latest-listing-price"><?= number_format((float)$listing['price'], 0, ',', ' ') ?> €</p>
                            <p class="latest-listing-year"><?= htmlspecialchars($listing['year']) ?> m. | <span style="font-size:0.9em; color: #6c757d;"><?= htmlspecialchars($listing['vehicle_type_name']) ?></span></p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
<script>
    const PHP_SCRIPTS_ROOT_PATH = 'phpScript/';
</script>
    <script src="js/script.js"></script>
    <script src="js/greeting.js"></script>
</body>
</html>