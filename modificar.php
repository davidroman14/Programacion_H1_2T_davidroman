<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Modificar Usuario</title>
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
    // Cogemos el valor de correo de la url mediane GET que será el correo del usuario que queramos modificar
    $correo = $_GET['correo'];
    // Si no está vacio, hará selects de las tablas con la información del usuario seleccionado
    if (!empty($correo)) {
        $queryUsuario = "SELECT * FROM usuarios WHERE correo = '$correo'";
        $queryPlanes = "SELECT * FROM planes";
        $queryPaquetes = "SELECT * FROM paquetes";
        $queryPlanUsuario = "SELECT * FROM planes_usuarios WHERE correo = '$correo'";
        $queryPaquetesUsuario = "SELECT id_paquete FROM paquetes_usuarios WHERE correo = '$correo'";
        // Ejecutamos los selects
        $resultUsuario = $conexion->query($queryUsuario);
        $resultPlanes = $conexion->query($queryPlanes);
        $resultPaquetes = $conexion->query($queryPaquetes);
        $resultPlanUsuario = $conexion->query($queryPlanUsuario);
        $resultPaquetesUsuario = $conexion->query($queryPaquetesUsuario);
        // Verificamos si el usuario tiene datos antes de acceder
        $usuario = $resultUsuario ? $resultUsuario->fetch_assoc() : [];
        $planUsuario = $resultPlanUsuario ? $resultPlanUsuario->fetch_assoc() : [];
        $paquetesUsuario = [];
        // Obtenemos los paquetes del usuario seleccionadp
        if ($resultPaquetesUsuario) {
            while ($row = $resultPaquetesUsuario->fetch_assoc()) {
                $paquetesUsuario[] = $row['id_paquete'];
            }
        }
    } else {
        $usuario = [];
        $planUsuario = [];
        $paquetesUsuario = [];
    }
    // Si el usuario existe mostrará con HTML un formulario que se rellenará automaticamente con los datos del mismo
    if (!empty($usuario)):
        echo '<!DOCTYPE html>';
        echo '<html lang="es">';
        echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<title>Modificar Usuario</title>';
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">';
        echo '</head>';
        echo '<body>';
        echo '<div class="container mt-5">';
        echo '<h2>Modificar Usuario</h2>';
        echo '<form method="POST">';
        // Aqui mostrará la información del usuario como el nombre, apellidos o edad
        echo '<div class="mb-3">';
        echo '<label for="Nombre" class="form-label">Nombre:</label>';
        echo '<input type="text" name="Nombre" id="Nombre" class="form-control" value="' . $usuario['nombre'] . '" required>';
        echo '</div>';
        echo '<div class="mb-3">';
        echo '<label for="Apellido1" class="form-label">Apellido 1:</label>';
        echo '<input type="text" name="Apellido1" id="Apellido1" class="form-control" value="' .$usuario['apellido1'] . '" required>';
        echo '</div>';
        echo '<div class="mb-3">';
        echo '<label for="Apellido2" class="form-label">Apellido 2:</label>';
        echo '<input type="text" name="Apellido2" id="Apellido2" class="form-control" value="' .$usuario['apellido2'] . '" required>';
        echo '</div>';
        echo '<div class="mb-3">';
        echo '<label for="Edad" class="form-label">Edad:</label>';
        echo '<input type="number" name="Edad" id="Edad" class="form-control" value="' . $usuario['edad'] . '" required>';
        echo '</div>';
        // Aqui mostrará el plan que tiene el usuario y la duración del mismo
        echo '<div class="mb-3">';
        echo '<label class="form-label">Plan:</label><br>';
        while ($row = $resultPlanes->fetch_assoc()) {
            echo '<div class="form-check">';
            echo '<input type="radio" name="id_plan" class="form-check-input" value="' . $row['id_plan'] . '" ' . (isset($planUsuario['id_plan']) && $row['id_plan'] == $planUsuario['id_plan'] ? 'checked' : '') . '>';
            echo '<label class="form-check-label">' . $row['plan'] . '</label>';
            echo '</div>';
        }
        echo '</div>';
        echo '<div class="mb-3">';
        echo '<label for="duracion" class="form-label">Duración:</label>';
        echo '<select name="duracion" id="duracion" class="form-select">';
        for ($i = 1; $i <= 12; $i++) {
            echo '<option value="' . $i . '" ' . (isset($planUsuario['duracion']) && $i == $planUsuario['duracion'] ? 'selected' : '') . '>' . $i . ' meses</option>';
        }
        echo '</select>';
        echo '</div>';
        // Aquí indicará la información de los paquetes que tenga contratados el usuario
        echo '<div class="mb-3">';
        echo '<label class="form-label">Paquetes:</label><br>';
        while ($row = $resultPaquetes->fetch_assoc()) {
            echo '<div class="form-check">';
            echo '<input type="checkbox" name="paquete[]" class="form-check-input" value="' . $row['id_paquete'] . '" ' . (in_array($row['id_paquete'], $paquetesUsuario) ? 'checked' : '') . '>';
            echo '<label class="form-check-label">' . $row['paquete'] . '</label>';
            echo '</div>';
        }
        echo '</div>';
        echo '<div class="mb-3">';
        echo '<button type="submit" name="actualizar" class="btn btn-primary">Actualizar</button>';
        echo '<button type="button" class="btn btn-secondary" onclick="window.location.href=\'index.php\'">Volver al Menú Principal</button>';
        echo '</div>';
        echo '</form>';
        echo '</div>';
        echo '</body>';
        echo '</html>';
        if (!empty($correo) && isset($_POST['actualizar'])) {
            $nombre = $_POST['Nombre'];
            $apellido1 = $_POST['Apellido1'];
            $apellido2 = $_POST['Apellido2'];
            $edad = $_POST['Edad'];
            $id_plan = $_POST['id_plan'];
            $duracion = $_POST['duracion'];
            $paquetesSeleccionados = isset($_POST['paquete']) ? $_POST['paquete'] : [];
            // Aquí actualizamos la información del usuario
            $updateUsuario = "UPDATE usuarios SET nombre='$nombre', apellido1='$apellido1', apellido2='$apellido2', edad=$edad WHERE correo='$correo'";
            // Aquí actualizamos el plan del usuario
            $updatePlan = "UPDATE planes_usuarios SET id_plan=$id_plan, duracion=$duracion WHERE correo='$correo'";
            // Y para actualizar los planes es más sencillo hacer un delete y un insert ya que no puedes modificarlos
            // solo puedes tenerlos o no
            $deletePaquetes = "DELETE FROM paquetes_usuarios WHERE correo='$correo'";
            // Ejecutamos los update y los delete
            $conexion->query($updateUsuario);
            $conexion->query($updatePlan);
            $conexion->query($deletePaquetes);
            // Insertamos los paquetes que tenga el usuario
            foreach ($paquetesSeleccionados as $id_paquete) {
                $insertPaquete = "INSERT INTO paquetes_usuarios (correo, id_paquete) VALUES ('$correo', $id_paquete)";
                $conexion->query($insertPaquete);
            }
            echo "<div class='container mt-3'><p class='text-success'>Datos actualizados correctamente.</p></div>";
        }
    endif;
    ?>
        <footer class="bg-secondary text-white text-center py-3 fixed-bottom"><p>Hito 1 de Programación del Segundo Trimestre de David Román Martín</p></footer>
    </body>
</html>