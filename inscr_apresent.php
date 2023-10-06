<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
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
    <title>Inscrição de Trabalhos - CIETIS</title>
   

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
                    <div class="mb-3"><h3 class="tituloForm text-center">Formulário de Inscrição de Trabalhos</h3></div>
                    <div class="boxCab text-center">Agradecemos o interesse e gostaríamos que você lê-se as regras gerais e preenchesse o formulário abaixo com seus dados e do trabalho submetido.</div>
                    </div>
            </div>
            <?php
                $msg = "";
                $idTrabalho = "";
                $titulovalid = "";
                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if(!empty($dados['SendTrabalho'])){
                    // var_dump($dados);
                    // $verify = checkmail($dados['email']);
                    if ($dados['orcid'] == "" || $dados['orcid'] == "0000000000000000000" || $dados['orcid'] == "9999999999999999999") {
                        $msg = "<div class='alert alert-danger'>Você precisa preencher com o número ORCID (.<br>Aguarde o recarregamento da página automaticamente</div>";
                        header("refresh: 5");
                    } else {
                    $activCode = createHash();
                    $status = '0';
                    $titulovalid = preg_replace('/\s+/', '_', $dados['titulo']);

                    $query_cadTrab = "insert into listaTrabalhos (nome, email, phone, titulo, resumo, descritores, referencias, status, activCode, eixo, tipo, instituicao, orcid, titulovalid) values (:nome, :email, :phone, :titulo, :resumo, :descritores, :referencias, :status, :activCode, :eixo, :tipo, :instituicao, :orcid, :titulovalid)";
                    $exec_cadTrab = $conn->prepare($query_cadTrab);
                    $exec_cadTrab->bindParam(':nome',$dados['nomecompleto']);
                    $exec_cadTrab->bindParam(':email',$dados['email']);
                    $exec_cadTrab->bindParam(':phone',$dados['phone']);
                    $exec_cadTrab->bindParam(':titulo',$dados['titulo']);
                    $exec_cadTrab->bindParam(':resumo',$dados['resumo']);
                    $exec_cadTrab->bindParam(':descritores',$dados['descritores']);
                    $exec_cadTrab->bindParam(':referencias',$dados['referencias']);
                    $exec_cadTrab->bindParam(':status',$status);
                    $exec_cadTrab->bindParam(':activCode',$activCode);
                    $exec_cadTrab->bindParam(':eixo',$dados['eixo']);
                    $exec_cadTrab->bindParam(':tipo',$dados['tipo']);
                    $exec_cadTrab->bindParam(':instituicao',$dados['instituicao']);
                    $exec_cadTrab->bindParam(':orcid',$dados['orcid']);
                    $exec_cadTrab->bindParam(':titulovalid',$titulovalid);
                    $exec_cadTrab->execute();

                    if($exec_cadTrab->rowCount()){
                        // Insere coautores
                        $idTrabalho = $GLOBALS['conn']->lastInsertId();
                        
                        for ($n=2; $n <= 6; ++$n ){
                            $nome = "nome".$n;
                            
                            if($dados[$nome] != ""){
                                $nome = "nome".$n;
                                $email = "email".$n;
                                $phone = "phone".$n;
                                $orcid = "orcid".$n;
                                $instituicao = "instituicao".$n;

                                // echo $dados["$nome"]."<br>";
                                // echo $dados["$email"]."<br>";
                                // echo $dados["$phone"]."<br>";
                                // echo $dados["$orcid"]."<br>";
                                // echo $dados["$instituicao"]."<br>";
                                // echo $idTrabalho."<br>";

                                $query_cadAutor = "insert into coautores (id_listaTrabalhos, nome, email, phone, orcid, instituicao) values (:id_listaTrabalhos, :nome, :email, :phone, :orcid, :instituicao)";
                                $exec_cadAutor = $conn->prepare($query_cadAutor);
                                $exec_cadAutor->bindParam(':id_listaTrabalhos',$idTrabalho);
                                $exec_cadAutor->bindParam(':nome',$dados["$nome"]);
                                $exec_cadAutor->bindParam(':email',$dados["$email"]);
                                $exec_cadAutor->bindParam(':phone',$dados["$phone"]);
                                $exec_cadAutor->bindParam(':orcid',$dados["$orcid"]);
                                $exec_cadAutor->bindParam(':instituicao',$dados["$instituicao"]);
                                $exec_cadAutor->execute();
                            }
                        }
                        
                        $msg = "<div class='alert alert-success'>Cadastro do trabalho efetuado com sucesso!<br>Você receberá um e-mail para confirmá-lo, clique no link ou acesse pelo navegador o endereço desse link.</div>";
                                               
                        confirmaTrabalho($dados['nomecompleto'],$dados['email'], $titulovalid, $activCode);
                        header("refresh: 5");

                    } else {
                        $msg = "<div class='alert alert-danger'>Houve uma falha no cadastro. Tente novamente!</div>";
                        //echo $status."<br>";
                        //echo $activCode."<br>";
                    }
                }
                    echo $msg;
                }
            ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
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
                <label for="phone" class="col-sm-2 col-form-label">Telefone</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="5511999999999" size="15" maxlength="14" required>
                    </div>
                <label for="orcid" class="col-sm-2 col-form-label">ORCID</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="orcid" id="orcid" placeholder="0000-0000-0000-0000" size="20" maxlength="19" required>
                    </div>
            </div>
            <div class="row mb-3">
                <label for="instituicao" class="col-sm-2 col-form-label" for="instituicao">Instituição</label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" name="instituicao" id="instituicao" placeholder="Nome da Escola, Faculdade ou Universidade" maxlength="151" required>
                    </div>
            </div>
            <div class="row mb-3">
                <label for="titulo" class="col-sm-2 col-form-label" for="titulo">Título</label>
                    <div class="col-sm-8">
                    <input type="text" class="form-control" name="titulo" id="titulo" placeholder="Título do trabalho (até 150 caracteres)" maxlength="151" required>
                    </div>
            </div>
            <div class="row mb-3">
                <div class="d-grid gap-2 col-4 mx-auto">
                    <!-- botão modal -->
                    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#coautores">
                        Cadastre coautores aqui (até 5 autores)
                    </button>
                    <!-- Modal coautores -->
                    <div class="modal fade" id="coautores" tabindex="-1" aria-labelledby="ModalCoautores" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="ModalCoautores">Autores</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="nome2"> Nome do segundo autor: </span>
                                        <input type="text" class="form-control form-control-sm" name="nome2" id="nome2" placeholder="Nome completo autor 2">
                                    </div>
                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="email2">Email:</span>
                                        <input type="email" class="form-control form-control-sm" name="email2" id="email2" placeholder="nome@email.com">
                                    </div>
                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="phone2">Telefone</span>
                                        <input type="text" class="form-control form-control-sm" name="phone2" id="phone2" placeholder="Telefone" size="15" maxlength="14">

                                        <span class="input-group-text" id="orcid2">ORCID</span>
                                        <input type="text" class="form-control form-control-sm" name="orcid2" id="orcid2" placeholder="ORCID" size="20" maxlength="19">     
                                    </div>

                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="instituicao2">Instituição</span>
                                        <input type="text" class="form-control form-control-sm" name="instituicao2" id="instituicao2" placeholder="Instituição autor 2">
                                    </div>

                                </div>
                            <hr>
                                <div class="row">
                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="nome3"> Nome do terceiro autor: </span>
                                        <input type="text" class="form-control form-control-sm" name="nome3" id="nome3" placeholder="Nome completo autor 3">
                                    </div>
                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="email3">Email:</span>
                                        <input type="email" class="form-control form-control-sm" name="email3" id="email3" placeholder="nome@email.com">
                                    </div>
                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="phone3">Telefone</span>
                                        <input type="text" class="form-control form-control-sm" name="phone3" id="phone3" placeholder="Telefone" size="15" maxlength="14">

                                        <span class="input-group-text" id="orcid3">ORCID</span>
                                        <input type="text" class="form-control form-control-sm" name="orcid3" id="orcid3" placeholder="ORCID" size="20" maxlength="19">     
                                    </div>

                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="instituicao3">Instituição</span>
                                        <input type="text" class="form-control form-control-sm" name="instituicao3" id="instituicao3" placeholder="Instituição autor 3">
                                    </div>
                                </div>
                            <hr>
                                <div class="row">
                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="nome4"> Nome do quarto autor: </span>
                                        <input type="text" class="form-control form-control-sm" name="nome4" id="nome4" placeholder="Nome completo autor 4">
                                    </div>
                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="email4">Email:</span>
                                        <input type="email" class="form-control form-control-sm" name="email4" id="email4" placeholder="nome@email.com">
                                    </div>
                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="phone4">Telefone</span>
                                        <input type="text" class="form-control form-control-sm" name="phone4" id="phone4" placeholder="Telefone" size="15" maxlength="14">

                                        <span class="input-group-text" id="orcid4">ORCID</span>
                                        <input type="text" class="form-control form-control-sm" name="orcid4" id="orcid4" placeholder="ORCID" size="20" maxlength="19">     
                                    </div>

                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="instituicao4">Instituição</span>
                                        <input type="text" class="form-control form-control-sm" name="instituicao4" id="instituicao4" placeholder="Instituição autor 4">
                                    </div>
                                </div>
                            <hr>
                                <div class="row">
                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="nome5"> Nome do quinto autor: </span>
                                        <input type="text" class="form-control form-control-sm" name="nome5" id="nome5" placeholder="Nome completo autor 5">
                                    </div>
                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="email5">Email:</span>
                                        <input type="email" class="form-control form-control-sm" name="email5" id="email5" placeholder="nome@email.com">
                                    </div>
                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="phone5">Telefone</span>
                                        <input type="text" class="form-control form-control-sm" name="phone5" id="phone5" placeholder="Telefone" size="15" maxlength="14">

                                        <span class="input-group-text" id="orcid5">ORCID</span>
                                        <input type="text" class="form-control form-control-sm" name="orcid5" id="orcid5" placeholder="ORCID" size="20" maxlength="19">     
                                    </div>

                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="instituicao5">Instituição</span>
                                        <input type="text" class="form-control form-control-sm" name="instituicao5" id="instituicao5" placeholder="Instituição autor 5">
                                    </div>
                                </div>
                            <hr>
                                <div class="row">
                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="nome6"> Nome do sexto autor: </span>
                                        <input type="text" class="form-control form-control-sm" name="nome6" id="nome6" placeholder="Nome completo autor 6">
                                    </div>
                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="email6">Email:</span>
                                        <input type="email" class="form-control form-control-sm" name="email6" id="email6" placeholder="nome@email.com">
                                    </div>
                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="phone6">Telefone</span>
                                        <input type="text" class="form-control form-control-sm" name="phone6" id="phone6" placeholder="Telefone" size="15" maxlength="14">

                                        <span class="input-group-text" id="orcid6">ORCID</span>
                                        <input type="text" class="form-control form-control-sm" name="orcid6" id="orcid6" placeholder="ORCID" size="20" maxlength="19">     
                                    </div>

                                    <div class="input-group sm-3">
                                        <span class="input-group-text" id="instituicao6">Instituição</span>
                                        <input type="text" class="form-control form-control-sm" name="instituicao6" id="instituicao6" placeholder="Instituição autor 6">
                                    </div>
                                </div>

                            </div>

                          
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Salvar</button>
                          </div>
                        </div>
                      </div>
                    </div> <!-- end Modal coautores -->

                </div>

            </div>
            <div class="row mb-3">
                <div class="form-floating">
                    <textarea class="form-control" placeholder="Resumo do trabalho" name="resumo" id="resumo" style="height: 100px" maxlength="3001"></textarea>
                    <label for="resumo">Resumo do trabalho (até 3000 caracteres)</label>
                </div>
            </div>
            <div class="row mb-3">
                <div class="form-floating">
                    <textarea class="form-control" placeholder="Leave a comment here" name="descritores" id="descritores" style="height: 100px" maxlength="501"></textarea>
                    <label for="descritores">Descritores (de 3 a 5 descritores separados por vírgula, até 500 caracteres no total)</label>
                </div>
            </div>
            <div class="row mb-3">
                <div class="form-floating">
                    <textarea class="form-control" placeholder="Referências" name="referencias" id="referencias" style="height: 100px" maxlength="501"></textarea>
                    <label for="referencias">Referências (limite de 6 referências no formato ABNT, até 500 caracteres no total)</label>
                </div>
            </div>
            <div class="row mb-3">
                <label for="eixo" class="col-sm-2 col-form-label">Eixo temático</label>
                    <div class="col-sm-3">
                    <select name="eixo" id="eixo" class="form-select form-select-md mb-3" aria-label=".form-select-lg cat">
                      <option selected>Selecione o Eixo</option>
                            <?php
                                $sqlEixo = "select id,eixo from eixos";
                                $resultEixo = $conn->query($sqlEixo);
                                while($row = $resultEixo->fetch()){
                                    ?>
                                    <option value="<?php echo $row["id"]; ?>"><?php echo $row["eixo"]; ?></option>
                                <?php
                                }
                                    ?>
                    </select>
                    </div>
            </div>
            <div class="row mb-3">
                <label for="tipo" class="col-sm-2 col-form-label">Tipo de trabalho</label>
                    <div class="col-sm-3">
                    <select name="tipo" id="tipo" class="form-select form-select-md mb-3" aria-label=".form-select-lg cat">
                      <option selected>Selecione o Tipo</option>
                            <?php
                                $sqlTipo = "select id,tipo from tipos";
                                $resultTipo = $conn->query($sqlTipo);
                                while($row = $resultTipo->fetch()){
                                    ?>
                                    <option value="<?php echo $row["id"]; ?>"><?php echo $row["tipo"]; ?></option>
                                <?php
                                }
                                    ?>
                    </select>
                    </div>
            </div>

            <div class="row">
                <div class="d-grid gap-2 col-4 mx-auto">
                    <input type="submit" name="SendTrabalho" id="SendTrabalho" class="btn btn-outline-success" value="Enviar">
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