<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario']==99 || $_SESSION['tipousuario']==3)){
    $filial = $_SESSION['filial'];
    $usuario = $_SESSION['idusuario'];
    $nome = filter_input(INPUT_POST, 'nome');
    $departamento = filter_input(INPUT_POST, 'departamento');
    $tipoVol = filter_input(INPUT_POST, 'tipoVol') ;
    $valor = str_replace(",",".",filter_input(INPUT_POST, 'valor')) ;

    $db->beginTransaction();

    try{
        $sql = $db->prepare("INSERT INTO fornecedores (nome_fornecedor, departamento, tipo_volume, valor_volume, usuario_registro, filial) VALUES (:nome, :departamento, :tipo, :valor, :usuario, :filial)");
        $sql->bindValue(':nome', $nome);
        $sql->bindValue(':departamento', $departamento);
        $sql->bindValue(':tipo', $tipoVol);
        $sql->bindValue(':valor', $valor);
        $sql->bindValue(':usuario', $usuario);
        $sql->bindValue(':filial', $filial);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Fornecedor Cadastrado!';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Cadastrar Fornecedor!';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: descargas.php");
exit();
?>