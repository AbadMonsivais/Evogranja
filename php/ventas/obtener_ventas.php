<?php
$servername = "localhost";
$username = "tu_usuario";
$password = "tu_contraseña";
$dbname = "ventas_animales";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT * FROM ventas";
$result = $conn->query($sql);

$ventas = [];

if ($result->num_rows > 0) {
    // Salida de cada fila
    while($row = $result->fetch_assoc()) {
        $ventas[] = $row;
    }
}

echo json_encode($ventas);

// Cerrar conexión
$conn->close();
?>
