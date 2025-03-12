<!DOCTYPE HTML>
<html lang="lt">
    <head>
        <title>AutoTurgus</title>
        <link rel="stylesheet" href="rezultatai_stilius.css">
    </head>
    <body></body>
</html>
<?php
if (!empty($_GET)) {
    print_r("Info pasirinkta pagrindinej formoj<br>");
    echo "<h2>Gauti duomenys:</h2>";
    echo "<pre>";
    print_r($_GET['marke']);
    print_r(" ");
    print_r($_GET['modelis']);
    print_r("<br><br><br>");
    echo "<h2>Pilnas DB elementu sarasas:</h2>";
    $conn = connectDB();

    $sql = "SELECT * FROM listing"; // Pakeisk su savo lentele
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="car-item">';
            echo '<h3>' . htmlspecialchars($row["modelis"]) . '</h3>';
            echo '<p>Kaina: ' . htmlspecialchars($row["kaina"]) . ' €</p>';
            echo '<p>Telefono numeris: ' . htmlspecialchars($row["tel_nr"]);
            echo '<img alt="masina" src="' . htmlspecialchars($row["tel_nr"]) . '">';
            echo '</div>';
        }
    }else{
        $conn->close(); // Uždaryk ryšį po naudojimo
    }

}

function connectDB() {
    $servername = "127.0.0.1"; // Arba 127.0.0.1
    $username = "root";        // XAMPP/MAMP/LAMP/WAMP dažniausiai "root"
    $password = "";      // Jei yra slaptažodis, įrašyk
    $dbname = "projektukas";

    // Sukuriam prisijungimą
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Tikrinam prisijungimą
    if ($conn->connect_error) {
        die("Prisijungti nepavyko: " . $conn->connect_error);
    }

    return $conn; // Gražinam prisijungimo objektą
}

?>