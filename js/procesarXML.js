"use strict";

class Procesar {

    constructor() {

    }

    procesarDatos() {
        if (window.File && window.FileReader && window.FileList && window.Blob) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    var htmlText = "";
                    var xmlDoc = $.parseXML(xhttp.responseText);
                    var reseñas = $(xmlDoc).find("reseña");
                    var counter = 0;
                    for (var i = 1; i < reseñas.length + 1; i++) {
                        var reseña = reseñas[i - 1];
                        htmlText += "<h2>" + reseña.attributes[0].nodeValue + "</h2>";
                        htmlText += "<ul>";
                        htmlText += "<li>" + "Nombre: " + reseña.children[0].innerHTML + "</li>";
                        htmlText += "<li>" + "Correo: " + reseña.children[1].innerHTML + "</li>";
                        htmlText += "<li>" + "Lugar de la reseña: " + reseña.children[2].innerHTML + "</li>";
                        htmlText += "<li>" + "Procedencia: " + reseña.children[3].innerHTML + "</li>";
                        htmlText += "<li>" + "Reseña: " + reseña.children[4].innerHTML + "</li>";
                        htmlText += "<li>" + "Lugares:" + "</li>";
                        var lugares = $(xmlDoc).find("lugar");
                        for (var j = 1; j < lugares.length - 1; j++) {
                            var lugar = lugares[counter++];
                            htmlText += "<li class='lugares'>" + lugar.attributes[0].nodeValue + "</li>";
                            htmlText += "<li>" + "Reseña: " + lugar.children[0].innerHTML + "</li>";
                            htmlText += "<li>" + "Puntuación: " + lugar.children[1].innerHTML + "</li>";
                            var imagen = "<img src='../multimedia/" + lugar.children[2].innerHTML + "' alt='Imagen del paisaje' height='205' width='512' />";
                            htmlText += "<li>" + imagen + "</li>";
                        }
                        htmlText += "</ul>";
                    }
                    $("#reseñas").after(htmlText);
                }
            };
            xhttp.open("GET", "../xml/resenas.xml", true);
            xhttp.send();
        } else {
            document.write("<p>¡¡¡ Este navegador NO soporta el API File y este programa puede no funcionar correctamente !!!</p>");
        }
    }
}

var procesar = new Procesar();