<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logoF-A.png" type="image/x-icon">
    <link rel="stylesheet" href="css/style2.css">
    <title>Inventario de Insumos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f0f8ff;
        }
        h1 {
            color: #003366;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #1a3d7c;
            color: white;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #dddddd;
        }
        th {
            background-color: #0056b3;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #2a5298;
        }
        tr:nth-child(odd) {
            background-color: #1a3d7c;
        }
        tr:hover {
            background-color: #004080;
        }
        button {
            background-color: #0073e6;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            width: 100%;
            margin-top: 10px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Inventario de Insumos (Alimentos y Medicinas)</h1>

    <?php
    // Conexión a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'evogranja');

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Insertar datos en la tabla si se envió el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nombre = $_POST["nombre"];
        $tipo = $_POST["tipo"];
        $cantidad = $_POST["cantidad"];
        $fecha = $_POST["fecha"];

        if ($nombre && $tipo && $cantidad && $fecha) {
            $sql = "INSERT INTO inventario (nombre, tipo, cantidad, fecha_vencimiento) VALUES ('$nombre', '$tipo', '$cantidad', '$fecha')";
            if ($conn->query($sql) === TRUE) {
                echo "<p style='color: green;'>Insumo agregado con éxito</p>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "<p style='color: red;'>Por favor, complete todos los campos</p>";
        }
    }

    // Mostrar insumos
    $result = $conn->query("SELECT * FROM inventario");
    ?>

    <form method="post" action="">
        <label for="nombre">Nombre del insumo:</label>
        <input type="text" id="nombre" name="nombre" placeholder="Ingrese el nombre del insumo">

        <label for="tipo">Tipo de insumo:</label>
        <select id="tipo" name="tipo">
            <option value="Alimento">Alimento</option>
            <option value="Medicina">Medicina</option>
        </select>

        <label for="cantidad">Cantidad:</label>
        <input type="number" id="cantidad" name="cantidad" placeholder="Ingrese la cantidad">

        <label for="fecha">Fecha de vencimiento:</label>
        <input type="date" id="fecha" name="fecha">

        <button type="submit">Agregar Insumo</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Fecha de Vencimiento</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['nombre']}</td>
                            <td>{$row['tipo']}</td>
                            <td>{$row['cantidad']}</td>
                            <td>{$row['fecha_vencimiento']}</td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No hay insumos en el inventario</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php $conn->close(); ?>

</body>
</html>
