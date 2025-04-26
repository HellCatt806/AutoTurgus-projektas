<?php
require_once 'phpScript/config.php';
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
            <span>Sveikas, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
            <a href="pages/naujas_skelbimas.php" class="add-listing-btn">Pridėti skelbimą</a>
            <a href="phpScript/logout.php" class="logout-btn">Atsijungti</a>
        </div>
    <?php else: ?>
        <div class="top-menu">
            <button class="active" onclick="location.href='index.php'">Skelbimai</button>
            <button onclick="location.href='pages/login.php'">Prisijungti</button>
            <button onclick="location.href='pages/register.php'">Registruotis</button>
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

            <button type="submit" class="search-button">Ieškoti</button>
        </form>
    </div>

    <script src="js/script.js"></script>
</body>
</html>