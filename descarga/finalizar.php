<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario']==99 || $_SESSION['tipousuario']==4)){

    $tipoUsuario = $_SESSION['tipousuario'];

    $token = filter_input(INPUT_POST, 'token' );
    $problema = filter_input(INPUT_POST, 'problemaDescarga')?filter_input(INPUT_POST, 'problemaDescarga'):null;
    $obsProblema = filter_input(INPUT_POST, 'obsProblema')?filter_input(INPUT_POST, 'obsProblema'):null;
    $nfDev = $_FILES['nfDev'];

    //echo "$token<br>$problema<br>";
    //echo count($nfDev['name']);

    $sql = $db->prepare("UPDATE descarga SET situacao = :situacao, problema = :problemas, obs_problema = :obs WHERE token = :token");
    $sql->bindValue(':token', $token);
    $sql->bindValue(':situacao', "Descarga Finaliza");
    $sql->bindValue(':problemas', $problema);
    $sql->bindValue(':obs', $obsProblema);

    if($sql->execute()){
        if(!empty($nfDev)){
            $diretorioPrincipal = "nfs/".$token;
            mkdir($diretorioPrincipal,0755);
            for($i=0;$i<count($nfDev['name']);$i++){
                $destinoDocumentos = $diretorioPrincipal."/". $nfDev['name'][$i];
                move_uploaded_file($nfDev['tmp_name'][$i],$destinoDocumentos);
            }
        }
        echo "<script>alert('Descarga Finalizada!');</script>";
        echo "<script>window.location.href='descargas.php'</script>";

    }else{
        print_r($sql->errorInfo());
    }



}else{

    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";

}

?>