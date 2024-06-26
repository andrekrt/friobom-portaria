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
    $filial = $_SESSION['filial'];
    $anexos = $_FILES['anexo'];

    $db->beginTransaction();

    try{
        $inserir = $db->prepare("INSERT INTO pendencias (token_descarga, data_hora, cadastro, sem_pedido, preco_divergente, qtd_divergente, produto_inexistente, obs, situacao_pendencia, filial) VALUES (:iddescarga, :dataHora, :cadastro, :pedido, :preco, :qtd, :produto, :obs, :situacao, :filial)");
        $inserir->bindValue(':iddescarga', $iddescarga);
        $inserir->bindValue(':dataHora', $data);
        $inserir->bindValue(':cadastro', $cadastro);
        $inserir->bindValue(':pedido', $semPedido);
        $inserir->bindValue(':preco', $preco);
        $inserir->bindValue(':qtd', $qtd);
        $inserir->bindValue(':produto', $semProduto);
        $inserir->bindValue(':obs', $obs);
        $inserir->bindValue(':situacao', $situacao);
        $inserir->bindValue(':filial', $filial);
        $inserir->execute();

        $atualizaSituacao = $db->prepare("UPDATE descarga SET situacao = :situacao, pendencia = :pendencia, data_hora_pendencia = :tempoPendencia  WHERE token = :token");
        $atualizaSituacao->bindValue(':situacao', $situacao);
        $atualizaSituacao->bindValue(':pendencia', "Aguardando Resolução");
        $atualizaSituacao->bindValue(':tempoPendencia', $data);
        $atualizaSituacao->bindValue(':token', $token);
        $atualizaSituacao->execute();

        if(!empty($anexos['name'][0])){
            $diretorio = "pendencias/".$token;
            mkdir($diretorio, 0755);
            for($i=0;$i<count($anexos['name']);$i++){
                $destino = $diretorio."/".$anexos['name'][$i];
                move_uploaded_file($anexos['tmp_name'][$i], $destino);
            }
        }

        $db->commit();
        $_SESSION['msg'] = 'Pendência Registrada!';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $_SESSION['msg'] = 'Erro ao Registrar Pendência';
        $_SESSION['icon']='error';
    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='warning';
}
header("Location: descargas.php");
exit();
?>