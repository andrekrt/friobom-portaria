<?php

session_start();
require("../conexao.php");

$token = filter_input(INPUT_POST, 'tokenExclui');
$motivo = filter_input(INPUT_POST, 'motivo');

// echo $token."<br>".$motivo;
 
$sql = $db->prepare("UPDATE descarga SET excluido=:exclusao, motivo_exclusao=:motivo WHERE token = :token");
$sql->bindValue(':token',$token);
$sql->bindValue(':motivo', $motivo);
$sql->bindValue(':exclusao', '1');

if($sql->execute()){
    echo "<script>alert('Excluído com Sucesso!');</script>";
    echo "<script>window.location.href='descargas.php'</script>";
}else{
    print_r($sql->errorInfo());
}
    

?>