<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false){
    $token = filter_input(INPUT_GET, 'token');
    $situacao = 'Finalizada';
    $sql = $db->prepare("UPDATE descarga SET situacao = :situacao WHERE token = :token" );
    $sql->bindValue(':situacao', $situacao);
    $sql->bindValue(':token', $token);

    if($sql->execute()){
        echo "<script> alert('Finalizada!')</script>";
        echo "<script> window.location.href='descargas.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }

}

?>