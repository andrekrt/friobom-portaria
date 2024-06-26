<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false){
    $token = filter_input(INPUT_POST, 'tokenIniciar');
    $bonus = filter_input(INPUT_POST, 'bonus');
    $doca = filter_input(INPUT_POST, 'doca');
    $situacao = "Descarga Iniciada";
    $data = date('Y-m-d H:i');

    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE descarga SET situacao = :situacao, bonus = :bonus, doca = :doca, data_hora_iniciodesc = :dataInicioDesc WHERE token = :token" );
        $sql->bindValue(':situacao', $situacao);
        $sql->bindValue(':bonus', $bonus);
        $sql->bindValue(':doca', $doca);
        $sql->bindValue(':dataInicioDesc', $data);
        $sql->bindValue(':token', $token);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Descarga Iniciada com Sucesso!';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Iniciar Descarga';
        $_SESSION['icon']='error';
    }

}
header("Location: descargas.php");
exit();
?>