<?php
include '../../config/db_connect.php';

if (isset($_GET['name'])) {
    $name = $_GET['name'];

    // Inicia una transacción
    $conn->begin_transaction();

    try {
        // Obtener el ID del animal
        $query = "SELECT id FROM animales WHERE nombre = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->bind_result($animal_id);
        $stmt->fetch();
        $stmt->close();

        if ($animal_id) {
            // Eliminar los sementales asociados
            $query = "DELETE FROM sementales WHERE animal_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $animal_id);
            $stmt->execute();
            $stmt->close();

            // Eliminar el registro principal
            $query = "DELETE FROM animales WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $animal_id);
            $stmt->execute();
            $stmt->close();

            // Confirma la transacción
            $conn->commit();
            echo "Registro eliminado exitosamente.";
        } else {
            throw new Exception("Registro no encontrado.");
        }
    } catch (Exception $e) {
        // Revierte la transacción en caso de error
        $conn->rollback();
        echo "Error al eliminar el registro: " . $e->getMessage();
    }
} else {
    echo "Nombre de registro no proporcionado.";
}

$conn->close();
?>
