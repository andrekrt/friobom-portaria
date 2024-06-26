<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false){
    $token = filter_input(INPUT_GET, 'token');
    $situacao = 'Validada';
    $data = date('Y-m-d H:i');

    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE descarga SET situacao = :situacao, data_hora_validacao = :tempoValidacao  WHERE token = :token" );
        $sql->bindValue(':situacao', $situacao);
        $sql->bindValue(':tempoValidacao', $data);
        $sql->bindValue(':token', $token);
        $sql->execute();

        $db->commit();
        $_SESSION['msg'] = 'Validada!';
        $_SESSION['icon']='success';
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Validar';
        $_SESSION['icon']='error';
    }

    header("Location: descargas.php");
    exit();
}

?>