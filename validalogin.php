<?php
session_name('exam');
session_start();
$usuario = $_POST['usuario'];
$pass = $_POST['pass'];
//Creamos la conexion a partir de los datos del archivo config.php
include 'config.php';
$conexion = new mysqli($host, $username, $password, $db_name);
if ($conexion->connect_errno) {
 die("Error de conexion: $conexion->connect_error");
}
//evitamos un sql-injection
$usuario = $conexion->real_escape_string($usuario);
$pass = $conexion->real_escape_string($pass);
$sql = "SELECT * "
 . "FROM usuarios "
 . "WHERE user='$usuario' AND passwd=MD5('$pass')";
$result = $conexion->query($sql);
$validado = FALSE;
while ($fila = $result->fetch_assoc()){
 //hay un usuario que cumple
 $validado = TRUE;
 $email=$fila['email'];
 $id_user = $fila['passwd'];
}
if ($validado) {
 echo "El usuario $usuario con $email ha entrado en el sistema <br><br>";
//Ahora toca buscar las preguntas para ponerlas en pantalla dentro de un formulario
  $result = $conexion->query("SELECT * FROM preguntas");
 echo '<FORM action="calificar.php" method="post">';
 while ($fila = $result->fetch_assoc()) {
 //cogemos la pregunta y las opciones
 $idp = $fila['id'];
 $pregunta = $fila['pregunta'];
 echo '<b>',$pregunta,'</b><br>';
  $sql = "SELECT * FROM opciones WHERE idPregunta = $fila[id]";
  //echo "<br>".$sql."</br>";
  $op = $conexion->query($sql);
  while ($registro = $op->fetch_assoc()){
        echo "<input type='radio' name='pregunta".$registro['idPregunta']."' value='".$registro['valor']."'> <b>".$registro['respueta']."</b><br>";
  echo "<hr>";
  }
 
  echo '<br>';
  }
 echo '<input type="submit" value="corregir">';
 echo '</FORM>';
}

else { 
    echo "Usuario o contrase&ntilde;a incorrecto ";

};