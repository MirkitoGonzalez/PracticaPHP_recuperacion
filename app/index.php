<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php require_once 'vistas/includes/head.php'; ?>
    <title>Examen</title>
</head>
<body>
<?php
error_reporting(0);
    session_start();
    require_once 'controlador/controlador.php';
    //Definimos un objeto controlador
    $controlador = new controlador();
    if ($_GET && $_GET["accion"]) :
        //Sanitizamos los datos que recibamos mediante el GET
        $accion = filter_input(INPUT_GET, "accion", FILTER_SANITIZE_STRING);
        //Verificamos que el objeto controlador que hemos creado implementa el
        //método que le hemos pasado mediante GET
        if (method_exists($controlador, $accion)) :
            $controlador->$accion(); //Ejecutamos la operación indicada en $accion
        else :
            $controlador->vistaLogin(); //Redirigimos a la página de inicio
        endif;
    else :
        $controlador->vistaLogin(); // Redirigimos a la página de inicio inicialmente
    endif;
    ?>
</body>
</html>