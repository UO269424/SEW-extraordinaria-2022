<?php
include 'db.php';
echo"<!DOCTYPE html>
<html lang='es'>
<head>
    <title>Informe</title>
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
        <h1>Informe de World Review</h1>" . $bd->generarInforme() ."
        <h2>Cargar datos de un CSV</h2>
            <form action='#' method='post' name='db.php'>
                <p><label for='archivo_usuarios'>Seleccionar el CSV de usuarios: <input type='file' accept='.csv' name='archivo_usuarios' id='archivo_usuarios'/></label>
                <label for='importar_usuarios'>Importar: <input type='submit' class='button' name='importar_usuarios' value='Importar' id='importar_usuarios'/></label></p>
                
                <p><label for='archivo_lugares'>Seleccionar el CSV de lugares: <input type='file' accept='.csv' name='archivo_lugares' id='archivo_lugares'/></label>
                <label for='importar_lugares'>Importar: <input type='submit' class='button' name='importar_lugares' value='Importar' id='importar_lugares'/></label></p>
                
                <p><label for='archivo_resenas'>Seleccionar el CSV de reseñas: <input type='file' accept='.csv' name='archivo_resenas' id='archivo_resenas'/></label>
                <label for='importar_resenas'>Importar: <input type='submit' class='button' name='importar_resenas' value='Importar' id='importar_resenas'/></label></p>

                <p><label for='archivo_viajes'>Seleccionar el CSV de viajes: <input type='file' accept='.csv' name='archivo_viajes' id='archivo_viajes'/></label>
                <label for='importar_viajes'>Importar: <input type='submit' class='button' name='importar_viajes' value='Importar' id='importar_viajes'/></label></p>
                
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