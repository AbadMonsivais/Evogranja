
<?php
session_start();

require_once __DIR__ . '/../../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../login.html');
    exit;
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    exit('Ingresa tu usuario y contraseña.');
}

// Buscar al usuario
$query = "SELECT id, username, password FROM users WHERE username = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    exit('Error al preparar la consulta.');
}

$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $db_username, $hashed_password);
    $stmt->fetch();

    if (password_verify($password, $hashed_password ?? '')) {
        session_regenerate_id(true);

        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $db_username;

        $stmt->close();
        $conn->close();

        // Ir al calendario principal de Evogranja
        header('Location: ../../index.html');
        exit;
    }
}

$stmt->close();
$conn->close();

echo "Usuario o contraseña incorrectos.";
?>
