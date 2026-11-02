<?php
session_start();
require_once __DIR__ . '/../../config/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.html');
    exit;
}

$user_id = $_SESSION['user_id'];
$mensaje = '';
$tipo_alerta = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_username = trim($_POST['username'] ?? '');
    $nueva_password = $_POST['password'] ?? '';
    
    // 1. Actualizar nombre de usuario si se envió
    if (!empty($nuevo_username)) {
        $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
        $stmt->bind_param("si", $nuevo_username, $user_id);
        if ($stmt->execute()) {
            $_SESSION['username'] = $nuevo_username;
            $mensaje = "Datos actualizados correctamente. ";
            $tipo_alerta = "success";
        }
        $stmt->close();
    }

    // 2. Actualizar contraseña si se escribió una nueva
    if (!empty($nueva_password)) {
        $hashed_password = password_hash($nueva_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        $stmt->execute();
        $stmt->close();
        $mensaje .= "Contraseña actualizada. ";
        $tipo_alerta = "success";
    }

    // 3. Subir o cambiar foto de perfil
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
        $fileName = $_FILES['profile_pic']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedExtensions)) {
            $newFileName = 'user_' . $user_id . '_' . time() . '.' . $fileExtension;
            $uploadFileDir = '../../uploads/profiles/';
            
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }
            
            $dest_path = $uploadFileDir . $newFileName;
            $db_path = 'uploads/profiles/' . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
                $stmt->bind_param("si", $db_path, $user_id);
                $stmt->execute();
                $stmt->close();
                $mensaje .= "Foto de perfil actualizada. ";
                $tipo_alerta = "success";
            }
        } else {
            $mensaje = "Error: Formato de imagen no permitido (solo JPG, PNG, GIF).";
            $tipo_alerta = "error";
        }
    }
}

// Obtener datos actuales para rellenar el formulario
$stmt = $conn->prepare("SELECT username, email, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $profile_pic);
$stmt->fetch();
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Cuenta - Evogranja</title>
    <link rel="icon" href="../../img/logoF-A.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <header class="evogranja-header">
        <div class="header-brand">
            <img src="../../img/logoFi.png" alt="Logo Evogranja" class="logo">
        </div>
        <div style="text-align: center;">
            <h1 style="margin: 0; font-size: 24px; color: white;">Configuración de Cuenta</h1>
        </div>
    </header>

    <main style="display: flex; justify-content: center; align-items: center; padding: 30px;">
        <div id="records-container" style="width: 100%; max-width: 600px;">
            <h2 id="records-title">Editar Datos Personales</h2>
            
            <?php if (!empty($mensaje)): ?>
                <div style="background: <?php echo $tipo_alerta === 'success' ? '#e2f0d9' : '#f8d7da'; ?>; color: <?php echo $tipo_alerta === 'success' ? '#385723' : '#721c24'; ?>; padding: 12px; margin-bottom: 20px; border-radius: 6px; border: 1px solid <?php echo $tipo_alerta === 'success' ? '#c3e6cb' : '#f5c6cb'; ?>;">
                    <?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>

            <form action="configuracion.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <label for="username" style="display: block; margin-bottom: 5px; font-weight: bold;">Nombre de usuario:</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username ?? ''); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
                </div>

                <div>
                    <label for="email" style="display: block; margin-bottom: 5px; font-weight: bold;">Correo electrónico (No editable):</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($email ?? ''); ?>" disabled style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background: #e9ecef; color: #6c757d; box-sizing: border-box;">
                </div>

                <div>
                    <label for="password" style="display: block; margin-bottom: 5px; font-weight: bold;">Nueva contraseña:</label>
                    <input type="password" id="password" name="password" placeholder="Dejar en blanco si no deseas cambiarla" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
                </div>

                <div>
                    <label for="profile_pic" style="display: block; margin-bottom: 5px; font-weight: bold;">Foto de perfil:</label>
                    <input type="file" id="profile_pic" name="profile_pic" accept="image/*" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 5px; background: #fff; box-sizing: border-box;">
                </div>

                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <button type="submit" class="action-button search" style="flex: 1; margin: 0;">Guardar Cambios</button>
                    <a href="perfil.php" class="action-button" style="flex: 1; text-align: center; text-decoration: none; margin: 0; background-color: #6c757d; display: inline-flex; align-items: center; justify-content: center;">Cancelar</a>
                </div>
            </form>

            <div style="text-align: center; margin-top: 20px;">
                <a href="../../index.html" style="color: #075b65; text-decoration: none; font-weight: bold;">← Volver al Inicio</a>
            </div>
        </div>
    </main>

    <script>
        // Sincronizar el modo oscuro con el resto de la aplicación
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark-mode');
        }
    </script>
</body>
</html>