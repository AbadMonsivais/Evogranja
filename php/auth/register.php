
<?php
require_once __DIR__ . '/../../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../login.html');
    exit;
}

$username = $_POST['username'] ?? '';
$passwordOriginal = $_POST['password'] ?? '';
$email = $_POST['email'] ?? '';

if ($username === '' || $passwordOriginal === '' || $email === '') {
    exit('Completa todos los campos obligatorios.');
}

$password = password_hash($passwordOriginal, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    exit('Error al preparar el registro: ' . $conn->error);
}

$stmt->bind_param("sss", $username, $password, $email);

if ($stmt->execute()) {
    header('Location: ../../login.html?registro=exitoso');
    exit;
} else {
    if ($stmt->errno === 1062) {
        echo "Ese usuario o correo ya está registrado.";
    } else {
        echo "Error al registrar el usuario.";
    }
}

$stmt->close();
?>
