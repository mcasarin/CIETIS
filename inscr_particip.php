<?php
// ini_set('display_errors', 1);
// error_reporting(E_ALL);
require "config.php";
include_once "./include/connect.php";
include_once "./include/func.php";
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="CIETIS">
    <meta name="author" content="Marcio Casarin, Etwas Informatica">
    <title>Inscrição Participante - CIETIS</title>
   

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">   
    <!-- Custom styles for this template -->
    <link href="https://fonts.googleapis.com/css?family=Playfair&#43;Display:700,900&amp;display=swap" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/blog.css" rel="stylesheet">
    </head>
  <body>
    <div class="container">
        <div class="formBox">
            <div class="row">
                <div class="col">
                    <div class="mb-3"><h3 class="tituloForm text-center">Formulário de Inscrição</h3></div>
                    <div class="boxCab text-center">Agradecemos o interesse e gostaríamos que você preenchesse o formulário abaixo com seus dados.</div>
                    </div>
            </div>
            <?php
                $msg = "";
                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if(!empty($dados['SendInscricao'])){
                    //var_dump($dados);
                    $verify = checkmail($dados['email']);
                    if($verify == true) {
                        $msg = "<div class='alert alert-danger'>Já existe uma inscrição com esse e-mail!<br>Somente é permitida uma inscrição por e-mail<br>Aguarde o recarregamento da página automaticamente</div>";
                        header("refresh: 5");
                    } elseif ($dados['cpf'] == "" && $dados['doc'] == "") {
                        $msg = "<div class='alert alert-danger'>Você precisa preencher com um documento.<br>Aguarde o recarregamento da página automaticamente</div>";
                        header("refresh: 5");
                    /*} elseif ($dados['cpf'] != ""){
                        if(validaCPF($dados['cpf']) == false) {
                            $msg = "<div class='alert alert-danger'>O CPF informado é inválido!<br>Necessário informar um CPF válido.<br>Aguarde o recarregamento da página automaticamente</div>";
                            header("refresh: 5");
                        }*/
                    } else {
                    $activCode = createHash();
                    $status = '0';
                    $query_cadPart = "insert into listaInscritos (nome, email, phone, categoria, cpf, doc, pais, status, activCode, instituicao, cidade, estado, rebetis) values (:nome, :email, :phone, :categoria, :cpf, :doc, :pais, :status, :activCode, :instituicao, :cidade, :estado, :rebetis)";
                    $exec_cadPart = $conn->prepare($query_cadPart);
                    $exec_cadPart->bindParam(':nome',$dados['nomecompleto']);
                    $exec_cadPart->bindParam(':email',$dados['email']);
                    $exec_cadPart->bindParam(':phone',$dados['phone']);
                    $exec_cadPart->bindParam(':categoria',$dados['categoria']);
                    $exec_cadPart->bindParam(':cpf',$dados['cpf']);
                    $exec_cadPart->bindParam(':doc',$dados['doc']);
                    $exec_cadPart->bindParam(':pais',$dados['pais']);
                    $exec_cadPart->bindParam(':status',$status);
                    $exec_cadPart->bindParam(':activCode',$activCode);
                    $exec_cadPart->bindParam(':instituicao',$dados['instituicao']);
                    $exec_cadPart->bindParam(':cidade',$dados['cidade']);
                    $exec_cadPart->bindParam(':estado',$dados['estado']);
                    $exec_cadPart->bindParam(':rebetis',$dados['rebetis']);
                    $exec_cadPart->execute();

                    if($exec_cadPart->rowCount()){
                        $msg = "<div class='alert alert-success'>Inscrição efetuada com sucesso!<br>Você receberá um e-mail para confirmar sua inscrição, clique no link ou acesse pelo navegador o endereço desse link.</div>";
                        confirmaInscricao($dados['nomecompleto'],$dados['email'], $activCode);
                        header("refresh: 5");
                    }else{
                        $msg = "<div class='alert alert-danger'>Houve uma falha no cadastro. Tente novamente!</div>";
                        //echo $status."<br>";
                        //echo $activCode."<br>";
                    }
                }
                    echo $msg;
                }
            ?>
            <form name="formApresent" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <div class="row mb-3">
                <label for="nomecompleto" class="col-sm-2 col-form-label" for="nomecompleto">Nome completo</label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" name="nomecompleto" id="nomecompleto" placeholder="Preferencialmente sem abreviações" required>
                    </div>
            </div>
            <div class="row mb-3">
                <label for="email" class="col-sm-2 col-form-label">E-mail</label>
                    <div class="col-sm-8">
                    <input type="email" class="form-control" name="email" id="email" placeholder="nome@email.com" required>
                    </div>
            </div>
            <div class="row mb-3">
                <label for="cpf" class="col-sm-2 col-form-label">CPF</label>
                    <div class="col-sm-3">
                        <input type="tel" class="form-control" name="cpf" id="cpf" placeholder="Somente números" size="12" maxlength="11">
                    </div>
            </div>
            <div class="row mb-3">
                <label for="cpf" class="col-sm-2 col-form-label">Sou estrangeiro</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="doc" id="doc" placeholder="Documento de identidade" size="20" maxlength="20">
                    </div>
                <label for="cpf" class="col-sm-2 col-form-label">País de origem</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="pais" id="pais" placeholder="" size="30" maxlength="30">
                    </div>
            </div>
            <div class="row mb-3">
                <label for="phone" class="col-sm-2 col-form-label">Telefone</label>
                    <div class="col-sm-3">
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="5511999999999" size="15" maxlength="14" required>
                    </div>
                <label for="categoria" class="col-sm-2 col-form-label" for="categoria">Categoria</label>
                    <div class="col-sm-3">
                    <select name="categoria" id="categoria" class="form-select form-select-md" aria-label=".form-select-lg cat" required>
                      <option value="">Selecione uma categoria</option>
                        <?php
                            $sqlCat = "select id,categoria from categorias";
                            $resultCat = $conn->query($sqlCat);
                            while($row = $resultCat->fetch()){
                        ?>
                            <option value="<?php echo $row["id"]; ?>"><?php echo $row["categoria"]; ?></option>
                        <?php
                            }
                            
                        ?>
                    </select>
                    </div>
            </div>
            <div class="row mb-3">
                <label for="instituicao" class="col-sm-2 col-form-label">Nome da Instituição</label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" name="instituicao" id="instituicao" placeholder="Universidade, Faculdade, Escola" required>
                    </div>
            </div>
            <div class="row mb-3">
                <label for="cidade" class="col-sm-2 col-form-label">Cidade</label>
                    <div class="col-sm-3">
                    <input type="text" class="form-control" name="cidade" id="cidade" placeholder="Onde mora..." required>
                    </div>
                
                <label for="estado" class="col-sm-2 col-form-label">Estado</label>
                    <div class="col-sm-3">
                    <select name="estado" id="estado" class="form-select form-select-md mb-3" aria-label=".form-select-lg cat" required>
                      <option value="">Selecione seu Estado</option>
                            <?php
                                $sqlEstado = "select id,estado from estados";
                                $resultEstado = $conn->query($sqlEstado);
                                while($row = $resultEstado->fetch()){
                                    ?>
                                    <option value="<?php echo $row["id"]; ?>"><?php echo $row["estado"]; ?></option>
                                <?php
                                }
                                    ?>
                    </select>
                    </div>
            </div>
            <div class="row mb-3 justify-content-center">
                <div class="col-sm-3">
                    <label for="rebetis" class="col-sm-12 col-form-label center">Gostaria de se vincular à ReBETIS?</label>
                </div>
            </div>
            <div class="row mb-3 justify-content-center">
                    <div class="col-sm-1">
                        <input type="radio" class="form-check-input" name="rebetis" id="rebetis" value="1"> 
                        <label class="form-check-label" for="rebetissim">
                            SIM
                        </label>
                    </div>
                    <div class="col-sm-1">
                        <input type="radio" class="form-check-input" name="rebetis" id="rebetis" value="0">
                        <label class="form-check-label" for="rebetisnao">
                            NÃO
                        </label>
                    </div>
                    <div class="col-sm-2">
                        <input type="radio" class="form-check-input" name="rebetis" id="rebetis" value="2">
                        <label class="form-check-label" for="rebetisnao">
                            Já sou vinculado
                        </label>
                    </div>
                    
            </div>

            <div class="row">
                <div class="d-grid gap-2 col-4 mx-auto">
                    <input type="submit" name="SendInscricao" id="SendInscricao" class="btn btn-outline-success" value="Enviar">
                </div>
            </div>
        </div>
    </div>
        <br>
        <footer class="blog-footer">
            <p>CIETIS - Colóquio Internacional de Educação e Trabalho Interprofissional em Saúde. <?php echo date("Y"); ?></p>
            <p>
                <a href="index.php">Volta para o Início</a>
            </p>
            <span>Desenvolvido e hospedado por  <a href="https://etwas.com.br/" target="_blank">Etwas Informática</a>.</span>
        </footer>
        <!-- end body -->
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    </body>
</html>
<?php
$conn = null;
// eof
?>
