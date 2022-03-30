<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false){
    $token = filter_input(INPUT_POST, 'tokenIniciar');
    $bonus = filter_input(INPUT_POST, 'bonus');
    $doca = filter_input(INPUT_POST, 'doca');
    $situacao = "Descarga Iniciada";
    $data = date('Y-m-d H:i');

    $sql = $db->prepare("UPDATE descarga SET situacao = :situacao, bonus = :bonus, doca = :doca, data_hora_iniciodesc = :dataInicioDesc WHERE token = :token" );
    $sql->bindValue(':situacao', $situacao);
    $sql->bindValue(':bonus', $bonus);
    $sql->bindValue(':doca', $doca);
    $sql->bindValue(':dataInicioDesc', $data);
    $sql->bindValue(':token', $token);

    //echo "$token<br>$situacao<br>$bonus<br>$doca";

    if($sql->execute()){
        echo "<script> alert('Atualizado!')</script>";
        echo "<script> window.location.href='descargas.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }

}

?>