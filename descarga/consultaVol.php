<?php

include_once "../conexao.php";

$fornecedor = filter_input(INPUT_GET, 'codFornecedor');

if(!empty($fornecedor)){
    $limite =1;
    
    $resultado =$db->prepare("SELECT * FROM fornecedores WHERE idfornecedores = :fornecedor");
    $resultado->bindValue(':fornecedor', $fornecedor);
    $resultado->execute();


    $valores = array();

    if($resultado->rowCount() != 0){
        $fornecedor = $resultado->fetch(PDO::FETCH_ASSOC);
        $valores['tipo_volume'] = $fornecedor['tipo_volume'];
        
    }else{
        $valores['tipo_volume']="Não Encontrado";
    }

    echo json_encode($valores);
}

?>