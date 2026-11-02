<?php
session_start();
require_once __DIR__ . '/../../config/db_connect.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'loggedIn' => false,
        'message' => 'No hay una sesión activa.'
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Usuario';
$profile_pic = null;

// 1. Intentar consultar incluyendo profile_pic
$stmt = $conn->prepare("SELECT username, profile_pic FROM users WHERE id = ?");
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_username, $profile_pic);
    if ($stmt->fetch()) {
        $username = $db_username;
    }
    $stmt->close();
} else {
    // 2. Fallback de seguridad por si la columna profile_pic aún no existe en la tabla
    $stmt2 = $conn->prepare("SELECT username FROM users WHERE id = ?");
    if ($stmt2) {
        $stmt2->bind_param("i", $user_id);
        $stmt2->execute();
        $stmt2->bind_result($db_username);
        if ($stmt2->fetch()) {
            $username = $db_username;
        }
        $stmt2->close();
    }
}
$conn->close();

echo json_encode([
    'loggedIn' => true,
    'user_id' => $user_id,
    'username' => $username,
    'profile_pic' => $profile_pic
]);
?>