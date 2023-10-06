<?php
// Mostra todos os erros
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
header("Content-type: text/html; charset=iso-8859-1");
//include "config.php";
include_once "connect.php";

require "./include/PHPMailer/src/MyPHPMailer.php"; // classe de config global de email


function checkmail ($email){
    $sql = "select id from listaInscritos where email=:email";
    $result = $GLOBALS['conn']->prepare($sql);
    $result->bindParam(':email',$email);
    $result->execute();
    if($result->rowCount() > 0){
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['id'];
    }else{
        return false;
    }
}

function confirmaInscricao($name, $email, $activCode){
    
    // PHPMailer
    $base_url = BASE_URL;
    try {
    $mail_body = "
    <p>Olá, ".$name.",</p>
    <p>Obrigado pela sua inscrição. Utilize o link abaixo para confirmá-la!</p>
    <p>https://etwas.com.br/cietis/email_verification.php?email=".$email."&activation_code=".$activCode."</p>
    <p><b>Caso não tenha sido você, somente desconsidere.</b></p>
    <p>Caso tenha alguma restrição, copie e cole o endereço no navegador.</p>
    <p>Você poderá utilizar esse mesmo link para envio do comprovante de pagamento da inscrição, assim como o e-mail e WhatsApp da coordenação do evento.</p>
    <p>Nossos agradecimentos.</p>
    <p>CIETIS - 6º Colóquio Internacional de Educação e Trabalho Interprofissional em Saúde.</p>";
    $mail_body = utf8_decode($mail_body);
    $mail = new MyPHPMailer(true, $mail_body);
    $mail->addAddress($email);
    $mail->Subject = 'Checando e-mail - Inscrito - CIETIS'; // precisa ajustar caracteres pt-br
    $mail->send();
    } catch (Exception $e){
        echo 'Houston, i have a problem ' . get_class($e) . ': ' . $e->getMessage();
    }
    // end PHPMailer
}

function checkInscricao (array $data) {
    $sql = $GLOBALS['conn']->query("update listaInscritos set status='1' where arq_pgto='0' and status='0' and email='".$data['email']."' and activCode='".$data['activCode']."'");
    if($sql->rowCount() > 0){
        return '0';
    }else{
        $sqlinside = $GLOBALS['conn']->query("select email,activCode from listaInscritos where status='1' and arq_pgto='0' and email='".$data['email']."' and activCode='".$data['activCode']."'");
        if($sqlinside->rowCount() > 0){
            return '1';
        } else {
            return '2';
        }
    }
}

function confirmaTrabalho($name, $email, $titulo, $activCode){
    
    // PHPMailer
    $base_url = BASE_URL;
    try {
    $mail_body = "
    <p>Olá, ".$name.",</p>
    <p>Obrigado pela inscrição do seu trabalho com título: ".$titulo.".<br>Utilize o link abaixo para confirmá-la!</p>
    <p>https://etwas.com.br/cietis/email_verification_trab.php?email=".$email."&activation_code=".$activCode."&titulo=".$titulo."</p>
    <p><b>Caso não tenha sido você, somente desconsidere.</b></p>
    <p>Caso tenha alguma restrição, copie e cole o endereço no navegador.</p>
    <p>Você poderá utilizar esse mesmo link para envio do comprovante de pagamento da inscrição, assim como o e-mail e WhatsApp da coordenação do evento.</p>
    <p>Nossos agradecimentos.</p>
    <p>CIETIS - 6º Colóquio Internacional de Educação e Trabalho Interprofissional em Saúde.</p>";
    $mail_body = utf8_decode($mail_body);
    $mail = new MyPHPMailer(true, $mail_body);
    $mail->addAddress($email);
    $mail->Subject = 'Checando e-mail - Trabalho - CIETIS'; // precisa ajustar caracteres pt-br
    $mail->send();
    } catch (Exception $e){
        echo 'Houston, i have a problem ' . get_class($e) . ': ' . $e->getMessage();
    }
    // end PHPMailer
}

function checkTrabalho (array $data) {

    $sql = $GLOBALS['conn']->query("update listaTrabalhos set status='1' where status='0' and email='".$data['email']."' and titulovalid='".$data['titulo']."' and activCode='".$data['activCode']."'");
    if($sql->rowCount() > 0){
        return '0';
    }else{
        $sqlinside = $GLOBALS['conn']->query("select email,activCode from listaTrabalhos where status='1' and email='".$data['email']."' and titulo='".$data['titulo']."' and activCode='".$data['activCode']."'");
        if($sqlinside->rowCount() > 0){
            return '1';
        }
    }
}

function createHash(){
    $activation_code = rand(120000,8908950);
    return $activation_code;
}


function validaCPF($cpf) {
 
    // Extrai somente os números
    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
     
    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;

}
