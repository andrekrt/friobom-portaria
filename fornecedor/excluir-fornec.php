<?php

session_start();
require("../conexao.php");

$id = filter_input(INPUT_GET, 'id');

if(isset($id) && empty($id) == false ){ 

    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE fornecedores SET ativo=:ativo WHERE idfornecedores = :id");
        $sql->bindValue(':ativo', 0);
        $sql->bindValue(':id',$id);
        $sql->execute();

        $db->commit();

        $_SESSION['msg'] = 'Fornecedor Excluído com Sucesso!';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Excluir Fornecedor';
        $_SESSION['icon']='error';
    }
    
}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: fornecedores.php");
exit();
?>