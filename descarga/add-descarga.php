<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario'] ==1 || $_SESSION['tipousuario']==99)){


    $token = filter_input(INPUT_POST, 'token');
    $cadastro = filter_input(INPUT_POST, 'cadastro')?1:0;

    $consultaToken = $db->query("SELECT MAX(token) as token FROM descarga");
    $token = $consultaToken->fetch();
    if(empty($token['token'])){
        $newToken = 0+1;
    }else{
        $newToken = $token['token']+1;
    }

    $usuario = $_SESSION['idusuario'];
    $filial = $_SESSION['filial'];
    $tempoChegada = filter_input(INPUT_POST, 'tempoChegada');
    $tempoRegistro = date('Y-m-d H:i');
    $fornecedor = filter_input(INPUT_POST, 'fornecedor');
    $tipoFrete = filter_input(INPUT_POST, 'tipoFrete');
    $transportadora = filter_input(INPUT_POST,'transportadora');
    $motorista = filter_input(INPUT_POST, 'motorista');
    $rgMotorista = filter_input(INPUT_POST, 'rgMotorista');
    $contatoMotorista = str_replace(",",".", filter_input(INPUT_POST, 'contatoMotorista'));
    $placa = filter_input(INPUT_POST, 'placa');

    $chaveNF = $_POST['chaveNf'];
    $qtdVol = str_replace(".","", $_POST['qtdVol']) ;
    $qtdVol = str_replace(",",".", $qtdVol);  

    if($transportadora==1){
        $select = $db->prepare("SELECT * FROM fornecedores WHERE idfornecedores = :fornecedor");
        $select->bindValue(':fornecedor', $fornecedor);
        $select->execute();
        $fornecedores = $select->fetch();

        $valorVol = $fornecedores['valor_volume'];
        $formaPagamento = "À Vista";

        //echo "1";
        
    }else{
        $select = $db->prepare("SELECT * FROM transportadoras WHERE idtransportadoras = :idtransportadora");
        $select->bindValue(':idtransportadora', $transportadora);
        $select->execute();
        $transportadoras = $select->fetch();

        $valorVol = $transportadoras['valor_volume_transp'];
        $formaPagamento = "Desconto em Título";

        //echo "$valorVol";
    }

    $situacao = "Aguardando Validação";
    
    for($i=0; $i<count($chaveNF); $i++){
        //verificar se já existe a nf registrada
        $verificaNf = $db->prepare("SELECT * FROM descarga WHERE chave_nf = :chave AND excluido=0");
        $verificaNf->bindValue(':chave', $chaveNF[$i]);
        $verificaNf->execute();
        if($verificaNf->rowCount()>0){
            $_SESSION['msg'] = 'Já existe registro dessa NF!';
            $_SESSION['icon']='warning';
            header("Location: form-descarga.php");
            exit();
        }else{
            
            $valorDescarga = $qtdVol[$i]*$valorVol;

            if($valorDescarga==0 || $formaPagamento == "Desconto em Título"){
                $pago = 1;
            }else{
                $pago = 0;
            }

            $numNF = ltrim(substr($chaveNF[$i], 25, 9), '0') ;

            $sql = $db->prepare("INSERT INTO descarga (token, data_hora_chegada, data_hora_registro, fornecedor, transportadora, tipo_frete, nome_motorista, rg_motorista, contato_motorista, placa, chave_nf, num_nf, qtd_volume, valor_descarga, forma_pagamento, situacao, pago, usuario_registro, filial) VALUES (:token, :tempoChegada, :tempoRegistro, :fornecedor, :transportadora, :frete, :motorista, :rgMotorista, :contatoMotorista, :placa, :chaveNf, :numNf, :qtdVol, :valorDescarga, :pagamento, :situacao, :pago, :usuario, :filial)");
            $sql->bindValue(':token', $newToken);
            $sql->bindValue(':tempoChegada', $tempoChegada);
            $sql->bindValue(':tempoRegistro', $tempoRegistro);
            $sql->bindValue(':fornecedor', $fornecedor);
            $sql->bindValue(':transportadora', $transportadora);
            $sql->bindValue(':frete', $tipoFrete);
            $sql->bindValue(':motorista', $motorista);
            $sql->bindValue(':rgMotorista', $rgMotorista);
            $sql->bindValue(':contatoMotorista', $contatoMotorista);
            $sql->bindValue(':placa', $placa);
            $sql->bindValue(':chaveNf', $chaveNF[$i]);
            $sql->bindValue(':numNf', $numNF);
            $sql->bindValue(':qtdVol', $qtdVol[$i]);
            $sql->bindValue(':valorDescarga', $valorDescarga);
            $sql->bindValue(':pagamento', $formaPagamento);
            $sql->bindValue(':situacao', $situacao);
            $sql->bindValue(':pago', $pago);
            $sql->bindValue(':usuario', $usuario);
            $sql->bindValue(':filial', $filial);
            
            if($sql->execute()){
                $_SESSION['msg'] = 'Descarga Registrada!';
                $_SESSION['icon']='success';
            }else{
                $_SESSION['msg'] = 'Erro ao Registrar Descarga';
                $_SESSION['icon']='error';
            }

        }        

    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='warning';
}
header("Location: form-descarga.php");
exit();
?>