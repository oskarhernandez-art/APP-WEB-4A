<?php
echo "Probando conexión...<br>";

include 'config/database.php';
$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "✅ Conexión exitosa!<br>";
    
    // Probar una consulta simple
    $stmt = $db->query("SHOW TABLES");
    $tablas = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tablas en la BD: " . implode(", ", $tablas);
} else {
    echo "❌ No se pudo conectar a la BD";
}
?>