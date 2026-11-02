
<?php
session_start();
include '../../config/db_connect.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    die('Acceso denegado. Inicie sesión primero.');
}

$userId = $_SESSION['user_id']; // ID del usuario que inició sesión

// Validar conexión antes de usarla
if (!$conn) {
    die('No se pudo conectar a la base de datos. Verifica la configuración.');
}

// Consultar las ventas del usuario
$sql = "SELECT * FROM ventas WHERE user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Error en la preparación de la consulta: ' . $conn->error);
}
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logoF-A.png" type="image/x-icon">
    <title>Registro de Ventas por Peso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #ffffff;
            margin: 0;
            padding: 0;
            text-align: center;
        }
        
        h2, h3 {
            color: #00aaff;
        }
        
        form {
            background-color: #1e1e1e;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 170, 255, 0.5);
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
        }
        
        input:focus, select:focus {
            outline: none;
            border: 2px solid #00aaff;
        }
        
        button {
            background-color: #00aaff;
            color: #fff;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #0077cc;
        }
        
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        
        th, td {
            border: 1px solid #00aaff;
            padding: 10px;
            text-align: center;
        }
        
        th {
            background-color: #00aaff;
            color: #fff;
        }
        
        tbody tr:hover {
            background-color: rgba(0, 170, 255, 0.2);
        }
    </style>
    <script>
        let ventasTotales = [];

        function calcularPrecioTotal() {
            const nombre = document.getElementById("nombre").value;
            const raza = document.getElementById("raza").value;
            const peso = parseFloat(document.getElementById("peso").value);
            const precioPorKilo = parseFloat(document.getElementById("precioPorKilo").value);

            if (nombre && raza && !isNaN(peso) && !isNaN(precioPorKilo) && peso > 0 && precioPorKilo > 0) {
                const precioTotal = peso * precioPorKilo;
                document.getElementById("resultado").textContent = "Precio total: $" + precioTotal.toFixed(2);

                // Guardar la venta localmente
                const venta = {
                    nombre: nombre,
                    raza: raza,
                    peso: peso.toFixed(2),
                    precioPorKilo: precioPorKilo.toFixed(2),
                    precioTotal: precioTotal.toFixed(2)
                };
                ventasTotales.push(venta);
                mostrarVentasTotales();

                // Limpiar los campos del formulario
                document.getElementById("nombre").value = "";
                document.getElementById("raza").value = "";
                document.getElementById("peso").value = "";
                document.getElementById("precioPorKilo").value = "";
            } else {
                document.getElementById("resultado").textContent = "Por favor, ingrese valores válidos.";
            }
        }

        function mostrarVentasTotales() {
            const tablaVentas = document.getElementById("tablaVentas");
            tablaVentas.innerHTML = ""; // Limpiar la tabla antes de actualizar

            ventasTotales.forEach((venta, index) => {
                const fila = document.createElement("tr");
                fila.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${venta.nombre}</td>
                    <td>${venta.raza}</td>
                    <td>${venta.peso} kg</td>
                    <td>$${venta.precioPorKilo} /kg</td>
                    <td>$${venta.precioTotal}</td>
                `;
                tablaVentas.appendChild(fila);
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Registro de Ventas por Peso</h2>

    <form id="formularioVentas" action="guardar_venta.php" method="POST">
    <label for="nombre">Nombre del animal:</label>
    <input type="text" id="nombre" name="nombre" placeholder="Ingrese el nombre" required>

    <label for="raza">Raza:</label>
    <input type="text" id="raza" name="raza" placeholder="Ingrese la raza" required>

    <label for="peso">Peso (kg):</label>
    <input type="number" id="peso" name="peso" placeholder="Ingrese el peso" required>

    <label for="precioPorKilo">Precio por kilo ($):</label>
    <input type="number" id="precioPorKilo" name="precioPorKilo" placeholder="Ingrese el precio por kilo" required>

    <button type="submit">Guardar y Calcular</button>
</form>

<h3>Ventas Registradas</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Raza</th>
                <th>Peso</th>
                <th>Precio por Kilo</th>
                <th>Precio Total</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php $count = 1; while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $count++; ?></td>
                <td><?= htmlspecialchars($row['nombre_animal']); ?></td>
                <td><?= htmlspecialchars($row['raza']); ?></td>
                <td><?= htmlspecialchars($row['peso']); ?> kg</td>
                <td>$<?= htmlspecialchars($row['precio_por_kilo']); ?> /kg</td>
                <td>$<?= htmlspecialchars($row['precio_total']); ?></td>
                <td><?= htmlspecialchars($row['created_at']); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <h3>Análisis de Ventas</h3>
    <canvas id="ventasChart" width="400" height="200"></canvas>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        // Obtener los datos de PHP y convertirlos a un formato JavaScript
        const ventasData = <?php 
            $ventas = [];
            foreach ($result as $row) {
                $ventas[] = [
                    'fecha' => $row['created_at'],
                    'total' => $row['precio_total']
                ];
            }
            echo json_encode($ventas);
        ?>;

        // Procesar datos para el gráfico
        const labels = ventasData.map(venta => venta.fecha); // Fechas
        const data = ventasData.map(venta => parseFloat(venta.total)); // Totales

        // Configurar y renderizar el gráfico
        const ctx = document.getElementById('ventasChart').getContext('2d');
        new Chart(ctx, {
            type: 'line', // Puedes cambiar a 'bar', 'pie', etc.
            data: {
                labels: labels,
                datasets: [{
                    label: 'Ventas Totales ($)',
                    data: data,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Fecha'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total de Ventas ($)'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

</body>
</html>