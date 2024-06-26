<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false){
    $token = filter_input(INPUT_GET, 'token');
    $pago = 1;
    $data = date('Y-m-d H:i');

    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE descarga SET pago = :pago, data_hora_pago = :dataPagamento  WHERE token = :token" );
        $sql->bindValue(':pago', $situacao);
        $sql->bindValue(':dataPagamento', $data);
        $sql->bindValue(':token', $token);
        $sql->execute();

        $db->commit();
        $_SESSION['msg'] = 'Pagamento Efetuado!';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Efetuar Pagamento';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='warning';
}
header("Location: descargas.php");
exit();
?>