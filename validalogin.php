<?php
session_name(exam);
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
 . "WHERE username='$usuario' AND password=MD5('$pass')";
$result = $conexion->query($sql);
$validado = FALSE;
while ($fila = $result->fetch_assoc()){
 //hay un usuario que cumple
 $validado = TRUE;
 $email=$fila['email'];
 $id_user = $fila['clave_usuario'];
}
if ($validado) {
 echo "El usuario $usuario con $email ha entrado en el sistema <br>";
//Ahora toca buscar las preguntas para ponerlas en pantalla dentro de un form
 $sql = "SELECT * "
 . "FROM preguntas ";
 $result = $conexion->query($sql);
 $sql = "SELECT * "
 . "FROM opciones ";
 $result2 = $conexion->query($sql);
 echo '<FORM action="calificar.php" method="post">';
 while ($fila = $result->fetch_assoc()) {
 //cogemos la pregunta y las opciones
 $idp = $fila['id'];
 $pregunta = $fila['pregunta'];
 while ($fila2 = $result2->fetch_assoc()) {
 $op = $fila2['respuesta'];
 //las presentamos
 echo '<b>',$pregunta,'</b><br>';
 echo '<b>',$op,'</b><br>';
  echo "<hr>";
 }}
 echo '<input type="submit" value="corregir">';
 echo '</FORM>';
}
else { echo "Usuario o contraseña incorrecto ";}