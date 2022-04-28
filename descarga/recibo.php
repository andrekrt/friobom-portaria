<?php

session_start();
use Mpdf\Mpdf;
require_once __DIR__ . '/../vendor/autoload.php';
require("../conexao.php");
$tipousuario = $_SESSION['tipousuario'];

if($tipousuario==2 || $tipousuario==99){
    $token = filter_input(INPUT_GET, 'token');
    $dataAtual = date('d/m/Y');

    $sql = $db->prepare("SELECT * FROM descarga LEFT JOIN fornecedores ON descarga.fornecedor=fornecedores.idfornecedores LEFT JOIN transportadoras ON descarga.transportadora = transportadoras.idtransportadoras WHERE token = :token");
    $sql->bindValue(':token', $token);
    $sql->execute();
    $dados = $sql->fetchAll();
    $motorista = $dados[0]['nome_motorista'];
    $contato = $dados[0]['contato_motorista'];
    $rg = $dados[0]['rg_motorista'];
    $placa = $dados[0]['placa'];
    $fornecedor = $dados[0]['nome_fornecedor'];
    $transportadora = $dados[0]['nome_transportadora'];
    $frete = $dados[0]['tipo_frete'];
    $token = $dados[0]['token'];
    $linha = '';
    $valorTotal = 0;

    foreach($dados as $dado): 
        $valorVol = $dado['valor_descarga']/$dado['qtd_volume'];
        switch ($dado['tipo_volume']){
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
        $volume = number_format($dado['qtd_volume'],2, ",", ".") . $tipoVolume;
        $valorVol = "R$ ". number_format($valorVol, 3, ",",".");
        $valorDesc ="R$ ". number_format($dado['valor_descarga'],2,",",".");
        $valorTotal = $valorTotal +  $dado['valor_descarga'];
        $linha .= "<tr>
        <td>$dado[num_nf]</td>
        <td> $volume  </td>
        <td>$valorVol</td>
        <td>$valorDesc</td>
    </tr>";
    endforeach; 
    $valorTotal = number_format($valorTotal, 2,",",".");
    $mpdf = new Mpdf();
    $mpdf->AddPage();
    $mpdf->WriteHTML("
    <!DOCTYPE html>
<html lang='pt-bt'>
<head>
    
</head>
<body >
    <div style='display: flex; flex-direction: column; '>        
        <div style='width: 100%;'>
            <div style='width: 40%; float: left; '> <img style='width: 300px;' src='../assets/images/logo.png'> </div>
            <div style='text-align: right; float: right; width: 60%;'> 
                <p> Basto Mesquita Dist. e Logistica LTDA <br> Rod. BR 316, KM 357, PQ Rui Barbosa, S/N <br>65700-000, Bacabal - MA </p>    
            </div>
        </div>
        <div style='width: 100%; border: 1px solid #000; border-radius: 15px;'>
            <p> 
                Data de Emissão: $dataAtual <br>
                Executado para $motorista <span style='float: right;'>Fone: $contato</span> <br>
                Documento: $rg <span style='float: right;'> Placa: $placa </span> <br>
                Fornecedor: $fornecedor <br>
                Transportadora: $transportadora <span style='float: right;'> Tipo de Frete: $frete </span> <br>
                Descarga: N° $token <br> 
                Prestação de Serviços de Descaga de Mercadoria
            </p>
        </div>
        <table border='1' style='width: 100%; margin-top:10px'>
            <tr>
                <th>NF</th>
                <th>QTD VOL</th>
                <th>VALOR POR VOL</th>
                <th>VALOR DESCARGA</th>
            </tr>
            $linha
        </table>
        <div style='width: 100%; border: 1px solid #000; border-radius: 15px; margin-top:10px'>
            <p>Recebi no dia $dataAtual de $motorista <br> RG $rg o valor de R$ $valorTotal reais referente a prestação de serviço de desacarga de mercadorias.</p>
        </div>
        <div style='width: 100%; text-align:center; margin-top:10px; height:50px'>
            <div style='width: 15%; float: left; border: 1px solid #000; border-radius:15px; height:75px'>
                <p style='margin-top:0; font-size:10px'>Recibo de <br>Prestação de Serviços <br> Nº $token </p> 
            </div>
            <div style='width: 84%; float: right; border: 1px solid #000; border-radius: 15px; display: flex; flex-direction: row;'>
                <div style='float:left; width: 30%; border-right: 1px solid #000; font-size:13px;  height:75px'>
                    Data do Recebimento <br>
                    $dataAtual
                </div>
                <div style='float:right; text-align: center'>
                    Identificação e Recebimento <br>
                    <img style='margin:0; height:50px' src='../assets/images/assinaturas/assinatura.png' >
                </div>
            </div>
        </div>
    </div>
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
<html lang='pt-bt'>
<head>
    
</head>
<body>
    <div style='display: flex; flex-direction: column;'>        
        <div style='width: 100%;'>
            <div style='width: 40%; float: left; '> <img style='width: 300px;' src='../assets/images/logo.png'> </div>
            <div style='text-align: right; float: right; width: 60%;'> 
                <p> Basto Mesquita Dist. e Logistica LTDA <br> Rod. BR 316, KM 357, PQ Rui Barbosa, S/N <br>65700-000, Bacabal - MA </p>    
            </div>
        </div>
        <div style='width: 100%; border: 1px solid #000; border-radius: 15px;'>
            <p> 
                Data de Emissão: $dataAtual <br>
                Executado para $motorista <span style='float: right;'>Fone: $contato</span> <br>
                Documento: $rg <span style='float: right;'> Placa: $placa </span> <br>
                Fornecedor: $fornecedor <br>
                Transportadora: $transportadora <span style='float: right;'> Tipo de Frete: $frete </span> <br>
                Descarga: N° $token <br> 
                Prestação de Serviços de Descaga de Mercadoria
            </p>
        </div>
        <table border='1' style='width: 100%; margin-top:10px'>
            <tr>
                <th>NF</th>
                <th>QTD VOL</th>
                <th>VALOR POR VOL</th>
                <th>VALOR DESCARGA</th>
            </tr>
            $linha
        </table>
        <div style='width: 100%; border: 1px solid #000; border-radius: 15px;'>
            <p>Recebi no dia $dataAtual de $motorista <br> RG $rg o valor de $valorTotal reais referente a prestaçaõ de serviço de desacarga de mercadorias.</p>
        </div>
        <div style='width: 100%;'>
            <div style='width: 40%; float: left; '>
                <p style="text-align: center;">Recibo de <br>Prestação de Serviços</p>
                <p>Nº $token</p>
            </div>
            <div style='width: 59%; float: right; border: 1px solid #000; border-radius: 15px; margin-top:10px; display: flex; flex-direction: row;'>
                <div style='float:left; width: 20%; border-right: 1px solid #000'>
                    Data do Recebimento <br>
                    $dataAtual
                </div>
                <div style='float:right; text-align: center; width: 80%;'>
                    Identificação e Recebimento <br>
                    <img src='../assets/images/assinaturas/assinatura.png' >
                </div>
            </div>
        </div>
    </div>
</body>
</html>