<?php

session_start();
require("../conexao.php");

$id = filter_input(INPUT_GET, 'id');

if(isset($id) && empty($id) == false ){ 
    
    $sql = $db->prepare("DELETE FROM transportadoras WHERE idtransportadoras = :id");
    $sql->bindValue(':id',$id);
    
    if($sql->execute()){
        echo "<script>alert('Excluído com Sucesso!');</script>";
        echo "<script>window.location.href='transportadoras.php'</script>";
    }else{
        print_r($sql->errorInfo());
    }
    
}else{
    header("Location:solicitacoes.php");
}

?>