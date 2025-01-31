<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>alta_usuario</title>
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
    // Detectamos si las variables del formulario tienen datos o no, si no tiene mostramos el formulario para el 
    // registro del nuevo usuario
    if (empty($_POST["Nombre"])and empty($_POST["Apellido1"]) and empty($_POST["Apellido2"]) and empty($_POST["Correo"]) and empty($_POST["Edad"])){
        echo '
            <div class="container mt-5">
                <h2>Registrar nuevo usuario</h2>
                <form method="POST">
                    <div class="mb-3">
                        <label for="Nombre" class="form-label">Nombre</label>
                        <input type="text" name="Nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="Apellidos" class="form-label">Apellidos</label>
                        <input type="text" name="Apellido1" class="form-control" required>
                        <br>
                        <input type="text" name="Apellido2" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="Correo" class="form-label">Correo</label>
                        <!-- Mediante un patrón controlamos que lo que se introduzca sea un correo valido -->
                        <input type="email" name="Correo" class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required>
                    </div>
                    <div class="mb-3">
                        <label for="Edad" class="form-label">Edad</label>
                        <input type="number" name="Edad" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar</button>
                </form>
            </div>';
        // Si detecta que el formulario tiene datos guardará todo en variables
        } else {
            $nombre = ($_POST["Nombre"]);
            $apellido1 = ($_POST["Apellido1"]);
            $apellido2 = ($_POST["Apellido2"]);
            $correo = ($_POST["Correo"]);
            $edad = ($_POST["Edad"]);
            // Verificamos mediante un select que el correo no esté en la base de datos
            $validacion = "select correo from usuarios where correo = '$correo'";
            $resultadovalidacion = $conexion->query($validacion);
            // Si ya está registrado mostrará un error
            if ($resultadovalidacion->num_rows != 0){
                echo "<div class='alert alert-danger mt-3' role='alert'>
                        <h3>ERROR: El correo ya está registrado</h3>
                    </div>";
                    echo '<button type="button" class="btn btn-secondary" onclick="window.location.href=\'index.php\'">Volver al Menú Principal</button>';
            } else {
                // Si no lo registrará en la base con un insert y nos dirá que presionemos al botón para continuar
                $insertarusuario = "insert into usuarios values('$correo','$nombre','$apellido1','$apellido2','$edad')";
                $resultadoregistro = $conexion->query($insertarusuario);
                echo "<div class='alert alert-success mt-3' role='alert'>
                        <h2>El usuario se ha registrado correctamente</h2>
                        <h3>Presione el botón para seleccionar su plan</h3>
                    </div>";
                // Enviarmos el correo para asi poder identificar que usuario contrata que plan y que paquete 
                // además de la edad que la necesitaremos para que el usuario contrate solo el paquete infantil
                // en caso de que sea menor de edad
                echo "<form method='POST' action='alta_plan.php'>
                        <input type='hidden' name='Edad' value='$edad'>
                        <input type='hidden' name='Correo' value='$correo'>
                        <button type='submit' class='btn btn-primary'>Seleccionar plan</button>
                    </form>";
            }
        }
?>
        <footer class="bg-secondary text-white text-center py-3 fixed-bottom"><p>Hito 1 de Programación del Segundo Trimestre de David Román Martín</p></footer>
    </body>
</html>
