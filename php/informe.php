<?php
include 'db.php';
echo"<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='utf-8'>
    <title>Informe</title>
    <link rel='stylesheet' href='../css/estilo.css'>
    <meta name = 'application_name' content = 'World Review'>
    <meta name = 'author' content = 'Alonso Gago Suárez - UO269424'>
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
        <h1>Informe de World Review</h1>".$baseDatos->generarInforme()."
        <h2>Cargar o exportar datos de un CSV</h2>
            <form action='#' method='post' name='db.php'>
                <p><label for='archivo'>Seleccionar el CSV: <input type='file' accept='.csv' name='archivo' id='archivo'/></label>
                <label for='importar'><input type='submit' class='button' name='importar' value='Importar' id='importar'/></label></p>
                <p><label for='exportar'>Exportar el archivo CSV:<input type='submit' class='button' name='exportar' value='Exportar' id='exportar'/></label></p>
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