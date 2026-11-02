<?php
session_start();
require_once __DIR__ . '/../../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.html');
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $profile_pic);
$stmt->fetch();
$stmt->close();
$conn->close();

$avatar = !empty($profile_pic) ? '../../' . $profile_pic : '../../img/logoF-A.png';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - Evogranja</title>
    <link rel="icon" href="../../img/logoF-A.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header class="evogranja-header">
        <div class="header-brand">
            <img src="../../img/logoFi.png" alt="Logo Evogranja" class="logo">
        </div>
        <div style="text-align: center;">
            <h1 style="margin: 0; font-size: 24px; color: white;">Perfil de Usuario</h1>
        </div>
    </header>

    <main style="display: flex; justify-content: center; align-items: center; padding: 30px;">
        <div id="records-container" style="width: 100%; max-width: 500px; text-align: center;">
            <h2 id="records-title" style="margin-bottom: 20px;">Información de la Cuenta</h2>
            
            <div style="margin: 20px 0;">
                <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Foto de perfil" style="width: 130px; height: 130px; border-radius: 50%; object-fit: cover; border: 3px solid #075b65; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
            </div>
            
            <div style="text-align: left; background: rgba(0,0,0,0.02); padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ddd;" class="profile-info-box">
                <p style="margin: 10px 0;"><strong>Nombre de usuario:</strong> <?php echo htmlspecialchars($username ?? ''); ?></p>
                <p style="margin: 10px 0;"><strong>Correo electrónico:</strong> <?php echo htmlspecialchars($email ?? ''); ?></p>
            </div>

            <div style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                <a href="configuracion.php" class="action-button search" style="text-decoration: none; display: inline-block;">Editar Configuración</a>
                <a href="../../index.html" class="action-button" style="text-decoration: none; display: inline-block; background-color: #6c757d;">Volver al Inicio</a>
            </div>
        </div>
    </main>

    <script>
        // Mantener el modo oscuro si estaba activo en el almacenamiento local
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }
    </script>
</body>
</html>