<?php
require "config.php";
include_once "./include/connect.php";
include_once "./include/func.php";

$id = checkMail('casarin.marcio@gmail.com');
echo "Este é o ID: ".$id;