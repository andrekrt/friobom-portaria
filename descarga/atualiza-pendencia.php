<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false){

    $id = filter_input(INPUT_POST, 'id');
    $token = filter_input(INPUT_POST, 'token');
    $situacao = filter_input(INPUT_POST, 'situacao');
    $obs = filter_input(INPUT_POST, 'obs');
    $data = date('Y-m-d H:i');
    
    $atualiza = $db->prepare("UPDATE pendencias SET situacao_pendencia = :situacao, obs_resolucao = :obs, data_hora_resolucao = :dataResolucao WHERE id = :id");
    $atualiza->bindValue(':situacao', $situacao);
    $atualiza->bindValue(':obs', $obs);
    $atualiza->bindValue(':dataResolucao', $data);
    $atualiza->bindValue(':id', $id);

    if($atualiza->execute()){
        $atualizaDescarga = $db->prepare("UPDATE descarga SET situacao = :situacaoDesc, pendencia = :solucao WHERE token =:token" );
        $atualizaDescarga->bindValue(':situacaoDesc', 'Aguardando Validação');
        $atualizaDescarga->bindValue(':solucao',$situacao);
        $atualizaDescarga->bindValue(':token', $token);
        if($atualizaDescarga->execute()){
            echo "<script> alert('Pendência Corrigida!)</script>";
            echo "<script> window.location.href='pendencias.php' </script>";
        }else{
            print_r($atualizaDescarga->errorInfo());
        }
    }else{
        print_r($atualiza->errorInfo());
    }

}else{
    echo "<script> alert('Acesso não permitido!')</script>";
    echo "<script> window.location.href='descargas.php' </script>";

}

?>