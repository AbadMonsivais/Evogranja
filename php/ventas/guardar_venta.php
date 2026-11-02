<?php
session_start();
include '../../config/db_connect.php'; // Archivo que contiene la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $raza = $_POST['raza'];
    $peso = $_POST['peso'];
    $precioPorKilo = $_POST['precioPorKilo'];
    $precioTotal = $peso * $precioPorKilo;
    $userId = $_SESSION['user_id']; // El ID del usuario que inició sesión

    $sql = "INSERT INTO ventas (nombre_animal, raza, peso, precio_por_kilo, precio_total, user_id)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdddi", $nombre, $raza, $peso, $precioPorKilo, $precioTotal, $userId);

    if ($stmt->execute()) {
        echo "Venta guardada exitosamente.";
    } else {
        echo "Error al guardar la venta: " . $conn->error;
    }
}
?>
