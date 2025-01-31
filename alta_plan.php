<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>alta_plan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
    // Abrrimos la conexión con la base de datos y si no consigue conectarse mediante un if saltará un error
    $conexion = new mysqli("127.0.0.1", "root", "campusfp", "hito1prog");
    if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
    }
    // Incluimos la barra de navegación
    include('navegacion.php');
    // Hacemos un select que muestre los planes de la base de datos para que, en caso de que se modifique algo 
    // como el precio lo muestre actualizado
    $planes = "SELECT * FROM planes";
    $mostrarplanes = $conexion->query($planes);
    // Cogemos el correo para poder hacer el registro correctamente y la edad para poder controlar los paquetes más tarde
    $edad = ($_POST["Edad"]);
    $correo = $_POST["Correo"];
    // Si no se ha seleccionado ningún plan mostrará una tabla con un formulario en la que seleccionaremos el plan que queremos
    if (empty($_POST["id_plan"]) && empty($_POST["Duracion"])) {
        echo "<div class='container mt-5'>";
        echo "<h3>Seleccione un plan</h3>";
        echo "<form method='POST'>";
        echo "<table class='table table-striped'>";
        echo "<thead><tr><th>PLAN</th><th>PRECIO</th><th>SELECCIÓN</th></tr></thead>";
        echo "<tbody>";
        while ($row = $mostrarplanes->fetch_assoc()) {
            $plan = $row['plan'];
            $idplan = $row['id_plan'];
            echo "<tr>";
            echo "<td>" . $plan . "</td>";
            echo "<td>" . $row['precio'] . "</td>";
            echo "<td><input type='radio' name='id_plan' value='$idplan' required></td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        // Y aqui seleccionaremos si queremos que sea anual o mensual y el número de meses que queremos si seleccionamos mensual
        echo "<h5>DURACIÓN DEL PLAN</h5>";
        echo "<div class='form-check'>";
        echo "<input type='radio' name='Duracion' value='mensual' class='form-check-input' required>";
        echo "<label class='form-check-label'>Mensual</label>";
        echo "</div>";
        echo "<div class='form-check'>";
        echo "<input type='radio' name='Duracion' value='anual' class='form-check-input' required>";
        echo "<label class='form-check-label'>Anual</label>";
        echo "</div>";
        echo "<div class='mb-3'>";
        echo "<label for='meses' class='form-label'>Selecciona los meses (solo si es mensual)</label>";
        echo "<select name='meses' class='form-select' required>";
        echo "<option value='1'>1</option><option value='2'>2</option><option value='3'>3</option><option value='4'>4</option><option value='5'>5</option><option value='6'>6</option><option value='7'>7</option><option value='8'>8</option><option value='9'>9</option><option value='10'>10</option><option value='11'>11</option>";
        echo "</select>";
        echo "</div>";
        // Enviamos mediante un valor oculto la edad y el correo para poder validar y registrartodo correctamente
        echo "<input type='hidden' name='Edad' value='$edad'>";
        echo "<input type='hidden' name='Correo' value='$correo'>";
        echo "<button type='submit' class='btn btn-primary'>Enviar</button>";
        echo "</form>";
        echo "</div>";
    // Si hemos seleccionado un plan recogera todos los valores y se los asignará a una variable
    } else {
        $correo = $_POST["Correo"];
        $duracion = ($_POST["Duracion"]);
        $planregistro = ($_POST["id_plan"]);
        // si hemos seleccionado mensual cogerá el valor de los meses que hemos elegido para la duración
        if ($duracion == "mensual") {
            $tiempoduracion = (int)$_POST["meses"];
        // Si es anual le asignará 12 meses a la duración
        } else {
            $tiempoduracion = 12;
        }
        // Hacemos un insert en la tabla planes_usuarios con la información del usuario, el plan y la duración 
        // y mostramos un botón para continuar con el registro
        if ($planregistro and $duracion) {
            $insertarplan = "INSERT INTO planes_usuarios (correo, id_plan, duracion) 
                VALUES ('$correo', '$planregistro', $tiempoduracion)";
            $resultadoregistro = $conexion->query($insertarplan);
            if ($resultadoregistro) {
                echo "<div class='container mt-5'>";
                echo "<div class='alert alert-success' role='alert'>
                        <h3>Presione el botón para seleccionar los paquetes de su plan</h3>
                    </div>";
                echo "<form method='POST' action='alta_paquetes.php'>";
                echo "<input type='hidden' name='Edad' value='$edad'>";
                echo "<input type='hidden' name='Duracion' value='$duracion'>";
                echo "<input type='hidden' name='Correo' value='$correo'>";
                echo "<input type='hidden' name='Idplan' value='$planregistro'>";
                echo "<button type='submit' class='btn btn-primary'>Seleccionar paquetes</button>";
                echo "</form>";
                echo "</div>";
            }
        }
    }
?>
        <footer class="bg-secondary text-white text-center py-3 fixed-bottom"><p>Hito 1 de Programación del Segundo Trimestre de David Román Martín</p></footer>
    </body>
</html>

