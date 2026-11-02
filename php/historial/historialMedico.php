<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logoF-A.png" type="image/x-icon">
    <title>Formulario de Información</title>
    <link rel="stylesheet" href="css/style2.css">
    <script>
        function agregarVacuna() {
    const vacunaInput = document.getElementById("vacunaInput");
    const listaVacunas = document.getElementById("listaVacunas");
    const vacunasInput = document.createElement("input");

    if (vacunaInput.value.trim() !== "") {
        const nuevaVacuna = document.createElement("li");
        nuevaVacuna.textContent = vacunaInput.value;
        listaVacunas.appendChild(nuevaVacuna);
        
        // Crear un input oculto para enviar las vacunas al servidor
        vacunasInput.type = "hidden";
        vacunasInput.name = "vacunas[]"; // Nombre como array
        vacunasInput.value = vacunaInput.value;
        document.querySelector("form").appendChild(vacunasInput);

        vacunaInput.value = "";
    }
}

        function verificarSalud() {
            const raza = document.getElementById("raza").value;
            const etapa = document.getElementById("etapa").value;
            const peso = parseFloat(document.getElementById("peso").value);
            const listaVacunas = document.getElementById("listaVacunas").getElementsByTagName("li").length;

            let mensajeSalud = "";

            // Definir criterios de salud según la raza y la etapa de crecimiento
            if (raza === "Cerdo") {
                if (etapa === "Cría") {
                    if (peso > 15 && listaVacunas >= 1) {
                        mensajeSalud = "El cerdo en etapa de cría está en buena salud.";
                    } else {
                        mensajeSalud = "El cerdo en etapa de cría necesita atención.";
                    }
                } else if (etapa === "desarrollo") {
                    if (peso > 35 && listaVacunas >= 2) {
                        mensajeSalud = "El cerdo en etapa de desarrollo está en buena salud.";
                    } else {
                        mensajeSalud = "El cerdo en etapa de desarrollo necesita atención.";
                    }
                } else if (etapa === "madurez") {
                    if (peso > 70 && listaVacunas >= 4) {
                        mensajeSalud = "El cerdo en etapa de madurez está en buena salud.";
                    } else {
                        mensajeSalud = "El cerdo en etapa de madurez necesita atención.";
                    }
                }
            } else if (raza === "Borrego") {
                if (etapa === "Cría") {
                    if (peso > 10 && listaVacunas >= 1) {
                        mensajeSalud = "El borrego en etapa de cría está en buena salud.";
                    } else {
                        mensajeSalud = "El borrego en etapa de cría necesita atención.";
                    }
                } else if (etapa === "desarrollo") {
                    if (peso > 30 && listaVacunas >= 2) {
                        mensajeSalud = "El borrego en etapa de desarrollo está en buena salud.";
                    } else {
                        mensajeSalud = "El borrego en etapa de desarrollo necesita atención.";
                    }
                } else if (etapa === "madurez") {
                    if (peso > 50 && listaVacunas >= 3) {
                        mensajeSalud = "El borrego en etapa de madurez está en buena salud.";
                    } else {
                        mensajeSalud = "El borrego en etapa de madurez necesita atención.";
                    }
                }
            } else if (raza === "Vaca") {
                if (etapa === "Cría") {
                    if (peso > 100 && listaVacunas >= 2) {
                        mensajeSalud = "La vaca en etapa de cría está en buena salud.";
                    } else {
                        mensajeSalud = "La vaca en etapa de cría necesita atención.";
                    }
                } else if (etapa === "desarrollo") {
                    if (peso > 250 && listaVacunas >= 3) {
                        mensajeSalud = "La vaca en etapa de desarrollo está en buena salud.";
                    } else {
                        mensajeSalud = "La vaca en etapa de desarrollo necesita atención.";
                    }
                } else if (etapa === "madurez") {
                    if (peso > 400 && listaVacunas >= 5) {
                        mensajeSalud = "La vaca en etapa de madurez está en buena salud.";
                    } else {
                        mensajeSalud = "La vaca en etapa de madurez necesita atención.";
                    }
                }
            } else if (raza === "Chivo") {
                if (etapa === "Cría") {
                    if (peso > 10 && listaVacunas >= 1) {
                        mensajeSalud = "El chivo en etapa de cría está en buena salud.";
                    } else {
                        mensajeSalud = "El chivo en etapa de cría necesita atención.";
                    }
                } else if (etapa === "desarrollo") {
                    if (peso > 25 && listaVacunas >= 2) {
                        mensajeSalud = "El chivo en etapa de desarrollo está en buena salud.";
                    } else {
                        mensajeSalud = "El chivo en etapa de desarrollo necesita atención.";
                    }
                } else if (etapa === "madurez") {
                    if (peso > 40 && listaVacunas >= 2) {
                        mensajeSalud = "El chivo en etapa de madurez está en buena salud.";
                    } else {
                        mensajeSalud = "El chivo en etapa de madurez necesita atención.";
                    }
                }
            } else {
                mensajeSalud = "Raza o etapa desconocida, no se puede verificar la salud.";
            }

            // Mostrar el resultado en la página
            document.getElementById("resultadoSalud").textContent = mensajeSalud;
        }
    </script>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

h2 {
    text-align: center;
    margin-top: 20px;
}

form {
    width: 50%;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

label {
    font-weight: bold;
}

input, select, button {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    background-color: #4CAF50;
    color: white;
    cursor: pointer;
}

button:hover {
    background-color: #45a049;
}

ul {
    list-style-type: none;
}

#resultadoSalud {
    color: #333;
    text-align: center;
    font-size: 1.2em;
}
</style>
</head>
<body>
    <h2>Formulario de Información</h2>
    <form action="../animales/guardar_datos.php" method="post">
        <label for="nombre">Nombre:</label><br>
        <input type="text" id="nombre" name="nombre" required><br><br>

        <label for="peso">Peso (kg):</label><br>
        <input type="number" id="peso" name="peso" step="0.1" required><br><br>

        <label for="genero">Género:</label><br>
        <select id="genero" name="genero" required>
            <option value="masculino">Masculino</option>
            <option value="femenino">Femenino</option>
        </select><br><br>

        <label for="raza">Raza:</label><br>
        <select id="raza" name="raza" required>
            <option value="Cerdo">Cerdo</option>
            <option value="Borrego">Borrego</option>
            <option value="Vaca">Vaca</option>
            <option value="Chivo">Chivo</option>
        </select><br><br>

        <label for="etapa">Etapa de Crecimiento:</label><br>
        <select id="etapa" name="etapa" required>
            <option value="cria">Cría</option>
            <option value="desarrollo">Desarrollo</option>
            <option value="madurez">Madurez</option>
        </select><br><br>

        <label for="corral">Corral:</label><br>
        <select id="corral" name="corral" required>
            <option value="destete">Destete</option>
            <option value="engorda">Engorda</option>
            <option value="gestacion">Gestación</option>
            <option value="maternidad">Sala de maternidad</option>
            <option value="sementalera">Sementalera</option>
        </select><br><br>

        <label for="vacunas">Vacunas:</label><br>
        <input type="text" id="vacunaInput" placeholder="Ingrese el nombre de la vacuna"><br><br>
        <button type="button" onclick="agregarVacuna()">Agregar Vacuna</button><br><br>

        <ul id="listaVacunas">
            <!-- Aquí se mostrarán las vacunas ingresadas -->
        </ul><br><br>

        <button type="button" onclick="verificarSalud()">Verificar Salud</button><br><br>

        <p id="resultadoSalud" style="font-weight: bold;"></p><br><br>

        <button type="submit">Guardar</button>
    </form>
</body>
</html>