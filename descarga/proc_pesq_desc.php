<?php
include '../conexao.php';
session_start();
$tipousuario =$_SESSION['tipousuario'];

switch ($tipousuario) {
    case '5':
        $condicao = ' AND confirmacao_financeiro = "Não Confirmado"';
        break; 
    case '3':
        $condicao = ' AND confirmacao_financeiro = "Não Confirmado" OR situacao = "Aguardando Validação"';
        break; 
    case '1':
        $condicao = 'AND situacao = "Aguardando Validação"';
        break;
    case '4':
        $condicao = 'AND situacao <> "Descarga Finalizada"';
        break;
    default:
        $condicao='AND situacao LIKE "%%"';
        break;
}

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$filial = $_SESSION['filial'];
if($filial==99){
    $condicaoFilial = "";
}else{
    $condicaoFilial = "AND descarga.filial=$filial";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (departamento LIKE :departamento OR nome_fornecedor LIKE :nome_fornecedor OR nome_transportadora LIKE :nome_transportadora OR tipo_frete LIKE :tipo_frete OR nome_motorista LIKE :nome_motorista OR rg_motorista LIKE :rg_motorista OR contato_motorista LIKE :contato_motorista OR placa LIKE :placa OR num_nf LIKE :num_nf OR data_hora_chegada LIKE :data_hora_chegada OR situacao LIKE :situacao OR token LIKE :token) ";
    $searchArray = array( 
        'departamento'=>"%$searchValue%", 
        'nome_fornecedor'=>"%$searchValue%",
        'tipo_frete'=>"%$searchValue%",
        'nome_motorista'=>"%$searchValue%",
        'rg_motorista'=>"%$searchValue%",
        'placa'=>"%$searchValue%",
        'num_nf'=>"%$searchValue%",
        'data_hora_chegada'=>"%$searchValue%",
        'nome_transportadora'=>"%$searchValue%",
        'contato_motorista'=>"%$searchValue%",
        'situacao'=>"%$searchValue%",
        'token'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(DISTINCT(token)) AS allcount FROM descarga WHERE excluido=0 $condicaoFilial ". $condicao);
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(DISTINCT(token)) AS allcount FROM descarga LEFT JOIN fornecedores ON descarga.fornecedor = fornecedores.idfornecedores LEFT JOIN usuarios ON descarga.usuario_registro = usuarios.idusuarios LEFT JOIN transportadoras ON descarga.transportadora = transportadoras.idtransportadoras WHERE 1 AND excluido=0 $condicaoFilial " .$condicao .$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT *, COUNT(num_nf) as qtdNf, SUM(qtd_volume) as totalVolume, SUM(valor_descarga) totalDescarga, descarga.filial as filialDesc FROM descarga LEFT JOIN fornecedores ON descarga.fornecedor = fornecedores.idfornecedores LEFT JOIN usuarios ON descarga.usuario_registro = usuarios.idusuarios LEFT JOIN transportadoras ON descarga.transportadora = transportadoras.idtransportadoras WHERE 1 AND excluido=0 $condicaoFilial " .$condicao .$searchQuery." GROUP BY token ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $pendencia = '';
    $descarga = '';
    $finalizar = '';
    $recebido = '';
    $pagar = "";
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

    if(($tipousuario ==1 || $tipousuario == 99 || $tipousuario==10) && ($row['pago']==0)){
        $ficha = ' <a target="_blank" class="btn btn-sm btn-primary" href="ordem-pagamento.php?id='.$row['token'].'">Ficha</a> ';
        $editar=' <a href="form-edit-desc.php?idDesc='.$row['iddescarga'].'" data-id="'.$row['iddescarga'].'"  class="btn btn-info btn-sm editbtn" >Visualizar</a> ';
    }

    if(($tipousuario==2 || $tipousuario==99) && ($row['pago']==1)){
        $imprimir = ' <a target="_blank" class="btn btn-sm btn-success" href="recibo.php?token='.$row['token'].'">Recibo</a> ';
    }elseif(($tipousuario==2 || $tipousuario==99 || $tipousuario==5) && ($row['pago']==0)){
        $editar=' <a href="form-edit-desc.php?idDesc='.$row['iddescarga'].'" data-id="'.$row['iddescarga'].'"  class="btn btn-info btn-sm editbtn" >Visualizar</a> ';
        $pagar = ' <a onclick=\'confirmaPagamento(' . $row['token'] . ')\' id="pagar" data-id="'.$row['token'].'"  class="btn btn-success btn-sm " >Pagar</a> ';
    }

    if(($tipousuario==3 || $tipousuario==99) && ($row['situacao']=='Aguardando Validação')){
        $validar = ' <a onclick=\'confirmaValidar(' . $row['token'] . ')\' id="bonus" data-id="'.$row['token'].'"  class="btn btn-info btn-sm editbtn" >Validar</a> ';
        $pendencia = ' <a href="javascript:void();" id="pendencia" data-id="'.$row['iddescarga'].'"  class="btn btn-danger btn-sm editbtn" >Pendência</a> ';
    }elseif(($tipousuario==4 || $tipousuario==99 || $tipousuario==10) && ($row['situacao']=='Validada' && $row['pago']==1)){
        $descarga = ' <a href="javascript:void();" id="iniciar" data-id="'.$row['iddescarga'].'"  class="btn btn-info btn-sm editbtn" >Inicar Descarga</a> ';
        
    }elseif(($tipousuario==4 || $tipousuario==99 || $tipousuario==10) && ($row['situacao']=='Descarga Iniciada')){
        $finalizar = ' <a href="javascript:void();" id="finalizar" data-id="'.$row['iddescarga'].'"  class="btn btn-info btn-sm editbtn" >Finalizar</a> ';
    }

    if(($tipousuario==5 || $tipousuario==99) && ($row['pago']==1)){
        $recebido = ' <a href="javascript:void();" id="recebido" data-id="'.$row['iddescarga'].'"  class="btn btn-info btn-sm editbtn" >Recebido</a> ';
    }

    if(($tipousuario==4 || $tipousuario==99) && $row['situacao']!='Finalizada'){
        $excluir = ' <a href="javascript:void();" id="excluir" data-id="'.$row['iddescarga'].'"  class="btn btn-danger btn-sm editbtn" >Excluir</a> ';
    }

    if(is_dir('nfs/'.$row['token'])){
        $anexo = ' <a href="nfs/'.$row['token'].'">NFs</a> ';
    }else{
        $anexo='Sem NF';
    }

    if($row['pago']==1){
        $pago = "SIM";
    }else{
        $pago= "NÃO";
    }
    $data[] = array(
        "filial"=>$row['filialDesc'],
        "token"=>$row['token'],
        "iddescarga"=>$row['iddescarga'],
        "data"=>date("d/m/Y H:i", strtotime($row['data_hora_chegada'])),
        "departamento"=>strtoupper($row['departamento']),
        "bonus"=>$row['bonus'],
        "doca"=>$row['doca'],
        "nome_fornecedor"=>strtoupper($row['nome_fornecedor']),
        "tipo_frete"=>strtoupper($row['tipo_frete']),
        "transportadora"=>strtoupper($row['nome_transportadora']),
        "motorista"=>strtoupper($row['nome_motorista']),
        "rgMotorista"=>$row['rg_motorista'],
        "contatoMotorista"=>$row['contato_motorista'],
        "placa"=>strtoupper($row['placa']),
        "qtdNf"=>$row['qtdNf'],
        "qtdVol"=>str_replace(".", ",", $row['totalVolume']) .$tipoVolume,
        "valorVol"=>"R$". str_replace(".",",",$valorVolume) ,
        "valorTotalDescarga"=>"R$". number_format( $row['totalDescarga'],2,",",".") ,
        "forma_pagamento"=>$row['forma_pagamento'],
        "pago"=>$pago,
        "recebimento"=>$row['forma_recebimento'],
        "confirmacao"=>$row['confirmacao_financeiro'],
        "situacao"=>strtoupper($row['situacao']),
        "pendencia"=>strtoupper($row['pendencia']),
        "anexo_pendencia"=>$anexo,
        "problema"=>strtoupper($row['problema']),
      
        "acoes"=>$pagar . $editar . $ficha .  $imprimir . $validar . $finalizar . $pendencia . $descarga . $recebido .$excluir 
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
