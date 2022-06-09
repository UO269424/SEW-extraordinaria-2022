<?php

class BaseDatos{
    protected $servername = "localhost";
    protected $username = "DBUSER2020";
    protected $password = "DBPSWD2020";
    protected $nombreBase = "sew";
    protected $informe = "";
    protected $insert_usuario = "INSERT INTO usuario (id, nombre, apellidos) VALUES (?,?,?)";
    protected $insert_reseña = "INSERT INTO resena (id_usuario, id_lugar, texto, puntuacion) VALUES (?,?,?,?)";
    protected $insert_lugar = "INSERT INTO lugar (id, nombre) VALUES (?,?)";
    protected $delete_usuario = "DELETE FROM usuario WHERE id = ?";
    protected $delete_reseña = "DELETE FROM reseña WHERE id = ?";
    protected $delete_lugar = "DELETE FROM lugar WHERE id = ?";
    protected $select_usuario = "SELECT id FROM usuario WHERE nombre LIKE ?";
    protected $select_lugar = "SELECT id FROM lugar WHERE nombre LIKE ?";

    public function checkCon($base){
        if($base->connect_error) {
            exit ("<p>ERROR de conexión:".$base->connect_error."</p>");  
        }
    }

    public function closeBD($base){
        $base->close();
    }

    public function crearDatos($aux){
        $db = new mysqli($this->servername,$this->username,$this->password, $this->nombreBase);
        $this->checkCon($db);
        if($aux === 'usuario'){
            $consultaPre = $db->prepare($this->insert_usuario);
            $id = intval($_POST['idUsuario']);
            $consultaPre->bind_param('iss', $id, $_POST['nombreUsuario'], $_POST['apellidosUsuario']);
            $consultaPre->execute();
            $consultaPre->close();    
            $this->closeBD($db);
        }
        if($aux === 'resena'){
            $consultaPre = $db->prepare($this->insert_reseña);
            $param1 = intval($_POST['idUsuarioReseña']);
            $param2 = intval($_POST['idLugarReseña']);
            $param3 = intval($_POST['puntuacion']);
            $consultaPre->bind_param('iisi', $param1, $param2, $_POST['reseña'],$param3);
            $consultaPre->execute();
            $consultaPre->close();    
            $this->closeBD($db);
        }
        if($aux === 'lugar'){
            $consultaPre = $db->prepare($this->insert_lugar);
            $id = intval($_POST['idLugar']);
            $consultaPre->bind_param('is', $id, $_POST['nombreLugar']);
            $consultaPre->execute();
            $consultaPre->close();    
            $this->closeBD($db);
        }
    }

    public function buscarUsuario(){
        $db = new mysqli($this->servername,$this->username,$this->password, $this->nombreBase);
        $this->checkCon($db);
        $consultaPre = $db->prepare($this->select_usuario);
        $consultaPre->bind_param('s', $_POST['nombreUsuario']);
        $consultaPre->execute();
        $usuario = -1;
        $resultado = $consultaPre->get_result();
        if($resultado->num_rows >= 1){
            while($fila = $resultado->fetch_assoc())
                $usuario = $fila["id"];
        }
        $consultaPre->close();
        $this->closeBD($db);
        return $usuario;
    }

    public function buscarLugar(){
        $db = new mysqli($this->servername,$this->username,$this->password, $this->nombreBase);
        $this->checkCon($db);
        $consultaPre = $db->prepare($this->select_lugar);
        $consultaPre->bind_param('s', $_POST['nombreLugar']);
        $consultaPre->execute();
        $lugar = -1;
        $resultado = $consultaPre->get_result();
        if($resultado->num_rows >= 1){
            while($fila = $resultado->fetch_assoc())
                $lugar = $fila["id"];
        }
        $consultaPre->close();
        $this->closeBD($db);
        return $lugar;
    }

    public function insertarDatos(){
        $id_usuario = $this->buscarUsuario();
        if($id_usuario === -1){
            $db = new mysqli($this->servername,$this->username,$this->password, $this->nombreBase);
            $this->checkCon($db);
            $consultaPre = $db->prepare("INSERT INTO usuario (nombre, apellidos) VALUES (?,?)");
            $consultaPre->bind_param('ss', $_POST['nombreUsuario'], $_POST['apellidosUsuario']);
            $consultaPre->execute();
            $consultaPre->close();    
            $this->closeBD($db);
            $id_usuario = $this->buscarUsuario();
        }
        $id_lugar = $this->buscarLugar();
        if($id_lugar === -1){
            $db = new mysqli($this->servername,$this->username,$this->password, $this->nombreBase);
            $this->checkCon($db);
            $consultaPre = $db->prepare("INSERT INTO lugar (nombre) VALUES (?)");
            $consultaPre->bind_param('s', $_POST['nombreLugar']);
            $consultaPre->execute();
            $consultaPre->close();    
            $this->closeBD($db);
            $id_lugar = $this->buscarLugar();
        }  
        $_POST['idUsuarioReseña'] = $id_usuario;
        $_POST['idLugarReseña'] = $id_lugar;
        $this->crearDatos('resena');
    }

    public function generarInforme(){
        $db = new mysqli($this->servername,$this->username,$this->password, $this->nombreBase);
        $this->checkCon($db);
        $result = $db->query("SELECT COUNT(*) FROM usuario");
        $personas = $result->fetch_array()[0];
        if($personas == 0){
            $this->closeBD($db);
            return "<p>No se puede generar un informe sin datos en la Base de Datos. Por favor, introduzca alguna reseña.</p>";
        }
        $result = $db->query("SELECT id_lugar, COUNT(*) AS counter FROM resena GROUP BY id_lugar ORDER BY counter DESC");
        $aux = $result->fetch_array()[0];
        $result = $db->query("SELECT nombre FROM lugar WHERE id = ". $aux);
        $lugarMasVisitado = $result->fetch_array()[0];
        $result = $db->query("SELECT id_usuario, COUNT(*) AS counter FROM resena GROUP BY id_usuario ORDER BY counter DESC");
        $aux = $result->fetch_array()[0];
        $result = $db->query("SELECT nombre FROM usuario WHERE id = ". $aux);
        $usuarioMasViajero = $result->fetch_array()[0];
        $result = $db->query("SELECT id_lugar, AVG(puntuacion) AS counter FROM resena GROUP BY id_lugar ORDER BY counter DESC");
        $aux = $result->fetch_array()[0];
        $mayorPuntuacion = $result->fetch_array()[1];
        $result = $db->query("SELECT nombre FROM lugar WHERE id = ". $aux);
        $lugarMejorVotado = $result->fetch_array()[0];

        $informe = "<p><label for='lugarVisitado'>Lugar más visitado: <input type='text' name='lugarVisitado' value='". $lugarMasVisitado ."' readonly/></label></p>".
                   "<p><label for='persona'>Usuario más activo: <input type='text' name='persona' value='". $usuarioMasViajero ."' readonly/></label></p>" . 
                   "<p><label for='lugarVotado'>Lugar más votado: <input type='text' name='lugarVotado' value='". $lugarMejorVotado . " - " . $mayorPuntuacion ."' readonly/></label></p>";
        $this->closeBD($db);
        return $informe;
    }

    public function importarDatos(){
        if(($file = fopen($_POST["archivo"],"r")) !== FALSE){
            while(($data = fgetcsv($file, 1000, ";")) !== FALSE){
                if($data[0] === 'usuario'){
                    $_POST['idUsuario'] = $data[1];
                    $_POST['nombreUsuario'] = $data[2];
                    $_POST['apellidosUsuario'] = $data[3];
                    $this->crearDatos($data[0]);
                }
                if($data[0] === 'resena'){
                    $_POST['idUsuarioReseña'] = $data[1];
                    $_POST['idLugarReseña'] = $data[2];
                    $_POST['reseña'] = $data[3];
                    $_POST['puntuacion'] = $data[4];
                    $this->crearDatos($data[0]);
                }
                if($data[0] === 'lugar'){
                    $_POST['idLugar'] = $data[1];
                    $_POST['nombreLugar'] = $data[2];
                    $this->crearDatos($data[0]);
                }
            } 
        fclose($file);
        }
    }

    public function exportarDatos(){
        $db = new mysqli($this->servername,$this->username,$this->password, $this->nombreBase);
        $this->checkCon($db);
        $resultado = $db->query("SELECT * FROM usuario");
        $myfile = fopen("exportar.csv", "w") or die("Unable to open file!");
        if ($resultado->num_rows > 0) {
            while($fila = $resultado->fetch_assoc()) {
                $line = 'usuario;' . $fila['id'] .';'. $fila['nombre'] .';'. $fila['apellidos'] . "\n"; 
                fwrite($myfile, $line);
                }             
        }
        $resultado = $db->query("SELECT * FROM lugar");
        if ($resultado->num_rows > 0) {
            while($fila = $resultado->fetch_assoc()) {
                $line = 'lugar;' . $fila['id'] .';'. $fila['nombre'] . "\n"; 
                fwrite($myfile, $line);
                }             
        }
        $resultado = $db->query("SELECT * FROM resena");
        if ($resultado->num_rows > 0) {
            while($fila = $resultado->fetch_assoc()) {
                $line = 'resena;' . $fila['id'] .';'. $fila['id_usuario'] .';'. $fila['id_lugar'] .';'. $fila["texto"]  .';'. $fila['puntuacion'] . "\n";
                fwrite($myfile, $line);
                }             
        }
        fclose($myfile);
        $this->closeBD($db);
    }

}

session_start();
$baseDatos = new BaseDatos();

if (count($_POST)>0) {  
    if(isset($_POST['crearReseña'])){
        if($_POST['nombreUsuario'] !== '' && $_POST['nombreLugar'] !== '' && $_POST['apellidosUsuario'] !== '' && $_POST['reseña'] !== '')
            $baseDatos->insertarDatos();
    }
    if(isset($_POST['importar'])){
        if($_POST['archivo'] !== ''){
            $baseDatos->importarDatos();
        }
    }
    if(isset($_POST['exportar'])){
        $baseDatos->exportarDatos();
    }
}
?>