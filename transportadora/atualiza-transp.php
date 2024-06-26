<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario']==99 || $_SESSION['tipousuario']==3)){

    $id = filter_input(INPUT_POST, 'idTransp');
    $transportadora = filter_input(INPUT_POST, 'transportadora');
    $valorVolume = str_replace(",",".",filter_input(INPUT_POST, 'valorVolume')) ;

    $db->beginTransaction();

    try{
        $atualiza = $db->prepare("UPDATE transportadoras SET nome_transportadora = :transportadora, valor_volume_transp = :valorVolume WHERE idtransportadoras = :id");
        $atualiza->bindValue(':transportadora', $transportadora);
        $atualiza->bindValue(':valorVolume', $valorVolume);
        $atualiza->bindValue(':id', $id);
        $atualiza->execute();

        $db->commit();

        $_SESSION['msg'] = 'Transportadora Atualizada!';
        $_SESSION['icon']='success';
    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Atualizar Transportadora!';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}
header("Location: descargas.php");
exit();
?>