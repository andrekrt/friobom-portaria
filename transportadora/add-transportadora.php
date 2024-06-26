<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario']==99 || $_SESSION['tipousuario']==3)){
    $filial = $_SESSION['filial'];
    $nome = filter_input(INPUT_POST, 'nome');
    $valor = str_replace(",",".",filter_input(INPUT_POST, 'valorVol')) ;

    $db->beginTransaction();

    try{
        $sql = $db->prepare("INSERT INTO transportadoras (nome_transportadora, valor_volume_transp, filial) VALUES (:nome, :valor, :filial)");
        $sql->bindValue(':nome', $nome);
        $sql->bindValue(':valor', $valor);
        $sql->bindValue(':filial', $filial);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Transportadora Cadastrada!';
        $_SESSION['icon']='success';
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Cadastrar Transportadora!';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: descargas.php");
exit();
?>