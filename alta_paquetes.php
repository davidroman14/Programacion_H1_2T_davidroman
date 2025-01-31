<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>alta_paquetes</title>
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
        // Recogemos las variables de edad, correo y plan que necesitaremos para validar los paquetes
        // y registrarlos al usuario que los contrata
        $edad = ($_POST["Edad"]);
        $duracion = ($_POST["Duracion"]);
        $correo = ($_POST["Correo"]);
        $plan = ($_POST["Idplan"]);
        // Si no has seleccionado ningún paquete mostrará un formulario dependiendo de las siguientes variables
        if (empty($_POST["paquete"])) {
            echo "<div class='container mt-5'>";
            echo "<h3>Seleccione un paquete</h3>";
            echo "<p>Si no quiere ningún paquete vuelva al menú principal</p>";
            // Si es menor de edad solo mostrará el paquete infantil
            if ($edad < 18) {
                $paquetes = "SELECT * FROM paquetes WHERE paquete = 'Infantil'";
                $mostrarpaquetes = $conexion->query($paquetes);
                echo "<form method='POST'>";
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>PAQUETE</th><th>PRECIO</th><th>SELECCIÓN</th></tr></thead>";
                echo "<tbody>";
                while ($row = $mostrarpaquetes->fetch_assoc()) {
                    $paquete = $row['paquete'];
                    $idpaquete = $row['id_paquete'];
                    echo "<tr>";
                    echo "<td>" . $paquete . "</td>";
                    echo "<td>" . $row['precio'] . "</td>";
                    echo "<td><input type='radio' name='paquete' value='$idpaquete' required></td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "<input type='hidden' name='Correo' value='$correo'>";
                echo "<input type='hidden' name='Edad' value='$edad'>";
                echo "<input type='hidden' name='Duracion' value='$duracion'>";
                echo "<input type='hidden' name='Idplan' value='$plan'>";
                echo "<button type='submit' class='btn btn-primary'>Seleccionar</button>";
                echo '<button type="button" class="btn btn-secondary" onclick="window.location.href=\'index.php\'">Volver al Menú Principal</button>';
                echo "</form>";
            // Si es el plan basico, solo podrás elegir solo un paquete
            } elseif ($plan == 1) {
                // Si la duración es anual mostrará el paquete de deportes, si no, no lo mostrará
                if ($duracion == "anual") {
                    $paquetes = "SELECT * FROM paquetes";
                } else {
                    $paquetes = 'SELECT * FROM paquetes WHERE paquete != "Deporte"';
                }
                $mostrarpaquetes = $conexion->query($paquetes);
                echo "<form method='POST'>";
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>PAQUETE</th><th>PRECIO</th><th>SELECCIÓN</th></tr></thead>";
                echo "<tbody>";
                while ($row = $mostrarpaquetes->fetch_assoc()) {
                    $paquete = $row['paquete'];
                    $idpaquete = $row['id_paquete'];
                    echo "<tr>";
                    echo "<td>" . $paquete . "</td>";
                    echo "<td>" . $row['precio'] . "</td>";
                    echo "<td><input type='radio' name='paquete' value='$idpaquete' required></td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "<input type='hidden' name='Correo' value='$correo'>";
                echo "<input type='hidden' name='Edad' value='$edad'>";
                echo "<input type='hidden' name='Duracion' value='$duracion'>";
                echo "<input type='hidden' name='Idplan' value='$plan'>";
                echo "<button type='submit' class='btn btn-primary'>Seleccionar</button>";
                echo '<button type="button" class="btn btn-secondary" onclick="window.location.href=\'index.php\'">Volver al Menú Principal</button>';
                echo "</form>";
            // Si has seleccionado el plan estandar o el premium y eres mayor de edad te mostrará todos los planes
            } else {
                // con la condicion de si es anual para el paquete de deporte
                if ($duracion == "anual") {
                    $paquetes = "SELECT * FROM paquetes";
                } else {
                    $paquetes = 'SELECT * FROM paquetes WHERE paquete != "Deporte"';
                }
                $mostrarpaquetes = $conexion->query($paquetes);
                echo "<form method='POST'>";
                echo "<table class='table table-striped'>";
                echo "<thead><tr><th>PAQUETE</th><th>PRECIO</th><th>SELECCIÓN</th></tr></thead>";
                echo "<tbody>";
                while ($row = $mostrarpaquetes->fetch_assoc()) {
                    $paquete = $row['paquete'];
                    $idpaquete = $row['id_paquete'];
                    echo "<tr>";
                    echo "<td>" . $paquete . "</td>";
                    echo "<td>" . $row['precio'] . "</td>";
                    echo "<td><input type='checkbox' name='paquete[]' value='$idpaquete'></td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "<input type='hidden' name='Correo' value='$correo'>";
                echo "<input type='hidden' name='Edad' value='$edad'>";
                echo "<input type='hidden' name='Duracion' value='$duracion'>";
                echo "<input type='hidden' name='Idplan' value='$plan'>";
                echo "<button type='submit' class='btn btn-primary'>Seleccionar</button>";
                echo '<button type="button" class="btn btn-secondary" onclick="window.location.href=\'index.php\'">Volver al Menú Principal</button>';
                echo "</form>";
            }
            echo "</div>";
        // Si detecta datos al enviar el formulario hará un insert
        } else {
            if (isset($_POST['paquete'])) {
                // Recogemos los paquetes seleccionados
                $paquetesSeleccionados = $_POST['paquete'];
                // Si detecta que hay más de uno, mediante un for each haremos tantos insert como planess se han elegido
                if (is_array($paquetesSeleccionados)) {
                    foreach ($paquetesSeleccionados as $idPaquete) {
                        $insertarPaquete = "INSERT INTO paquetes_usuarios (correo, id_paquete) VALUES ('$correo', '$idPaquete')";
                        $conexion->query($insertarPaquete);
                    }
                    echo "<div class='container mt-5'><h4>Paquetes registrados correctamente.</h4></div>";
                // Si no solo se hará un insert que guardará el correo del usuario y la id del paquete
                } else {
                    $insertarPaquete = "INSERT INTO paquetes_usuarios (correo, id_paquete) VALUES ('$correo', '$paquetesSeleccionados')";
                    $conexion->query($insertarPaquete);
                    echo "<div class='container mt-5'><h4>Paquete registrado correctamente.</h4></div>";
                    echo '<button type="button" class="btn btn-secondary" onclick="window.location.href=\'index.php\'">Volver al Menú Principal</button>';
                }
            }
        }
    ?>
        <footer class="bg-secondary text-white text-center py-3 fixed-bottom"><p>Hito 1 de Programación del Segundo Trimestre de David Román Martín</p></footer>
    </body>
</html>