<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario'] ==3 || $_SESSION['tipousuario']==99)){

    $iddescarga = filter_input(INPUT_POST, 'iddescarga');
    $token = filter_input(INPUT_POST, 'token');
    $cadastro = filter_input(INPUT_POST, 'cadastro')?1:0;
    $semPedido = filter_input(INPUT_POST, 'noPedido')?1:0;
    $preco = filter_input(INPUT_POST, 'preco')?1:0;
    $qtd = filter_input(INPUT_POST, 'qtd')?1:0;
    $semProduto = filter_input(INPUT_POST, 'noProduto')?1:0;
    $obs = filter_input(INPUT_POST, 'obs');
    $situacao = "Pendente";
    $data = date('Y-m-d H:i');
    $anexos = $_FILES['anexo'];

    $inserir = $db->prepare("INSERT INTO pendencias (token_descarga, data_hora, cadastro, sem_pedido, preco_divergente, qtd_divergente, produto_inexistente, obs, situacao_pendencia) VALUES (:iddescarga, :dataHora, :cadastro, :pedido, :preco, :qtd, :produto, :obs, :situacao)");
    $inserir->bindValue(':iddescarga', $iddescarga);
    $inserir->bindValue(':dataHora', $data);
    $inserir->bindValue(':cadastro', $cadastro);
    $inserir->bindValue(':pedido', $semPedido);
    $inserir->bindValue(':preco', $preco);
    $inserir->bindValue(':qtd', $qtd);
    $inserir->bindValue(':produto', $semProduto);
    $inserir->bindValue(':obs', $obs);
    $inserir->bindValue(':situacao', $situacao);

    if($inserir->execute()){
        $atualizaSituacao = $db->prepare("UPDATE descarga SET situacao = :situacao, pendencia = :pendencia, data_hora_pendencia = :tempoPendencia  WHERE token = :token");
        $atualizaSituacao->bindValue(':situacao', $situacao);
        $atualizaSituacao->bindValue(':pendencia', "Aguardando Resolução");
        $atualizaSituacao->bindValue(':tempoPendencia', $data);
        $atualizaSituacao->bindValue(':token', $token);
        if($atualizaSituacao->execute()){
            if(!empty($anexos['name'][0])){
                $diretorio = "pendencias/".$token;
                mkdir($diretorio, 0755);
                for($i=0;$i<count($anexos['name']);$i++){
                    $destino = $diretorio."/".$anexos['name'][$i];
                    move_uploaded_file($anexos['tmp_name'][$i], $destino);
                }
            }
            echo "<script>alert('Pendência Registrada!');</script>";
            echo "<script>window.location.href='descargas.php'</script>";
        }else{
            $atualizaSituacao->errorInfo();
        }
        
    }else{
        print_r($inserir->errorInfo());
    }

}else{

}

?>