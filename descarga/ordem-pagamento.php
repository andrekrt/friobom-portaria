<?php 
session_start();
use Mpdf\Mpdf;
require_once __DIR__ . '/../vendor/autoload.php';
require("../conexao.php");
$tipousuario = $_SESSION['tipousuario'];

if($tipousuario==1 || $tipousuario==99){
    $token = filter_input(INPUT_GET, 'id');
    $sql = $db->prepare("SELECT *, SUM(valor_descarga) as totalDescarga FROM descarga LEFT JOIN fornecedores ON descarga.fornecedor = fornecedores.idfornecedores WHERE token = :token");
    $sql->bindValue(':token', $token);
    $sql->execute();
    $dados = $sql->fetchAll();
    $token = $dados[0]['token'];
    $motorista = $dados[0]['nome_motorista'];
    $rgMotorista = $dados[0]['rg_motorista'];
    $valorDescarga = 'R$'.number_format($dados[0]['totalDescarga'], 2, ',', '');
    $formaPagamento = $dados[0]['forma_pagamento'];
    $fornecedor = $dados[0]['nome_fornecedor'];
    $departamento = $dados[0]['departamento'];

    $mpdf = new Mpdf(['mode' => 'utf-8', 'format' => 'A4-P']);
    $mpdf->AddPage();
    $mpdf->WriteHTML("
    
    <!DOCTYPE html>
    <html lang='pt-br'>

    <body>
        <div style='width: 100%;'>
            <div style='width: 40%; float: left; '> <img style='width: 300px;' src='../assets/images/logo.png'> </div>
            <div style='text-align: right; float: right; width: 50%;'> 
                <p> Basto Mesquita Dist. e Logistica LTDA <br> Rod. BR 316, KM 357, PQ Rui Barbosa, S/N <br>65700-000, Bacabal - MA </p>    
            </div>
        </div>
        <table border='1' style='width: 100%;'>
            <tr>
                <td> <span style='font-weight:bold'> Cód. Descarga: </span> $token</td> 
                <td> <span style='font-weight:bold'> Depósito: </span> $departamento</td>
                <td colspan='2'> <span style='font-weight:bold'> Fornecedor: </span> $fornecedor </td>
            </tr>
            <tr>
                <td colspan='3'><span style='font-weight:bold'> Motorista: </span> $motorista</td>
                <td><span style='font-weight:bold'> RG: </span> $rgMotorista</td>
            </tr>
            <tr>
                <td colspan='2'><span style='font-weight:bold'> Valor Descarga: </span> $valorDescarga </td>
                <td colspan='2'><span style='font-weight:bold'>Forma de Pagamento: </span> $formaPagamento </td>
            </tr>
            
        </table>
        <p style='font-size:15px'> Dirija-se ao setor de Acerto de descarga, para efetuar o pagamento e receber seu recibo. </p>
    </body>
    </html>

    ");

    $mpdf->Output();


}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='descargas.php'</script>";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div style="width: 100%;">
        <div style="width: 40%; float: left; "> <img style="width: 300px;" src="../assets/images/logo.png" alt=""> </div>
        <div style="text-align: right; float: right; width: 50%;"> 
            <p> Basto Mesquita Dist. e Logistica LTDA <br> Rod. BR 316, KM 357, Pq Rui Barbosa, S/N <br>65700-000, Bacabal - MA </p>    
        </div>
    </div>
    <table border="1" style="width: 100%;">
        <tr>
           <td>Cód. Descarga</td> 
           <td>Nome Motorista</td>
           <td>RG Motorista</td>
           <td>Valor Descarga</td>
        </tr>
        <tr>
            <td><?=$dados[0]['token']?></td>
            <td><?=$dados[0]['nome_motorista']?></td>
            <td><?=$dados[0]['rg_motorista']?></td>
            <td><?="R$".number_format($dados[0]['totalDescarga'], 2, ",", "") ?></td>
        </tr>
    </table>
</body>
</html>