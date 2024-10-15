<?php
$servername = "data"; // Nom du conteneur de la base de données
$username = "user";
$password = "password";
$dbname = "testdb";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lecture : récupérer des données
$sql = "SELECT * FROM test_table";
$result = $conn->query($sql);

// Écriture : insérer des données
$insert_sql = "INSERT INTO test_table (name) VALUES ('Test " . rand(1, 100) . "')";
$conn->query($insert_sql);

// Affichage des résultats
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["name"]. "<br>";
    }
} else {
    echo "0 results";
}

$conn->close();
?>
