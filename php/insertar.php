<?php
include 'db.php';
echo"<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='utf-8'>
    <title>Insertar</title>
    <link rel='stylesheet' href='../css/estilo.css'>
    <meta name = 'application_name' content = 'World Review'>
    <meta name = 'author' content = 'Francisco Fernandez-UO269509'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
</head>
<body>
    <header>
    <nav class='navbar'>
        <ul class='nav-ul'>
            <li class='navlink'><a href='../index.html' class='navbar-enlace'>Inicio</a></li>
            <li class='navlink'><a href='../html/resenas.html' class='navbar-enlace'>Ver Reseñas</a></li>
            <li class='navlink'><a href='../html/mapa.html' class='navbar-enlace'>Mapa</a></li>
            <li class='navlink'><a href='../html/lugares.html' class='navbar-enlace'>Lugares</a></li>
            <li class='navlink'><a href='../html/companias.html' class='navbar-enlace'>Compañías</a></li>
            <li class='navlink'><a href='insertar.php' class='navbar-enlace'>Añadir Reseñas</a></li>
            <li class='navlink'><a href='informe.php' class='navbar-enlace'>Informe</a></li>
        </ul>
    </nav>
    </header>
    <section class='home'>
        <h1>Insertar datos en la Base de Datos</h1>
        <form action='#' method='post' name='db.php'>
            <h2>Insertar Reseñas</h2>
            <p><label for='nombreUsuario'>Nombre: <input type='text' name='nombreUsuario'/></label></p>
            <p><label for='apellidosUsuario'>Apellidos: <input type='text' name='apellidosUsuario'/></label></p>
            <p><label for='nombreLugar'>Lugar: <input type='text' name='nombreLugar'/></label></p>
            <p><label for='reseña'>Reseña: <input type='text' name='reseña'/></label></p>
            <p><label for='puntuacion'>Puntuación: <input type='number' min='1' max='10' step='1' value='10' name='puntuacion'/></label></p>

            <p><label for='crearReseña'><input type='submit' class='button' name='crearReseña' value='Enviar reseña' id='crearReseña'/></label></p>
        </form>
    </section>	
    <footer>
        <a href='https://validator.w3.org/check?uri=referer'>
            <img src='../multimedia/HTML5.png' alt='HTML5 válido!' height='31' width='32'/></a>
        <a href='http://jigsaw.w3.org/css-validator/check/referer'>
            <img src='../multimedia/CSS3.png' alt='CSS Válido!' height='31' width='32'/></a>
    </footer>
</body>
</html>";
?>