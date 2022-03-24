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
	$searchQuery = " AND (token_descarga LIKE :token_descarga OR nome_fornecedor LIKE :nome_fornecedor  ) ";
    $searchArray = array( 
        'token_descarga'=>"%$searchValue%", 
        'nome_fornecedor'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM pendencias ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM pendencias LEFT JOIN descarga ON descarga.iddescarga = pendencias.token_descarga LEFT JOIN fornecedores ON descarga.fornecedor = fornecedores.idfornecedores WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM pendencias LEFT JOIN descarga ON pendencias.token_descarga = descarga.iddescarga LEFT JOIN fornecedores ON descarga.fornecedor = fornecedores.idfornecedores WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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

    if(is_dir('pendencias/'.$row['token'])){
        $anexo = '<a target="_blank" href="pendencias/'.$row['token'].'">Anexos</a>';
    }else{
        $anexo='Sem Anexo';
    }
    
    $data[] = array(
        "id"=>$row['token'],
        "nome_fornecedor"=>strtoupper($row['nome_fornecedor']),
        "cadastro"=>$row['cadastro']?"SIM":"NÃO",
        "sem_pedido"=>$row['sem_pedido']?"SIM":"NÃO",
        "preco_divergente"=>$row['preco_divergente']?"SIM":"NÃO",
        "qtd_divergente"=>$row['qtd_divergente']?"SIM":"NÃO",
        "produto_inexistente"=>$row['produto_inexistente']?"SIM":"NÃO",
        "anexos"=>$anexo,
        "situacao"=>strtoupper($row['situacao_pendencia']),
        "acoes"=> '<a href="form-edit-pend.php?idPend='.$row['id'].'&&token='.$row['token'].'" data-id="'.$row['id'].'"  class="btn btn-info btn-sm editbtn" >Visualizar</a>'
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
