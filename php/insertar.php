<?php
include 'db.php';
echo"<!DOCTYPE html>
<html lang='es'>
<head>
    <title>Insertar</title>
    <link rel='stylesheet' href='../css/estilo.css'>
    <meta name = 'application_name' content = 'World Review'>
    <meta name = 'author' content = 'Alonso Gago Suárez - UO269424'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
</head>
<body>
    <nav class='navbar'>
            <a href='../index.html'> Inicio </a>
            <a href='../html/resenas.html'> Ver Reseñas </a>
            <a href='../html/mapa.html'> Mapa </a>
            <a href='../html/lugares.html'> Lugares </a>
            <a href='../html/companias.html'> Compañías </a>
            <a href='insertar.php' > Añadir Reseñas </a>
            <a href='informe.php' > Informe </a>
    </nav>
    <section class='home'>
        <h1>Insertar datos en la Base de Datos</h1>
        <form id='basedatos' action='#' method='post' name='db.php'>
            <h2>Insertar Reseñas</h2>
            <p><label for='nombreUsuario'>Nombre: <input type='text' name='nombreUsuario' id='nombreUsuario'/></label></p>
            <p><label for='apellidosUsuario'>Apellidos: <input type='text' name='apellidosUsuario' id='apellidosUsuario'/></label></p>
            <p><label for='nombreLugar'>Lugar: <input type='text' name='nombreLugar' id='nombreLugar'/></label></p>
            <p><label for='descripcion'>Reseña: <input type='text' name='descripcion' id='descripcion'/></label></p>
            <p><label for='puntuacion'>Puntuación: <input type='number' min='1' max='10' step='1' value='10' name='puntuacion' id='puntuacion'/></label></p>
            <p><label for='agencia'>Agencia:  <select name='agencia' title='agencia' id='agencia'>
                                                <option value='ninguna'>Ninguna</option>
                                                <option value='expedia'>Expedia</option>
                                                <option value='corte_ingles'>Viajes el Corte Inglés</option>
                                                <option value='ebooker'>EBooker</option>
                                                <option value='otra'>Otra</option>
                                            </select></p>
            <p><label for='insertarDatos'>Enviar datos: <input type='submit' class='button' name='insertarDatos' value='Enviar datos' id='insertarDatos'/></label></p>
        </form>
    </section>	
    <footer>
        <a href='https://validator.w3.org/check?uri=referer'>
            <img src='../multimedia/HTML5.png' alt='HTML5 válido!' height='31' width='32'/></a>
        <a href='https://jigsaw.w3.org/css-validator/check/referer'>
            <img src='../multimedia/CSS3.png' alt='CSS Válido!' height='31' width='32'/></a>
    </footer>
</body>
</html>";
?>