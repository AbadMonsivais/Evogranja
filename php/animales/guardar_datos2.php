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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $raza = $_POST["raza"];
    $peso = $_POST["peso"];
    $precioPorKilo = $_POST["precioPorKilo"];
    $precioTotal = $peso * $precioPorKilo;

    // Insertar en la base de datos
    $sql = "INSERT INTO ventas (nombre, raza, peso, precioPorKilo, precioTotal)
            VALUES ('$nombre', '$raza', '$peso', '$precioPorKilo', '$precioTotal')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro guardado correctamente.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Cerrar conexión
$conn->close();
?>
