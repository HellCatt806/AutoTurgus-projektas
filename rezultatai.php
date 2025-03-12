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
    $sqlas = sqlSetup();
    echo $sqlas;
    $result = $conn->query($sqlas);


    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="car-item">';
            echo '<h3>' . '<a href=skelbimas.php>' . htmlspecialchars($row["modelis"]) . '</h3>';
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
function sqlSetup(){
    $sql = "SELECT * FROM listing where ";
    $brandChoice = false;
    switch($_GET['marke']){
    case 'Audi':
        $sql .= 'marke = "Audi"';
        $brandChoice = true;
    break;
    case 'BMW':
        $sql .= 'modelis = "BMW"';
        $brandChoice = true;
    break;
    case 'Volkswagen':
        $sql .= 'marke = "Volkswagen"';
        $brandChoice = true;
    break;
    case 'Mazda':
        $sql .= 'marke = "Mazda"';
        $brandChoice = true;
    break;
    case 'Nissan':
        $sql .= 'marke = "Nissan"';
        $brandChoice = true;
    break;
    case 'Toyota':
        $sql .= 'marke = "Toyota"';
        $brandChoice = true;
    break;
    case 'Porsche':
        $sql .= 'marke = "Porsche"';
        $brandChoice = true;
    break;
    default:
        $sql = "SELECT * FROM listing";
    break;
    }
    if($brandChoice){
        $sqlOld = $sql;
        $sql .= " AND ";
        switch($_GET['modelis']){
        //Audi
        case "A1":
            $sql .= 'modelis = "A1"';
        break;
        case "A3":
            $sql .= 'modelis = "A1"';
        break;
        case "A4":
            $sql .= 'modelis = "A1"';
        break;
        case "A6":
            $sql .= 'modelis = "A1"';
        break;
        case "A8":
            $sql .= 'modelis = "A1"';
        break;
        case "Q3":
            $sql .= 'modelis = "A1"';
        break;
        case "Q5":
            $sql .= 'modelis = "A1"';
        break;
        case "Q7":
            $sql .= 'modelis = "A1"';
        break;
        case "A1":
            $sql .= 'modelis = "RS4"';
        break;
        case "A1":
            $sql .= 'modelis = "RS6"';
        break;

        //BMW
        case "1 serija":
            $sql .= 'modelis = "1 serija"';
        break;
        case "3 serija":
            $sql .= 'modelis = "3 serija"';
        break;
        case "5 serija":
            $sql .= 'modelis = "5 serija"';
        break;
        case "M3":
            $sql .= 'modelis = "M3"';
        break;
        case "M5":
            $sql .= 'modelis = "M5"';
        break;
        case "X3":
            $sql .= 'modelis = "X3"';
        break;
        case "X5":
            $sql .= 'modelis = "X5"';
        break;
        case "X6":
            $sql .= 'modelis = "X6"';
        break;
        case "Xi":
            $sql .= 'modelis = "Xi"';
        break;
        default:
            $sql = $sqlOld;
        break;
        }

        if(!empty($_GET['kainanuo']) || !empty($_GET['kainaiki'])){
            $sql .= ' AND kaina BETWEEN ' . $_GET['kainanuo'] . ' AND ' . $_GET['kainaiki'];
        }
        $sql .= ";";
    }else{}
    return $sql;
}
?>