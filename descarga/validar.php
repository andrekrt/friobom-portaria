<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false){
    $token = filter_input(INPUT_POST, 'token');
    $bonus = filter_input(INPUT_POST, 'bonus');
    $situacao = 'Validada';
    $sql = $db->prepare("UPDATE descarga SET situacao = :situacao, bonus = :bonus WHERE token = :token" );
    $sql->bindValue(':situacao', $situacao);
    $sql->bindValue(':token', $token);
    $sql->bindValue(':bonus', $bonus);

    if($sql->execute()){
        echo "<script> alert('Validada!')</script>";
        echo "<script> window.location.href='descargas.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }

}

?>