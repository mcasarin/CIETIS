<?php
session_start();
ob_start();
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
require "config.php";
include_once "include/connect.php";
include_once "include/func.php";
?>
<!DOCTYPE html><html lang="pt-br">
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
    <link href="css/login.css" rel="stylesheet">
    <link href="css/blog.css" rel="stylesheet">
  </head>

<body class="text-center">
    <?php
    // Gera senha
    //echo password_hash('53nh4minha', PASSWORD_DEFAULT);
    //$msg = "";
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    if(!empty($dados['sendLogin'])){
        // var_dump($dados);
        $query_user = "SELECT id,nome,email,pass,status from operadores where email = :email LIMIT 1";
        $result_user = $conn->prepare($query_user);
        $result_user->bindParam(':email',$dados['email']);
        $result_user->execute();
        if(($result_user) AND ($result_user->rowCount() != 0)){
            $user = $result_user->fetch(PDO::FETCH_ASSOC);
            // var_dump($user);
            if(password_verify($dados['password'], $user['pass'])){
              $_SESSION['id'] = $user['id'];
              $_SESSION['nome'] = $user['nome'];
              $_SESSION['status'] = $user['status'];
              header("Location: dashboard.php");
            } else {
              $_SESSION['msg'] = "<p style='color:#ff0000'>Erro: Usuário ou senha inválida!</p>";
            }
        } else {
            $_SESSION['msg'] = "<p style='color:#ff0000'>Erro: Usuário ou senha inválida!</p>";
        }
        
    }

    
    ?>
    
    <main class="form-signin w-100 m-auto">
      <form name="formLogin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
        <img class="mb-4" src="img/cietis.jpg" alt="" width="80" height="80">
        <h1 class="h3 mb-3 fw-normal">Admin Login</h1>
    
        <div class="form-floating">
          <input type="email" name="email" id="email" class="form-control" id="floatingInput" placeholder="name@example.com" value="<?php if(isset($dados['email'])){ echo $dados['email']; } ?>">
          <label for="floatingInput">E-mail</label>
        </div>
        <div class="form-floating">
          <input type="password" name="password" id="password" class="form-control" id="floatingPassword" placeholder="Password" required>
          <label for="floatingPassword">Senha</label>
        </div>
    
        <div class="checkbox mb-3">
        <?php
            if(isset($_SESSION['msg'])){
              echo $_SESSION['msg'];
              unset($_SESSION['msg']);
            }
        ?>
          <label>
            <!-- <input type="checkbox" value="remember-me"> Remember me -->
          </label>
        </div>
        <input class="w-100 btn btn-lg btn-primary" id="sendLogin" name="sendLogin" type="submit" value="Acessar">

        <p class="mt-5 mb-3 text-muted">&copy; Etwas Informática 2023–<?php echo date("Y"); ?></p>
      </form>
    </main>
    
    
        
      </body>
    </html>
    