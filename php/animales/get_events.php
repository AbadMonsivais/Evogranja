<?php
session_start();
include '../../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM animales WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = [
        'title' => $row['nombre'],
        'start' => $row['fecha_inseminacion'],
        'end' => $row['fecha_parto_estimado'],
        'backgroundColor' => $row['color'],
        'borderColor' => $row['color'],
        'extendedProps' => [
            'raza' => $row['raza'],
            'sementales' => [], // Asume que agregarás la lógica para obtener los sementales si es necesario
            'diasGestacion' => 114 // O el valor que corresponda
        ]
    ];
}

echo json_encode($events);

$stmt->close();
$conn->close();
?>
