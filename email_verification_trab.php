<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require "config.php";
include_once "./include/connect.php";
include_once "./include/func.php";

$msg = "";
$btn = "";

if(isset($_REQUEST) && count($_REQUEST) > 0){
    $email = $_REQUEST['email'];
    $activCode = $_REQUEST['activation_code'];
    $titulo = $_REQUEST['titulo'];
    
    $check = array(
        'email' => $email,
        'activCode' => $activCode,
        'titulo' => $titulo
    );
    $return = checkTrabalho($check);
    

    if($return == '0'){
        $msg = "<label class='text-success text-center'>Sua inscrição de trabalho foi ativada com sucesso!</label><br />
        <label class='text-info'>Aguarde o contato da coordenação para o envio do trabalho completo.</label>";
        
    } elseif($return == '1') {
        $msg = "<label class='text-success text-center'>Inscrição do trabalho já foi ativada.</label><br />
        <label class='text-info'>Caso tenha enviado outro trabalho, utilize o link específico para ativá-lo.<br />Cada postagem possui o seu!</label>";
        
    } else {
      $msg = "<label class='text-warning text-center'>Tem algo errado.</label>";
      
    }
    ?>
    <!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="CIETIS">
    <meta name="author" content="Marcio Casarin, Etwas Informatica">
    <title>Inscrição de Trabalho - CIETIS</title>
   

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">   
    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Playfair&#43;Display:700,900&amp;display=swap" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/blog.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  </head>
  <body>
    <main role="main" class="container">
      <div class="container">
      <div class="row g-5">
  <div class="col-md-6"></div><div class="col-md-6"><img class="float-end" src="./img/cietis.jpg" width="80px" height="80px" alt="Logo CIETIS"></div>
</div>
      <h3 class="tituloForm text-center">Verificação de e-mail.</h3>
      <br>
        <div class="row justify-content-center mt-10">
            <div class="col-xs-12 col-sm-12 col-md-12 col-xl-4 col-lg-4 center-align center-block">
                <div class="card shadow-lg p-3 mb-5 bg-white rounded"> 
                    <p><?php echo $msg; ?></p>
                </div>
                <!-- <div class="card shadow-lg p-3 mb-5 bg-white rounded"> 
                <p class="text-center">Envie seu comprovante de pagamento selecionando a imagem abaixo.</p>
                    <?php
                        // $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                        // if(!empty($dados['envioArquivo'])){
                        //     //var_dump($dados);
                        //     $arquivo = $_FILES['arq_pgto'];
                        //     //var_dump($arquivo);
                            
                        //     if((isset($arquivo['name'])) and (!empty($arquivo['name']))){
                        //         $id = checkmail($email);
                        //         //echo "Este é o ID: ".$id;
                        //         $mime_type = mime_content_type($arquivo['tmp_name']);
                        //         $allowed_file_types = ['image/png', 'image/jpeg', 'image/jpg', 'application/pdf'];
                        //           if (! in_array($mime_type, $allowed_file_types)) {
                        //             echo "<span class='alert alert-warning'>Seu comprovante está em um formato/extensão não permitido.</span><br>
                        //             <span>São permitidos somente arquivos: <strong>png, jpeg, jpg, pdf</strong></span>";
                        //           } else {
                        //             $diretorio = "comprovantes/$id/";
                        //             mkdir($diretorio,0755);
                        //             $nomearquivo = $arquivo['name'];
                        //             $move = move_uploaded_file($arquivo['tmp_name'], $diretorio.$nomearquivo);
                        //             if($move == true){
                        //               $sql = $GLOBALS['conn']->query("update listaInscritos set status='2', arq_pgto='".$nomearquivo."' where email='".$email."'");
                        //               //echo "SQL: ".$sql;
                        //               if($sql->rowCount() > 0){
                        //                 echo "<span class='alert alert-success'>Seu comprovante foi armazenado com sucesso!<br />Agradecemos.</span>";
                        //                 header("refresh: 3");
                        //               } else {
                        //                 $remove = @unlink($diretorio.$nomearquivo);
                        //               }
                        //             }
                        //         }
                        //     }
                        // }
                        
                      ?>
                      
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="email" value="<?php echo $email;?>">
                            <input type="hidden" name="activCode" value="<?php echo $activCode;?>">
                            <div class="mb-3">
                              <input class="form-control form-control-sm" type="file" name="arq_pgto">
                              <br>
                              <?php // echo $btn; ?>
                            </div>
                        </form>
                      
                    </div>
                  </div>
                </div> -->
      
    </div>
  </div> 
                </div>
            </div>           
        </div>
        <footer class="blog-footer">
                <p class="mt-5 text-center mb-3 text-muted">&copy; Etwas Informática <?php echo date('Y'); ?></p>
        </footer>
      </div>
    </main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>
<?php
} else {
  echo "Opa!!!";
}
?>