<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario']==99 || $_SESSION['tipousuario']==4 || $_SESSION['tipousuario']==10)){

    $tipoUsuario = $_SESSION['tipousuario'];

    $token = filter_input(INPUT_POST, 'token' );
    $problema = filter_input(INPUT_POST, 'problemaDescarga')?filter_input(INPUT_POST, 'problemaDescarga'):null;
    $obsProblema = filter_input(INPUT_POST, 'obsProblema')?filter_input(INPUT_POST, 'obsProblema'):null;
    $nfDev = $_FILES['nfDev'];
    $data = date('Y-m-d H:i');

    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE descarga SET situacao = :situacao, problema = :problemas, obs_problema = :obs, data_hora_fimdesc = :dataFimDesc WHERE token = :token");
        $sql->bindValue(':token', $token);
        $sql->bindValue(':situacao', "Descarga Finalizada");
        $sql->bindValue(':problemas', $problema);
        $sql->bindValue(':dataFimDesc', $data);
        $sql->bindValue(':obs', $obsProblema);
        $sql->execute();

        if(!empty($nfDev['name'][0])){
            $diretorioPrincipal = "nfs/".$token;
            mkdir($diretorioPrincipal,0755);
            for($i=0;$i<count($nfDev['name']);$i++){
                $destinoDocumentos = $diretorioPrincipal."/". $nfDev['name'][$i];
                move_uploaded_file($nfDev['tmp_name'][$i],$destinoDocumentos);
            }
        }

        $db->commit();
        $_SESSION['msg'] = 'Descarga Finalizada!';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Finalizar Descarga';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='warning';
}
header("Location: descargas.php");
exit();
?>