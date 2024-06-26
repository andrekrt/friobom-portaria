<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false){

    $id = filter_input(INPUT_POST, 'id');
    $token = filter_input(INPUT_POST, 'token');
    $situacao = filter_input(INPUT_POST, 'situacao');
    $obs = filter_input(INPUT_POST, 'obs');
    $data = date('Y-m-d H:i');

    $db->beginTransaction();

    try{
        $atualiza = $db->prepare("UPDATE pendencias SET situacao_pendencia = :situacao, obs_resolucao = :obs, data_hora_resolucao = :dataResolucao WHERE id = :id");
        $atualiza->bindValue(':situacao', $situacao);
        $atualiza->bindValue(':obs', $obs);
        $atualiza->bindValue(':dataResolucao', $data);
        $atualiza->bindValue(':id', $id);
        $atualiza->execute();

        $atualizaDescarga = $db->prepare("UPDATE descarga SET situacao = :situacaoDesc, pendencia = :solucao WHERE token =:token" );
        $atualizaDescarga->bindValue(':situacaoDesc', 'Aguardando Validação');
        $atualizaDescarga->bindValue(':solucao',$situacao);
        $atualizaDescarga->bindValue(':token', $token);
        $atualizaDescarga->execute();

        $db->commit();

        $_SESSION['msg'] = 'Pendência Corrigida!';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Lançar Despesa';
        $_SESSION['icon']='error';
    }
    
}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: descargas.php");
exit();
?>