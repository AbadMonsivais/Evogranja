<?php
require('fpdf/fpdf.php'); // Asegúrate de incluir la biblioteca FPDF en tu proyecto

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "evogranja";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$peso = $_POST['peso'];
$genero = $_POST['genero'];
$raza = $_POST['raza'];
$etapa = $_POST['etapa'];
$corral = $_POST['corral'];
$vacunas = isset($_POST['vacunas']) ? $_POST['vacunas'] : [];

// Insertar los datos en la base de datos
$sql = "INSERT INTO historial (nombre, peso, genero, raza, etapa, corral) 
        VALUES ('$nombre', $peso, '$genero', '$raza', '$etapa', '$corral')";

if ($conn->query($sql) === TRUE) {
    $animal_id = $conn->insert_id; // ID del registro insertado

    // Insertar las vacunas en otra tabla, si es necesario
    foreach ($vacunas as $vacuna) {
        $sql_vacuna = "INSERT INTO vacunas (animal_id, vacuna) VALUES ($animal_id, '$vacuna')";
        $conn->query($sql_vacuna);
    }

    // Crear el PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Historial Medico - ' . $nombre, 0, 1, 'C');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'Nombre: ' . $nombre, 0, 1);
    $pdf->Cell(0, 10, 'Peso: ' . $peso . ' kg', 0, 1);
    $pdf->Cell(0, 10, 'Genero: ' . ucfirst($genero), 0, 1);
    $pdf->Cell(0, 10, 'Raza: ' . $raza, 0, 1);
    $pdf->Cell(0, 10, 'Etapa de crecimiento: ' . ucfirst($etapa), 0, 1);
    $pdf->Cell(0, 10, 'Corral: ' . ucfirst($corral), 0, 1);
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Vacunas:', 0, 1);

    $pdf->SetFont('Arial', '', 12);
    foreach ($vacunas as $vacuna) {
        $pdf->Cell(0, 10, '- ' . $vacuna, 0, 1);
    }

    // Descargar el PDF automáticamente
    $file_name = 'Historial_Medico_' . $nombre . '.pdf';
    $pdf->Output('D', $file_name);
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
