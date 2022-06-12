<?php
            class BaseDatos{
                private $servername;
                private $username;
                private $password;
                private $database;
        
                public function __construct(){
                    $this->servername = "localhost";
                    $this->username = "DBUSER2021";
                    $this->password = "DBPSWD2021";
                    $this->database = "sew_extraordinaria";
                }
                
                public function checkCon($base){
                    if($base->connect_error) {
                        exit ("<p>ERROR de conexión:".$base->connect_error."</p>");  
                    }
                }

                public function closeBD($base){
                    $base->close();
                }
        
                public function crearBaseDeDatos(){
                    $trans = new mysqli($this->servername,$this->username,$this->password);
                    $cons = "CREATE DATABASE IF NOT EXISTS sew_extraordinaria COLLATE utf8_spanish_ci";
                    if($trans->query($cons) === FALSE)  {
                        echo "<p>La base de datos ya existe o no se ha podido crear</p>";
                        exit();
                    }
                    $trans->close();
                }
        
                public function crearTablas(){
                    $trans = new mysqli($this->servername,$this->username,$this->password,$this->database);
        
                    //Creacion tabla usuario
                    $cons = "CREATE TABLE IF NOT EXISTS Usuario (
                                id_usuario INT NOT NULL, 
                                nombre_usuario VARCHAR(50), 
                                apellidos  VARCHAR(50),
                                PRIMARY KEY (id_usuario))";
        
                    if($trans->query($cons) === FALSE){
                        echo "<p>Ha habido un error creando la tabla -usuario- o la tabla ya existe</p>";
                        exit();
                    } 

                    //Creacion tabla lugar
                    $cons = "CREATE TABLE IF NOT EXISTS Lugar (
                        id_lugar INT NOT NULL, nombre_lugar VARCHAR(255),
                        PRIMARY KEY (id_lugar))";

                    if($trans->query($cons) === FALSE){
                        echo "<p>Ha habido un error creando la tabla -lugar- o la tabla ya existe</p>";
                        exit();
                    } 

                    //Creacion tabla reseña
                    $cons = "CREATE TABLE IF NOT EXISTS Resena (
                        id_resena INT NOT NULL, 
                        id_lugar INT NOT NULL, 
                        id_usuario INT NOT NULL, 
                        descripcion VARCHAR(255),
                        puntuacion INT, 
                        PRIMARY KEY (id_resena), 
                        FOREIGN KEY (id_lugar) REFERENCES Lugar(id_lugar), 
                        FOREIGN KEY (id_usuario) REFERENCES Usuario(id_usuario),
                        CHECK (puntuacion >=0 AND puntuacion <=10)) ";

                    if($trans->query($cons) === FALSE){
                        echo "<p>Ha habido un error creando la tabla -reseña- o ya existe</p>";
                        exit();
                    }

                    //Creacion tabla viaje
                    $cons = "CREATE TABLE IF NOT EXISTS Viaje (
                        id_viaje INT NOT NULL, 
                        id_lugar INT NOT NULL, 
                        agencia VARCHAR(50), 
                        PRIMARY KEY (id_viaje), 
                        FOREIGN KEY (id_lugar) REFERENCES Lugar(id_lugar),
                        CHECK (agencia IN ('Ninguna', 'Expedia', 'Viajes el Corte Inglés', 'EBooker', 'Otra')))";

                    if($trans->query($cons) === FALSE){
                        echo "<p>Ha habido un error creando la tabla -viaje- o la tabla ya existe</p>";
                        exit();
                    }

                    $trans->close();
                }
        
                public function crearUsuario(){
                    $trans = new mysqli($this->servername,$this->username,$this->password,$this->database);
                    $id_usuario = rand();
                    while($this->existeUsuario($id_usuario)>0)  {
                        $id_usuario=rand();
                    }
                    $consInsercion = $trans->prepare("INSERT INTO Usuario (id_usuario, nombre_usuario, apellidos) VALUES (?,?,?)"); 
                    $consInsercion->bind_param('iss', $id_usuario, $_POST['nombreUsuario'], $_POST['apellidosUsuario']);
                    $consInsercion->execute();
                    $consInsercion->close();
                    $trans->close();
                }

                public function crearLugar(){
                    $trans = new mysqli($this->servername,$this->username,$this->password,$this->database);
                    $id_lugar = rand();
                    while($this->existeLugar($id_lugar)>0)  {
                        $id_lugar=rand();
                    }
                    $consInsercion = $trans->prepare("INSERT INTO lugar (id_lugar, nombre_lugar) VALUES (?,?)"); 
                    $consInsercion->bind_param('is', $id_lugar, $_POST['nombreLugar']);
                    $consInsercion->execute();
                    $consInsercion->close();
                    $trans->close();
                }

                public function crearResena($id_usuario, $id_lugar){
                    $trans = new mysqli($this->servername,$this->username,$this->password,$this->database);
                    $id_resena = rand();
                    while ($this->existeResena($id_resena)>0)  {
                        $id_resena = rand();
                    }
                    $consReseña = $trans->prepare("INSERT INTO resena (id_resena, id_lugar, id_usuario, descripcion, puntuacion) VALUES (?,?,?,?,?)");
                    $consReseña->bind_param('iiisi', $id_resena, $id_lugar, $id_usuario, $_POST['descripcion'], $_POST["puntuacion"]);
                    $consReseña->execute();
                    $consReseña->close();
                    $trans->close();
                }

                public function crearViaje($id_lugar){
                    $trans = new mysqli($this->servername,$this->username,$this->password,$this->database);
                    $id_viaje = rand();
                    while($this->existeViaje($id_viaje)>0)  {
                        $id_viaje=rand();
                    }
                    $consInsercion = $trans->prepare("INSERT INTO viaje (id_viaje, id_lugar, agencia) VALUES (?,?,?)"); 
                    $consInsercion->bind_param('iis', $id_viaje, $id_lugar, $_POST['agencia']);
                    $consInsercion->execute();
                    $consInsercion->close();
                    $trans->close();
                }

                public function buscarUsuario(){
                    $db = new mysqli($this->servername,$this->username,$this->password, $this->database);
                    $cons = $db->prepare("SELECT id_usuario FROM usuario WHERE (nombre_usuario = ? AND apellidos = ?)");
                    $cons->bind_param('ss', $_POST['nombreUsuario'], $_POST['apellidosUsuario']);
                    $cons->execute();
                    $usuario = -1;
                    $resultado = $cons->get_result();
                    if($resultado->num_rows >= 1){
                        while($fila = $resultado->fetch_assoc())
                            $usuario = $fila["id_usuario"];
                    }
                    $cons->close();
                    $this->closeBD($db);
                    return $usuario;
                }

                public function buscarLugar(){
                    $db = new mysqli($this->servername,$this->username,$this->password, $this->database);
                    $this->checkCon($db);
                    $cons = $db->prepare("SELECT id_lugar FROM lugar WHERE nombre_lugar = ?");
                    $cons->bind_param('s', $_POST['nombreLugar']);
                    $cons->execute();
                    $lugar = -1;
                    $resultado = $cons->get_result();
                    if($resultado->num_rows >= 1){
                        while($fila = $resultado->fetch_assoc())
                            $lugar = $fila["id_lugar"];
                    }
                    $cons->close();
                    $this->closeBD($db);
                    return $lugar;
                }

                public function buscarResena($id_usuario, $id_lugar)  {
                    $db = new mysqli($this->servername,$this->username,$this->password, $this->database);
                    $this->checkCon($db);
                    $cons = $db->prepare("SELECT id_resena FROM resena WHERE (id_lugar = ? AND id_usuario=?)");
                    $cons->bind_param('ii', $id_lugar, $id_usuario);
                    $cons->execute();
                    $resena = -1;
                    $resultado = $cons->get_result();
                     if($resultado->num_rows >= 1){
                        while($fila = $resultado->fetch_assoc())
                            $resena = $fila["id_resena"];
                    }
                    $cons->close();
                    $this->closeBD($db);
                    return $resena;
                }

                public function buscarViaje($id_lugar){
                    $db = new mysqli($this->servername,$this->username,$this->password, $this->database);
                    $this->checkCon($db);
                    $cons = $db->prepare("SELECT id_viaje FROM viaje WHERE (id_lugar = ? AND agencia = ?)");
                    $cons->bind_param('is', $id_lugar, $_POST['agencia']);
                    $cons->execute();
                    $viaje = -1;
                    $resultado = $cons->get_result();
                    if($resultado->num_rows >= 1){
                        while($fila = $resultado->fetch_assoc())
                            $viaje = $fila["id_viaje"];
                    }
                    $cons->close();
                    $this->closeBD($db);
                    return $viaje;
                }                

                public function existeUsuario(int $id_usuario)  {
                    $db = new mysqli($this->servername,$this->username,$this->password, $this->database);
                    $this->checkCon($db);
                    $cons = $db->prepare("SELECT id_usuario FROM usuario WHERE id_usuario = ?");
                    $cons->bind_param('i', $id_usuario);
                    $cons->execute();
                    $resultado = $cons->get_result()->num_rows;
                    $cons->close();
                    $this->closeBD($db);
                    return intval($resultado);
                }

                public function existeLugar(int $id_lugar)  {
                    $db = new mysqli($this->servername,$this->username,$this->password, $this->database);
                    $this->checkCon($db);
                    $cons = $db->prepare("SELECT id_lugar FROM lugar WHERE id_lugar = ?");
                    $cons->bind_param('i', $id_lugar);
                    $cons->execute();
                    $lugar = -1;
                    $resultado = $cons->get_result()->num_rows;
                    $cons->close();
                    $this->closeBD($db);
                    return intval($resultado);
                }

                public function existeResena(int $id_resena)  {
                    $db = new mysqli($this->servername,$this->username,$this->password, $this->database);
                    $this->checkCon($db);
                    $cons = $db->prepare("SELECT id_resena FROM resena WHERE id_resena = ?");
                    $cons->bind_param('i', $id_resena);
                    $cons->execute();
                    $resultado = $cons->get_result()->num_rows;
                    $cons->close();
                    $this->closeBD($db);
                    return intval($resultado);
                }

                public function existeViaje(int $id_viaje)  {
                    $db = new mysqli($this->servername,$this->username,$this->password, $this->database);
                    $this->checkCon($db);
                    $cons = $db->prepare("SELECT id_viaje FROM viaje WHERE id_viaje = ?");
                    $cons->bind_param('i', $id_viaje);
                    $cons->execute();
                    $resultado = $cons->get_result()->num_rows;
                    $cons->close();
                    $this->closeBD($db);
                    return intval($resultado);
                }


                public function insertarDatos() {
                    $trans = new mysqli($this->servername,$this->username,$this->password,$this->database);

                    if (empty($_POST['nombreUsuario']) || empty($_POST['apellidosUsuario']) 
                        || empty($_POST['nombreLugar']) || empty($_POST['descripcion']) 
                        || empty($_POST['puntuacion']) || (empty($_POST['agencia'])))
                        echo "<p>No se puede realizar la inserción, faltan campos por completar</p>";
                    else {
                        $usuario = $this->buscarUsuario();
                        if(intval($usuario)==-1)    {
                            $this->crearUsuario();
                            $usuario = $this->buscarUsuario();
                        }
                        $lugar = $this->buscarLugar();
                        if(intval($lugar)==-1)    {
                            $this->crearLugar();
                            $lugar = $this->buscarLugar();
                        }
                        $resena = $this->buscarResena($usuario, $lugar);
                        if(intval($resena)==-1) {
                            $this->crearResena($usuario, $lugar);
                        }
                        $viaje = $this->buscarViaje($lugar);
                        if(intval($viaje)==-1) {
                            $this->crearViaje($lugar);
                        }
                    }
                }

                public function generarInforme(){
                    $db = new mysqli($this->servername,$this->username,$this->password,$this->database);
                    $this->checkCon($db);
                    $result = $db->query("SELECT COUNT(*) FROM usuario");
                    $personas = $result->fetch_array()[0];
                    $result = $db->query("SELECT COUNT(*) FROM lugar");
                    $lugares = $result->fetch_array()[0];
                    $result = $db->query("SELECT COUNT(*) FROM resena");
                    $resenas = $result->fetch_array()[0];
                    $result = $db->query("SELECT COUNT(*) FROM viaje");
                    $viajes = $result->fetch_array()[0];
                    if($personas == 0 || $lugares == 0 || $resenas == 0 || $viajes == 0){
                        $this->closeBD($db);
                        return "<p>No se puede generar un informe sin datos en la Base de Datos. Por favor, introduzca alguna reseña.</p>";
                    }

                    $result = $db->query("SELECT id_lugar, COUNT(*) AS counter FROM resena GROUP BY id_lugar ORDER BY counter DESC");
                    $aux = $result->fetch_array()[0];
                    $result = $db->query("SELECT nombre_lugar FROM lugar WHERE id_lugar = ". $aux);
                    $lugarMasVisitado = $result->fetch_array()[0];

                    $result = $db->query("SELECT id_usuario, COUNT(*) AS counter FROM resena GROUP BY id_usuario ORDER BY counter DESC");
                    $aux = $result->fetch_array()[0];
                    $result = $db->query("SELECT nombre_usuario FROM usuario WHERE id_usuario = ". $aux);
                    $usuarioMasViajero = $result->fetch_array()[0];

                    $result = $db->query("SELECT id_lugar, AVG(puntuacion) AS counter FROM resena GROUP BY id_lugar ORDER BY counter DESC");
                    $aux = $result->fetch_array()[0];
                    $result = $db->query("SELECT nombre_lugar FROM lugar WHERE id_lugar = ". $aux);
                    $lugarMejorVotado = $result->fetch_array()[0];

                    $informe = "<p><label for='lugarVisitado'>Lugar más visitado: <input type='text' name='lugarVisitado' value='". $lugarMasVisitado ."' readonly/></label></p>".
                            "<p><label for='persona'>Usuario más activo: <input type='text' name='persona' value='". $usuarioMasViajero ."' readonly/></label></p>" . 
                            "<p><label for='lugarVotado'>Lugar mejor valorado: <input type='text' name='lugarVotado' value='". $lugarMejorVotado . "' readonly/></label></p>";
                    $this->closeBD($db);
                    return $informe;
                }

                
                public function cargarDatos(){
                    $this->cargarUsuarios();
                    $this->cargarLugares();
                    $this->cargarResenas();
                }
        
                public function cargarUsuarios(){            
                    $trans = new mysqli($this->servername,$this->username,$this->password,$this->database);
                    $consInsercion = $trans->prepare("INSERT INTO usuario VALUES (?,?,?)");
                    if(empty($_POST["archivo_usuarios"]))    {
                        echo "No file selected";
                    }
                    else{
                        $archivo = fopen($_POST["archivo_usuarios"], "r");        
                        while(($datos = fgetcsv($archivo,",")) == true){
                            
                            $consInsercion->bind_param('iss', $datos[0], $datos[1], $datos[2]);
                            $consInsercion->execute();
                        }
                        $consInsercion->close();
                        fclose($archivo);
                    }
                }

                public function cargarLugares(){            
                    $trans = new mysqli($this->servername,$this->username,$this->password,$this->database);
                    $consInsercion = $trans->prepare("INSERT INTO lugar VALUES (?,?)");
                    if(empty($_POST["archivo_lugares"]))    {
                        echo "No file selected";
                    }
                    else{
                        $archivo = fopen($_POST["archivo_lugares"], "r");        
                        while(($datos = fgetcsv($archivo,",")) == true){
                            
                            $consInsercion->bind_param('is', $datos[0],$datos[1]);
                            $consInsercion->execute();
                        }
                        $consInsercion->close();
                        fclose($archivo);
                    }
                }

                public function cargarResenas(){            
                    $trans = new mysqli($this->servername,$this->username,$this->password,$this->database);
                    $consInsercion = $trans->prepare("INSERT INTO resena VALUES (?,?,?,?,?)");
                    if(empty($_POST["archivo_resenas"]))    {
                        echo "No file selected";
                    }
                    else{
                        $archivo = fopen($_POST["archivo_resenas"], "r");        
                        while(($datos = fgetcsv($archivo,",")) == true){
                             
                            $consInsercion->bind_param('iiisi', $datos[0], $datos[1], $datos[2], $datos[3], $datos[4]);
                            $consInsercion->execute();
                        }
                        $consInsercion->close();
                        fclose($archivo);
                    }
                }

                public function cargarViajes(){            
                    $trans = new mysqli($this->servername,$this->username,$this->password,$this->database);
                    $consInsercion = $trans->prepare("INSERT INTO viaje VALUES (?,?,?)");
                    if(empty($_POST["archivo_viajes"]))    {
                        echo "No file selected";
                    }
                    else{
                        $archivo = fopen($_POST["archivo_viajes"], "r");        
                        while(($datos = fgetcsv($archivo,",")) == true){
                             
                            $consInsercion->bind_param('iis', $datos[0], $datos[1], $datos[2]);
                            $consInsercion->execute();
                        }
                        $consInsercion->close();
                        fclose($archivo);
                    }
                }
                
            }
            
            session_start();
            $bd = new BaseDatos();
            $bd->crearBaseDeDatos();
            $bd->crearTablas();
            /*
            if (!isset($_SESSION['basedatos']))
			  $_SESSION['basedatos'] = new BaseDatos();
            $bd = $_SESSION['basedatos'];
            */

            if (count($_POST)>0)  {
				if (isset($_POST["insertarDatos"])){
                    $bd->insertarDatos();
                }
                else if (isset($_POST["estadisticas"])){
                    $bd->generarInforme();
                }                
                else if (isset($_POST["importar_usuarios"])){
                    $bd->cargarUsuarios();
                }
                else if (isset($_POST["importar_lugares"])){
                    $bd->cargarLugares();
                }
                else if (isset($_POST["importar_resenas"])){
                    $bd->cargarResenas();
                }
                else if (isset($_POST["importar_viajes"])){
                    $bd->cargarViajes();
                }
                
            };
        ?>