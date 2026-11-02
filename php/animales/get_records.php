<?php
session_start();
include '../../config/db_connect.php';

// Función para actualizar el estado de los registros
function updateAnimalStatus($conn) {
    $query = "UPDATE animales
              SET en_registros_anteriores = 1
              WHERE fecha_parto_estimado < CURDATE() AND en_registros_anteriores = 0";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $stmt->close();
}

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

// Actualizar el estado de los registros antes de recuperar los datos
updateAnimalStatus($conn);

$type = $_GET['type'] ?? 'active';
$especie = $_GET['especie'] ?? '';
$user_id = $_SESSION['user_id'];

$query = "SELECT a.*, GROUP_CONCAT(s.semental) as sementales, GROUP_CONCAT(s.raza) as razas_sementales
          FROM animales a
          LEFT JOIN sementales s ON a.id = s.animal_id
          WHERE a.user_id = ?";

if ($type == 'previous') {
    $query .= " AND a.en_registros_anteriores = 1";
} else {
    $query .= " AND a.en_registros_anteriores = 0";
}

if (!empty($especie)) {
    $query .= " AND a.especie = ?";
}

$query .= " GROUP BY a.id";

$stmt = $conn->prepare($query);
if (!empty($especie)) {
    $stmt->bind_param('is', $user_id, $especie); // Usamos 'is' si el tipo de especie es string
} else {
    $stmt->bind_param('i', $user_id);
}
$stmt->execute();
$result = $stmt->get_result();

$records = [];
while ($row = $result->fetch_assoc()) {
    $sementales = [];
    if ($row['sementales']) {
        $sementalesArray = explode(',', $row['sementales']);
        $razasSementalesArray = explode(',', $row['razas_sementales']);
        foreach ($sementalesArray as $index => $semental) {
            $sementales[] = ['nombre' => $semental, 'raza' => $razasSementalesArray[$index]];
        }
    }
    $records[] = [
        'nombre' => $row['nombre'],
        'especie' => $row['especie'],
        'raza' => $row['raza'],
        'fecha_inseminacion' => $row['fecha_inseminacion'],
        'fecha_parto_estimado' => $row['fecha_parto_estimado'],
        'color' => $row['color'],
        'sementales' => $sementales,
        'dias_restantes' => (strtotime($row['fecha_parto_estimado']) - strtotime(date('Y-m-d'))) / 86400
    ];
}

echo json_encode($records);

$stmt->close();
$conn->close();
?>
