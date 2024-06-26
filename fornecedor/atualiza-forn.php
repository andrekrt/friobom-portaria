<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario']==99 || $_SESSION['tipousuario']==3)){

    $id = filter_input(INPUT_POST, 'idForn');
    $fornecedor = filter_input(INPUT_POST, 'fornecedor');
    $departamento = filter_input(INPUT_POST, 'departamento');
    $tipoVolume = filter_input(INPUT_POST, 'tipoVolume');
    $valorVolume = str_replace(",",".",filter_input(INPUT_POST, 'valorVolume')) ;

    $db->beginTransaction();

    try{
        
        $atualiza = $db->prepare("UPDATE fornecedores SET nome_fornecedor = :fornecedor, departamento = :departamento, tipo_volume = :tipoVolume, valor_volume = :valorVolume WHERE idfornecedores = :id");
        $atualiza->bindValue(':fornecedor', $fornecedor);
        $atualiza->bindValue(':departamento', $departamento);
        $atualiza->bindValue(':tipoVolume', $tipoVolume);
        $atualiza->bindValue(':valorVolume', $valorVolume);
        $atualiza->bindValue(':id', $id);
        $atualiza->execute();

        $db->commit();

        $_SESSION['msg'] = 'Fornecedor Atualizado!';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Atualizar Fornecedor';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: descargas.php");
exit();
?>