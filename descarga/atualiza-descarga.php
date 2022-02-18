<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false){

    $tempoChegada = filter_input(INPUT_POST, 'tempoChegada');
    $fornecedor = filter_input(INPUT_POST, 'fornecedor');
    $tipoFrete = filter_input(INPUT_POST, 'tipoFrete');
    $transportadora = filter_input(INPUT_POST,'transportadora');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $rgMotorista = filter_input(INPUT_POST, 'rgMotorista');
    $contatoMotorista = filter_input(INPUT_POST, 'contatoMotorista');
    $placa = filter_input(INPUT_POST, 'placa');

    $idDesc = $_POST['idDesc'];
    $qtdVol = str_replace(",",".", $_POST['qtdVolume']) ;  

    if($transportadora==1){
        $select = $db->prepare("SELECT * FROM fornecedores WHERE idfornecedores = :fornecedor");
        $select->bindValue(':fornecedor', $fornecedor);
        $select->execute();
        $fornecedores = $select->fetch();

        $valorVol = $fornecedores['valor_volume'];
        $formaPagamento = "À Vista";
        
    }else{
        $select = $db->prepare("SELECT * FROM transportadoras WHERE idtransportadoras = :idtransportadora");
        $select->bindValue(':idtransportadora', $transportadora);
        $select->execute();
        $transportadoras = $select->fetch();

        $valorVol = $transportadoras['valor_volume_transp'];
        $formaPagamento = "Desconto em Título";

    }

    $situacao = filter_input(INPUT_POST, 'situacao')?filter_input(INPUT_POST, 'situacao'):'Aguardando Pagamento';
    
    for($i=0; $i<count($idDesc); $i++){

        $valorDescarga = $qtdVol[$i]*$valorVol;

        $sql = $db->prepare("UPDATE descarga SET data_hora_chegada = :tempoChegada, fornecedor = :fornecedor, transportadora = :transportadora, tipo_frete = :frete, nome_motorista = :motorista, rg_motorista = :rgMotorista, contato_motorista = :contatoMotorista, placa = :placa, qtd_volume = :qtdVol, valor_descarga = :valorDescarga, forma_pagamento = :pagamento, situacao = :situacao WHERE iddescarga = :id");
        $sql->bindValue(':tempoChegada', $tempoChegada);
        $sql->bindValue(':fornecedor', $fornecedor);
        $sql->bindValue(':transportadora', $transportadora);
        $sql->bindValue(':frete', $tipoFrete);
        $sql->bindValue(':motorista', $motorista);
        $sql->bindValue(':rgMotorista', $rgMotorista);
        $sql->bindValue(':contatoMotorista', $contatoMotorista);
        $sql->bindValue(':placa', $placa);
        $sql->bindValue(':qtdVol', $qtdVol[$i]);
        $sql->bindValue(':valorDescarga', $valorDescarga);
        $sql->bindValue(':pagamento', $formaPagamento);
        $sql->bindValue(':situacao', $situacao);
        $sql->bindValue(':id', $idDesc[$i]);
        
        if($sql->execute()){
            echo "<script> alert('Descarga Atualizada!')</script>";
            echo "<script> window.location.href='descargas.php' </script>";
        }else{
            print_r($sql->errorInfo());
        }

    }

}else{
    echo "<script> alert('Acesso não permitido!')</script>";
    echo "<script> window.location.href='descargas.php' </script>";

}

?>