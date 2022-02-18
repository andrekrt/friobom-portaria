<?php

session_start();
require("../conexao.php");

$token = filter_input(INPUT_GET, 'token');
 
$sql = $db->prepare("DELETE FROM descarga WHERE token = :token");
$sql->bindValue(':token',$token);

if($sql->execute()){
    echo "<script>alert('Excluído com Sucesso!');</script>";
    echo "<script>window.location.href='descargas.php'</script>";
}else{
    print_r($sql->errorInfo());
}
    

?>