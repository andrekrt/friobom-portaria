<?php
include '../conexao.php';

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
	$searchQuery = " AND (nome_fornecedor LIKE :nome_fornecedor OR departamento LIKE :departamento OR tipo_volume LIKE :tipo_volume) ";
    $searchArray = array(
        'nome_fornecedor'=>"%$searchValue%",
        'departamento'=>"%$searchValue%",
        'tipo_volume'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM fornecedores");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM fornecedores WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM fornecedores LEFT JOIN usuarios ON fornecedores.usuario_registro = usuarios.idusuarios  WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $data[] = array(
            "idfornecedores"=>$row['idfornecedores'],
            "nome_fornecedor"=>$row['nome_fornecedor'],
            "departamento"=>$row['departamento'],
            "tipo_volume"=>$row['tipo_volume'],
            "valor_volume"=>"R$".number_format($row['valor_volume'],3,",",".") ,
            "usuario_registro"=>$row['nome_usuario'],
            "acoes"=> '<a href="javascript:void();" data-id="'.$row['idfornecedores'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  <a href="excluir-fornec.php?id='.$row['idfornecedores'].' " data-id="'.$row['idfornecedores'].'"  class="btn btn-danger btn-sm deleteBtn" >Deletar</a>'
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
