<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>index</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
    // En php incluimos la barra de navegación externa
    include('navegacion.php');
?>
    <!-- Aqui mostramos el título de la página y unos botones que nos llevarán a sus diferentes secciones  -->
    <div class="container text-center mt-4">
        <h1 class="display-4">Página de Gestión de Usuarios</h1>
    </div>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="row w-100">
            <div class="col-md-4">
                <a href="usuarios.php" class="btn btn-primary w-100 p-5 fs-1">Listado de Usuarios</a>
            </div>
            <div class="col-md-4">
                <a href="desglose.php" class="btn btn-primary w-100 p-5 fs-1">Desglose del Precio</a>
            </div>
            <div class="col-md-4">
                <a href="alta_usuario.php" class="btn btn-primary w-100 p-5 fs-1">Dar de Alta a un Nuevo Usuario</a>
            </div>
        </div>
    </div>
    
    <footer class="bg-secondary text-white text-center py-3 fixed-bottom"><p>Hito 1 de Programación del Segundo Trimestre de David Román Martín</p></footer>
</body>
</html>
