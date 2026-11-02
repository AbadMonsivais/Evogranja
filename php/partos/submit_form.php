
<?php
session_start();
require_once __DIR__ . '/../../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo "Usuario no autenticado.";
    exit;
}

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo "No se recibieron datos válidos.";
    exit;
}

$nombre = trim($data['nombre'] ?? '');
$especie = $data['especie'] ?? '';
$raza = trim($data['raza'] ?? '');
$fecha_inseminacion = $data['fecha_inseminacion'] ?? '';
$color = $data['color'] ?? '#ff0000';
$sementales = $data['sementales'] ?? [];

// Días aproximados de gestación según la especie.
$diasGestacion = [
    'Cerdo' => 114,
    'Borrego' => 150,
    'Vaca' => 283,
    'Chiva' => 150
];

// Validar los datos básicos.
if (
    $nombre === '' ||
    $raza === '' ||
    !isset($diasGestacion[$especie]) ||
    !is_array($sementales)
) {
    http_response_code(400);
    echo "Verifica el nombre, la especie, la raza y los sementales.";
    exit;
}

// Validar y calcular la fecha estimada de parto.
$fecha = DateTime::createFromFormat('!Y-m-d', $fecha_inseminacion);

if (
    !$fecha ||
    $fecha->format('Y-m-d') !== $fecha_inseminacion
) {
    http_response_code(400);
    echo "La fecha de inseminación no es válida.";
    exit;
}

$fecha->modify('+' . $diasGestacion[$especie] . ' days');
$fecha_parto_estimado = $fecha->format('Y-m-d');

// Guardar el registro del animal.
$sql = "INSERT INTO animales
        (nombre, especie, raza, fecha_inseminacion,
         fecha_parto_estimado, user_id, color)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo "No se pudo preparar el registro del animal.";
    exit;
}

$stmt->bind_param(
    "sssssis",
    $nombre,
    $especie,
    $raza,
    $fecha_inseminacion,
    $fecha_parto_estimado,
    $user_id,
    $color
);

if (!$stmt->execute()) {
    http_response_code(500);
    echo "No se pudo guardar el registro del animal.";
    $stmt->close();
    $conn->close();
    exit;
}

$animal_id = $stmt->insert_id;
$stmt->close();

// Guardar los sementales asociados.
$sql_semental = "INSERT INTO sementales (animal_id, semental, raza)
                 VALUES (?, ?, ?)";

$stmt_semental = $conn->prepare($sql_semental);

if (!$stmt_semental) {
    http_response_code(500);
    echo "El animal se guardó, pero no se pudo preparar el registro de sus sementales.";
    $conn->close();
    exit;
}

foreach ($sementales as $semental) {
    $nombre_semental = trim($semental['nombre'] ?? '');
    $raza_semental = trim($semental['raza'] ?? '');

    // No guardar entradas vacías.
    if ($nombre_semental === '' && $raza_semental === '') {
        continue;
    }

    $stmt_semental->bind_param(
        "iss",
        $animal_id,
        $nombre_semental,
        $raza_semental
    );

    if (!$stmt_semental->execute()) {
        http_response_code(500);
        echo "El animal se guardó, pero hubo un error al guardar un semental.";
        $stmt_semental->close();
        $conn->close();
        exit;
    }
}

$stmt_semental->close();
$conn->close();

echo "Registro agregado exitosamente. Fecha estimada de parto: "
     . $fecha_parto_estimado;
?>
