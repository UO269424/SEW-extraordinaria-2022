"use strict";

class Meteo{

    constructor(){
        this.pantallaCompleta();
        this.api_key = 'QFKtbIN6HTQrtH9Gr0q3elidi9lREsES';
        this.base_city = 'https://dataservice.accuweather.com/locations/v1/cities/geoposition/search';
        this.base_weather = 'https://dataservice.accuweather.com/currentconditions/v1/';
        this.query = '?apikey=';
        this.andOperator = '&q=';
    }

    buscarCiudad(latitud, longitud){
        $.ajax(
            {
                dataType: "json",
                url: this.base_city + this.query + this.api_key + this.andOperator + latitud + ',' + longitud,
                method: 'GET',
                success: function(datos)
                        {
                            meteo.cargarDatos(datos.Key);
                        },
                error:function()
                    {
                        $("h2").html("¡Tenemos problemas! No se puede obtener su localización");
                    }
            }
        );
    }

    cargarDatos(locationKey){
        $.ajax(
            {
                dataType: "json",
                url: this.base_weather + locationKey + this.query + this.api_key,
                method: 'GET',
                success: function(datos)
                        {
                            var tiempo = datos[0];
                            var link = tiempo.Link;
                            var temperatura = tiempo.Temperature.Metric.Value;
                            var text = tiempo.WeatherText;
                            $("p").remove();
                            $(".button").after("<p> Hace "+temperatura+"ºC - "+ text +" <a class='link' href='"+ link +"'> More info</a></p>");
                        },
                error:function()
                    {
                        $("h2").html("¡Tenemos problemas! No se puede cargar los datos meteorológicos");
                    }
            }
        );
    }

    pantallaCompleta(){
        document.addEventListener("keydown", function(e) {
            if (e.key === 'Enter') {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                  if (document.exitFullscreen) {
                    document.exitFullscreen(); 
                  }
                }
            }
          }, false);
    }

    initMap(){  
        var infoWindow = new google.maps.InfoWindow;
        var longitud;
        var latitud;
        if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(function(position) {
                var pos = {lat: position.coords.latitude, lng: position.coords.longitude};
                latitud = position.coords.latitude;
                longitud = position.coords.longitude;
                var mapaGeoposicionado = new google.maps.Map($('div')[0],{
                    zoom: 8,
                    center:pos,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });
                if(document.getElementById("reseña").value !== ''){
                    infoWindow.setPosition(pos);
                    infoWindow.setContent(document.getElementById("reseña").value);
                    meteo.buscarCiudad(pos.lat, pos.lng)
                }
                infoWindow.open(mapaGeoposicionado);
                mapaGeoposicionado.setCenter(pos);
                return pos;
              }, function() {
                handleLocationError(true, infoWindow, mapaGeoposicionado.getCenter());
              });
        } else {
          handleLocationError(false, infoWindow, mapaGeoposicionado.getCenter());
        }
    }

    handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: Ha fallado la geolocalización' :
                              'Error: Su navegador no soporta geolocalización');
        infoWindow.open(mapaGeoposicionado);
      }
}

var meteo = new Meteo();