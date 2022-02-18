<?php

require('conexao.php');

$cpf = '04006292325';
$nome = 'André Santos';
$email = 'andrekrt1922@gmail.com';
$senha = password_hash('andre123', PASSWORD_DEFAULT);
$tipo = 1;

$sql = $db->prepare("INSERT INTO usuarios (cpf, email, nome_usuario, senha, tipo_usuario) VALUES (:cpf, :email, :nome, :senha, :tipoUsuario)");
$sql->bindValue(':cpf', $cpf);
$sql->bindValue(':nome', $nome);
$sql->bindValue(':email', $email);
$sql->bindValue(':senha', $senha);
$sql->bindValue(':tipoUsuario', $tipo);

if($sql->execute()){
    echo "cadastrado";
}else{
    print_r($sql->errorInfo());
}

?>