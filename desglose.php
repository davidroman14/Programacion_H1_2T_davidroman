<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Desglose</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <style>
            ul.text-center {
                text-align: left;
                padding-left: 20px;
            }
        </style>
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
    // Mediante un formulario introduciremos el correo del usuario del que queramos ver el desglose de precios
    echo '
    <div class="container mt-5">
        <form method="POST">
            <label for="correo" class="form-label">Introduce tu correo:</label>
            <input type="email" name="correo" id="correo" class="form-control mb-3" required>
            <input type="submit" name="buscar" value="Buscar" class="btn btn-primary">
        </form>
    </div>';
    // Verificamos el correo
    if (isset($_POST['correo'])) {
        $correo = $_POST['correo'];
        $validacion = "select * from usuarios where correo = '$correo'";
        $resultadovalidacion = $conexion->query($validacion);
        // Si no existe saldrá error
        if ($resultadovalidacion->num_rows == 0) {
            echo '<div class="alert alert-danger mt-3 text-center" role="alert">ERROR, el correo no está registrado.</div>';
        // Si existe mostraremos en un saludo el nombre del usuario
        } else {
            $usuario = $resultadovalidacion->fetch_assoc();
            echo "<h2 class='mt-4 text-center'>Hola, " . $usuario['nombre'] . " " . $usuario['apellido1'] . " " . $usuario['apellido2'] . "</h2>";
            // Hacemos un select para saber el nombre del plan, el precio y la duración del mismo
            $queryPlan = "SELECT planes.plan, planes.precio, planes_usuarios.duracion 
                          FROM planes_usuarios 
                          JOIN planes ON planes_usuarios.id_plan = planes.id_plan 
                          WHERE planes_usuarios.correo = '$correo'";
            $resultPlan = $conexion->query($queryPlan);
            $plan = $resultPlan->fetch_assoc();
            // Y mostraremos los datos del plan al que esta suscrito el usuario y la duración del mismo
            echo "<p class='text-center'>Estás suscrito al plan: <b>" . $plan['plan'] . "</b>, que cuesta <b>" . $plan['precio'] . "€</b> al mes.</p>";
            $precioTotalPlan = $plan['precio'] * $plan['duracion'];
            echo "<p class='text-center'>Duración: <b>" . $plan['duracion'] . " meses</b></p>";
            echo "<p class='text-center'>Precio total del plan: <b>" . $precioTotalPlan . "€</b></p>";
            // Después hacemos un select que muestre los paquetes y el precio a los que esta suscrito el usuario
            $queryPaquetes = "SELECT paquetes.paquete, paquetes.precio 
                              FROM paquetes_usuarios 
                              JOIN paquetes ON paquetes_usuarios.id_paquete = paquetes.id_paquete 
                              WHERE paquetes_usuarios.correo = '$correo'";
            $resultPaquetes = $conexion->query($queryPaquetes);
            // Si no tiene ninguno lo indicará por pantalla
            if ($resultPaquetes->num_rows == 0) {
                echo "<p class='text-center'>No tienes paquetes adicionales.</p>";
                // controlaremos mediante una variable el precio total de los packs sumados
                $precioTotalPaquetes = 0;
            // Si tiene alguno lo mostrará y sumará los precios a la variable
            } else {
                echo "<p class='text-center'>Paquetes adicionales:</p><ul class='text-center'>";
                $precioTotalPaquetes = 0;
                while ($paquete = $resultPaquetes->fetch_assoc()) {
                    echo "<li>" . $paquete['paquete'] . " - " . $paquete['precio'] . "€</li>";
                    $precioTotalPaquetes += $paquete['precio'];
                }
                echo "</ul>";
            }
            // Al final mostraremos el precio total del plan y los paquetes 
            // tanto sin como con IVA
            $precioFinal = $precioTotalPlan + $precioTotalPaquetes;
            echo "<h3 class='text-center'>Precio final total: <b>" . $precioFinal . "€</b></h3>";
            echo "<h3 class='text-center'>Precio final con IVA: <b>" . round($precioFinal * 1.21, 2) . "€</b></h3>";
        }
    }
    ?>
        <footer class="bg-secondary text-white text-center py-3 fixed-bottom"><p>Hito 1 de Programación del Segundo Trimestre de David Román Martín</p></footer>
    </body>
</html>
