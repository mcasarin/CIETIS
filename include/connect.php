<?php
$host = "localhost";
$dbname = "dbname";
$dbuser = "user";
$dbpass = "pass";
$port = 3306;

try{
    $conn = new PDO("mysql:host=$host;dbname=".$dbname, $dbuser, $dbpass);
    $conn->exec("set names utf8");

}catch(PDOException $err){
    echo "Erro: Conex«ªo com o banco de dados falhou!";
    $err->getMessage();
}
