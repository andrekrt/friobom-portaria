<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false){
    $token = filter_input(INPUT_GET, 'token');
    $situacao = 'Validada';
    $data = date('Y-m-d H:i');
    $sql = $db->prepare("UPDATE descarga SET situacao = :situacao, data_hora_validacao = :tempoValidacao  WHERE token = :token" );
    $sql->bindValue(':situacao', $situacao);
    $sql->bindValue(':tempoValidacao', $data);
    $sql->bindValue(':token', $token);

    //echo "$token<br>$situacao<br>";

    if($sql->execute()){
        echo "<script> alert('Validada!')</script>";
        echo "<script> window.location.href='descargas.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }

}

?>