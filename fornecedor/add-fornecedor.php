<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario']==99)){

    $usuario = $_SESSION['idusuario'];
    $nome = filter_input(INPUT_POST, 'nome');
    $departamento = filter_input(INPUT_POST, 'departamento');
    $tipoVol = filter_input(INPUT_POST, 'tipoVol') ;
    $valor = str_replace(",",".",filter_input(INPUT_POST, 'valor')) ;

    //echo "$nome<br>$departamento<br>$tipoVol<br>$valor<br>";

    $sql = $db->prepare("INSERT INTO fornecedores (nome_fornecedor, departamento, tipo_volume, valor_volume, usuario_registro) VALUES (:nome, :departamento, :tipo, :valor, :usuario)");
    $sql->bindValue(':nome', $nome);
    $sql->bindValue(':departamento', $departamento);
    $sql->bindValue(':tipo', $tipoVol);
    $sql->bindValue(':valor', $valor);
    $sql->bindValue(':usuario', $usuario);
    
    if($sql->execute()){
        echo "<script> alert('Fornecedor Cadastrado!')</script>";
        echo "<script> window.location.href='form-fornecedor.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }



}else{

}

?>