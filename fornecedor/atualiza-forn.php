<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario']==99 || $_SESSION['tipousuario']==3)){

    $id = filter_input(INPUT_POST, 'idForn');
    $fornecedor = filter_input(INPUT_POST, 'fornecedor');
    $departamento = filter_input(INPUT_POST, 'departamento');
    $tipoVolume = filter_input(INPUT_POST, 'tipoVolume');
    $valorVolume = str_replace(",",".",filter_input(INPUT_POST, 'valorVolume')) ;

    echo "$id<br>$fornecedor<br>$departamento<br>$tipoVolume<br>$valorVolume";

    $atualiza = $db->prepare("UPDATE fornecedores SET nome_fornecedor = :fornecedor, departamento = :departamento, tipo_volume = :tipoVolume, valor_volume = :valorVolume WHERE idfornecedores = :id");
    $atualiza->bindValue(':fornecedor', $fornecedor);
    $atualiza->bindValue(':departamento', $departamento);
    $atualiza->bindValue(':tipoVolume', $tipoVolume);
    $atualiza->bindValue(':valorVolume', $valorVolume);
    $atualiza->bindValue(':id', $id);

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='fornecedores.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{
    echo "<script> alert('Acesso não permitido!')</script>";
    echo "<script> window.location.href='colaboradores.php' </script>";
}

?>