<?php

session_start();
require("../conexao.php");

$token = filter_input(INPUT_POST, 'tokenExclui');
$motivo = filter_input(INPUT_POST, 'motivo');

$db->beginTransaction();

try{
    $sql = $db->prepare("UPDATE descarga SET excluido=:exclusao, motivo_exclusao=:motivo WHERE token = :token");
    $sql->bindValue(':token',$token);
    $sql->bindValue(':motivo', $motivo);
    $sql->bindValue(':exclusao', '1');
    $sql->execute();

    $db->commit();

    $_SESSION['msg'] = 'Descarga Excluído com Sucesso!';
    $_SESSION['icon']='success';

}catch(Exception $e){
    $db->rollBack();
    $_SESSION['msg'] = 'Erro ao Excluir Descarga';
    $_SESSION['icon']='error';
}
    
header("Location: descargas.php");
exit();
?>