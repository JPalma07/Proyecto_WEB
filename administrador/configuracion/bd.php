<?php
$host="localhost";
$db="proyecto";
$usuario="root";
$contrasenia="";

try {
    $conexion=new PDO("mysql:host=$host;dbname=$db", $usuario,$contrasenia);
} catch (Exception $ex) {
    echo $ex->getMessage();
}
?>