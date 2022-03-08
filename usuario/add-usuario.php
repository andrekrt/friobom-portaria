<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario']==99)){

    $cpf = filter_input(INPUT_POST, 'cpf');
    $email = filter_input(INPUT_POST, 'email');
    $nome = filter_input(INPUT_POST, 'nome');
    $senha = password_hash(filter_input(INPUT_POST,'senha'), PASSWORD_DEFAULT) ;
    $tipoUsuario = filter_input(INPUT_POST, 'tipoUsuario');

    $sql = $db->prepare("INSERT INTO usuarios (cpf, email, nome_usuario, senha, tipo_usuario) VALUES (:cpf, :email, :nome, :senha, :tipo)");
    $sql->bindValue(':cpf', $cpf);
    $sql->bindValue(':email', $email);
    $sql->bindValue(':nome', $nome);
    $sql->bindValue(':senha', $senha);
    $sql->bindValue(':tipo', $tipoUsuario);
    
    if($sql->execute()){
        echo "<script> alert('Usuário Cadastrado!!!')</script>";
        echo "<script> window.location.href='form-usuario.php' </script>";
    }else{
        print_r($sql->errorInfo());
    }

}else{
    echo "<script> alert('Acesso não permitido')</script>";
    echo "<script> window.location.href='index.php' </script>";
}

?>