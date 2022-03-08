<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario']==99 || $_SESSION['tipousuario']==3)){

    $id = filter_input(INPUT_POST, 'idTransp');
    $transportadora = filter_input(INPUT_POST, 'transportadora');
    $valorVolume = str_replace(",",".",filter_input(INPUT_POST, 'valorVolume')) ;

    echo "$id<br>$transportadora<br>$valorVolume";

    $atualiza = $db->prepare("UPDATE transportadoras SET nome_transportadora = :transportadora, valor_volume_transp = :valorVolume WHERE idtransportadoras = :id");
    $atualiza->bindValue(':transportadora', $transportadora);
    $atualiza->bindValue(':valorVolume', $valorVolume);
    $atualiza->bindValue(':id', $id);

    if($atualiza->execute()){
        echo "<script> alert('Atualizado com Sucesso!')</script>";
        echo "<script> window.location.href='transportadoras.php' </script>";
    }else{
        print_r($atualiza->errorInfo());
    }

}else{
    echo "<script> alert('Acesso não permitido!')</script>";
    echo "<script> window.location.href='colaboradores.php' </script>";
}

?>