<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>usuarios</title>
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
        // Aquí detectará el correo al presionar el botón de borrar y borrará lo siguiente
        if (!empty($_GET['eliminar'])) {
            $correo = $_GET['eliminar'];
            // De la tabla planes_usuarios todas las lineas con el correo del usuario que queramos borrar
            $eliminarplan = "DELETE FROM planes_usuarios WHERE correo = '$correo';";
            $resultado2 = $conexion->query($eliminarplan);
            // De la tabla paquetess_usuarios todas las lineas con el correo del usuario que queramos borrar
            $eliminarpack = "DELETE FROM paquetes_usuarios WHERE correo = '$correo';";
            $resultado3 = $conexion->query($eliminarpack);
            // Y de la tabla usuarios borramos toda la información del mismo
            $eliminarusuario = "DELETE FROM usuarios WHERE correo = '$correo';";
            $resultado4 = $conexion->query($eliminarusuario);
            echo "<div class='container mt-3'><h4 class='text-success'>Usuario eliminado correctamente</h4></div>";
        }
        // Mediante un select y una tabla HTML mostramos toda la información de los usuarios
        $instruccion = "SELECT * FROM usuarios";
        $resultado = $conexion->query($instruccion);
        echo "<div class='container mt-5'>";
        echo "<h3>Lista de usuarios registrados</h3>";
        echo "<table class='table table-striped table-bordered'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Correo</th>";
            echo "<th>Nombre</th>";
            echo "<th>Apellidos</th>";
            echo "<th>Edad</th>";
            echo "<th>Eliminar</th>";
            echo "<th>Modificar</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
        while ($row = $resultado->fetch_assoc()) {
            $correo = $row['correo'];
            echo "<tr>";
            echo "<td>" . $correo . "</td>";
            echo "<td>" . $row['nombre'] . "</td>";
            echo "<td>" . $row['apellido1'] . " " . $row['apellido2'] . "</td>";
            echo "<td>" . $row['edad'] . "</td>";
            // También tendremos dos botones
            // Uno que elimina el usuario 
            echo "<td>
                     <form method='GET' action='usuarios.php'>
                     <button type='submit' class='btn btn-danger' name='eliminar' value='$correo'>Borrar</button>
                     </form>
                  </td>";
            // y otro que nos lleva a una página en la que modificaremos el usuario
            echo "<td>
                     <form method='GET' action='modificar.php'>
                     <button type='submit' class='btn btn-warning' name='correo' value='$correo'>Modificar</button>
                     </form>
                  </td>";
            echo "</tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    ?>
        <footer class="bg-secondary text-white text-center py-3 fixed-bottom"><p>Hito 1 de Programación del Segundo Trimestre de David Román Martín</p></footer>
    </body>
</html>

