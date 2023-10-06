<?php
session_start();
ob_start();
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
require "config.php";
include_once "include/connect.php";
include_once "include/func.php";
if((!isset($_SESSION['id'])) AND (!isset($_SESSION['nome']))){
    header("Location: admin.php");
    $_SESSION['msg'] = "<p style='color:#ff0000'>Precisa estar logado para acessar essa página.</p>";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="CIETIS">
    <meta name="author" content="Marcio Casarin, Etwas Informatica">
    <title>Home - CIETIS</title>
   

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">   
    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Playfair&#43;Display:700,900&amp;display=swap" rel="stylesheet">
    <!-- Custom styles for this template -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> <!--google charts -->
  </head>
<body>
        <table class="table"> 
            <thead>
                <tr>
                    <th>Visualizar</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Categoria</th>
                    <th>CPF</th>
                    <th>DOC</th>
                    <th>País</th>
                    <th>Status</th>
                    <th>Comprovante</th>
                </tr>
            </thead>
            <tbody>
    <?php
    $sql = "select id,nome,email,phone,categoria,cpf,doc,pais,status,arq_pgto from listaInscritos order by nome";
    $exec = $conn->prepare($sql);
    $exec->execute();
    if(($exec) AND ($exec->rowCount() != 0)){
        while($row = $exec->fetch(PDO::FETCH_ASSOC)){
            ?>
            <tr>
                <td><a href=""><?php echo $row['id'] ?></td>
                <td><?php echo $row['nome'] ?></td>
                <td><?php echo $row['email'] ?></td>
                <td><?php echo $row['phone'] ?></td>
                <td><?php 
                    $sqlCat = "select categoria from categorias where id='".$row['categoria']."'";
                    $execCat = $conn->prepare($sqlCat);
                    $execCat->execute();
                    $categoria = $execCat->fetch(PDO::FETCH_ASSOC);
                    echo $categoria['categoria'];
                ?></td>
                <td><?php echo $row['cpf'] ?></td>
                <td><?php echo $row['doc'] ?></td>
                <td><?php echo $row['pais'] ?></td>
                <td><?php echo $row['status'] ?></td>
                <td><?php 
                    echo "<img src='comprovantes/".$row['id']."/".$row['arq_pgto']."'>";
                ?></td>
            </tr>

            
            <?php
        }
    } else {
        echo "Nenhum resultado a exibir.";
    }
    ?>
        </tbody>
    </table>
</body>
</html>