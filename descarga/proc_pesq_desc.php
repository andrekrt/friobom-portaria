<?php
include '../conexao.php';
session_start();
$tipousuario =$_SESSION['tipousuario'];

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (departamento LIKE :departamento OR nome_fornecedor LIKE :nome_fornecedor OR tipo_frete LIKE :tipo_frete OR nome_motorista LIKE :nome_motorista OR rg_motorista LIKE :rg_motorista OR placa LIKE :placa OR num_nf LIKE :num_nf ) ";
    $searchArray = array( 
        'departamento'=>"%$searchValue%", 
        'nome_fornecedor'=>"%$searchValue%",
        'tipo_frete'=>"%$searchValue%",
        'nome_motorista'=>"%$searchValue%",
        'rg_motorista'=>"%$searchValue%",
        'placa'=>"%$searchValue%",
        'num_nf'=>"%$searchValue%",
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM descarga ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM descarga WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT *, COUNT(num_nf) as qtdNf, SUM(qtd_volume) as totalVolume, SUM(valor_descarga) totalDescarga FROM descarga LEFT JOIN fornecedores ON descarga.fornecedor = fornecedores.idfornecedores LEFT JOIN usuarios ON descarga.usuario_registro = usuarios.idusuarios LEFT JOIN transportadoras ON descarga.transportadora = transportadoras.idtransportadoras WHERE 1 ".$searchQuery." GROUP BY token ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

// Bind values
foreach($searchArray as $key=>$search){
    $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();

foreach($empRecords as $row){
    $ficha = '';
    $imprimir = '';
    $editar = '';
    $excluir = '';
    $validar = '';
    $finalizar = '';
    switch ($row['tipo_volume']){
        case "PESO BRUTO (KG)":
            $tipoVolume = "Kg";
            break;
        case "CAIXA":
            $tipoVolume = "Cx";
            break;
        case "NAO PAGA":
            $tipoVolume="";
            break;
    }

    if($row['transportadora'] == 1){
        $valorVolume = $row['valor_volume'];
    }else{
        $valorVolume = $row['valor_volume_transp'];
    }

    if(($tipousuario ==1 || $tipousuario == 99) && ($row['situacao']=='Aguardando Pagamento')){
        $ficha = '<a target="_blank" class="btn btn-sm btn-primary" href="ordem-pagamento.php?id='.$row['token'].'">Ficha</a>';
        $excluir = '<a href="excluir-desc.php?token='.$row['token'].'" class="btn btn-danger btn-sm deleteBtn" >Deletar</a>';
        $editar='<a href="form-edit-desc.php?idDesc='.$row['iddescarga'].'" data-id="'.$row['iddescarga'].'"  class="btn btn-info btn-sm editbtn" >Visualizar</a>';
        $imprimir = '';
    }

    if(($tipousuario==2 || $tipousuario==99) && ($row['situacao']=='Pago')){
        $imprimir = '<a target="_blank" class="btn btn-sm btn-success" href="recibo.php?token='.$row['token'].'">Recibo</a>';
    }elseif(($tipousuario==2 || $tipousuario==99) && ($row['situacao']=='Aguardando Pagamento')){
        $imprimir='';
        $editar='<a href="form-edit-desc.php?idDesc='.$row['iddescarga'].'" data-id="'.$row['iddescarga'].'"  class="btn btn-info btn-sm editbtn" >Visualizar</a>';
    }

    if(($tipousuario==3 || $tipousuario==99) && ($row['situacao']=='Pago')){
        $validar = '<a href="javascript:void();" data-id="'.$row['token'].'"  class="btn btn-info btn-sm editbtn" >Validar</a>';
    }elseif(($tipousuario==4 || $tipousuario==99) && ($row['situacao']=='Validada')){
        $finalizar = '<a href="finalizar.php?token='.$row['token'].'" data-id="'.$row['token'].'"  class="btn btn-info btn-sm editbtn" >Finalizar</a>';
    }

    /*<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalBonus" data-id="'.$row['token'].'"  > Abrir modal de demonstração </button>*/
    
    $data[] = array(
        "token"=>$row['token'],
        "iddescarga"=>$row['iddescarga'],
        "data"=>date("d/m/Y H:i", strtotime($row['data_hora_chegada'])),
        "departamento"=>$row['departamento'],
        "bonus"=>$row['bonus'],
        "nome_fornecedor"=>$row['nome_fornecedor'],
        "tipo_frete"=>$row['tipo_frete'],
        "transportadora"=>$row['nome_transportadora'],
        "motorista"=>$row['nome_motorista'],
        "rgMotorista"=>$row['rg_motorista'],
        "contatoMotorista"=>$row['contato_motorista'],
        "placa"=>$row['placa'],
        "qtdNf"=>$row['qtdNf'],
        "qtdVol"=>str_replace(".", ",", $row['totalVolume']) .$tipoVolume,
        "valorVol"=>"R$". str_replace(".",",",$valorVolume) ,
        "valorTotalDescarga"=>"R$". number_format( $row['totalDescarga'],2,",",".") ,
        "forma_pagamento"=>$row['forma_pagamento'],
        "situacao"=>$row['situacao'],
        "usuario"=>$row['nome_usuario'],
        "acoes"=> $editar . $ficha . $excluir . $imprimir . $validar . $finalizar
    );
}

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
