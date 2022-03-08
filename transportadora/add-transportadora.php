<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario']==99 || $_SESSION['tipousuario']==3)){

    $nome = filter_input(INPUT_POST, 'nome');
    $valor = str_replace(",",".",filter_input(INPUT_POST, 'valorVol')) ;

    //echo "$nome<br>$valor<br>";

    $sql = $db->prepare("INSERT INTO transportadoras (nome_transportadora, valor_volume_transp) VALUES (:nome, :valor)");
    $sql->bindValue(':nome', $nome);
    $sql->bindValue(':valor', $valor);
    
    if($sql->execute()){
        echo "<script> alert('Transportadora Cadastrada!')</script>";
        echo "<script> window.location.href='form-transportadora.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }


}else{

}

?>