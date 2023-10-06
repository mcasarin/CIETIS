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

// Declarando variaveis
$cat1 = "";
$cat2 = "";
$cat3 = "";
$cat4 = "";
$cat5 = "";
$cat6 = "";


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

<?php
    // queries dos graficos
      $sqlcat = "select categoria as cat,count(*) as cont from listaInscritos group by categoria";
      $exec_sqlcat = $conn->prepare($sqlcat);
      $exec_sqlcat->execute();
      if(($exec_sqlcat) AND ($exec_sqlcat->rowCount() != 0)){
        while($catList = $exec_sqlcat->fetch(PDO::FETCH_ASSOC)) {
            // passar valor para campo hidden para conseguir coletar pelo JS
            // Google Charts
            $cat = $catList['cat'];
            $cont = $catList['cont'];
            $categoriaI = "categoria".$cat;
            echo "<input type='hidden' name='" . $categoriaI . "' id='" . $categoriaI . "' value='" . $cont . "'>";
        }
      }
      if ($cat1 == "") {
        echo "<input type='hidden' name='cat1' id='cat1' value='0'>";
      }
      if ($cat2 == "") {
        echo "<input type='hidden' name='cat2' id='cat2' value='0'>";
      }
      if ($cat3 == "") {
        echo "<input type='hidden' name='cat3' id='cat3' value='0'>";
      }
      if ($cat4 == "") {
        echo "<input type='hidden' name='cat4' id='cat4' value='0'>";
      }
      if ($cat5 == "") {
        echo "<input type='hidden' name='cat5' id='cat5' value='0'>";
      }
      if ($cat6 == "") {
        echo "<input type='hidden' name='cat6' id='cat6' value='0'>";
      }
    ?>
<body>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <!-- <button type="button" class="btn btn-sm btn-outline-secondary">Share</button> -->
                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>

            </div>
        </div>
        
    </div>
        <div id="chart_wrap">
            <div id="piechart_3d" style="width: 500px; height: 300px;"></div>
        </div>
            <script type="text/javascript">
            google.charts.load("current", {
              packages: ["corechart"]
            });
            google.charts.setOnLoadCallback(drawChart);
            // Conversão de campo hidden php para const JS
        
            const cat1 = document.getElementById('cat1');
            const cat1v = parseInt(cat1.value);
            const cat2 = document.getElementById('cat2');
            const cat2v = parseInt(cat2.value);
            const cat3 = document.getElementById('cat3');
            const cat3v = parseInt(cat3.value);
            const cat4 = document.getElementById('cat4');
            const cat4v = parseInt(cat4.value);
            const cat5 = document.getElementById('cat5');
            const cat5v = parseInt(cat5.value);
            const cat6 = document.getElementById('cat6');
            const cat6v = parseInt(cat6.value);
            
        
            function drawChart() {
              var data = google.visualization.arrayToDataTable([
                ['Categoria', 'Quantidade'],
                ['Estudantes de Graduação', cat1v],
                ['Estudantes de Pós-Graduação', cat2v],
                ['Profissionais de Saúde', cat3v],
                ['Gestores de Saúde', cat4v],
                ['Professores', cat5v],
                ['Usuários dos Serviços de Saúde', cat6v]
              ]);
          
              var options = {
                title: 'Categorias',
                is3D: true,
              };
          
              var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
              chart.draw(data, options);
            }
            </script>

</body>
</html>