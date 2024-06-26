<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipousuario'];
$filial = $_SESSION['filial'];
if($filial===99){
    $condicao = " ";
}else{
    $condicao = "AND fornecedores.filial=$filial";
}

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Planilha</title>
    </head>
    <body>
        <?php
        
            if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false){

                $arquivo = 'descarga.xls';

                $html = '';
                $html .= '<table border="1">';
                $html .= '<tr>';
                $html .= '<td class="text-center font-weight-bold"> Código </td>';
                $html .= '<td class="text-center font-weight-bold"> Fornecedor </td>';
                $html .= '<td class="text-center font-weight-bold"> Departamento </td>';
                $html .= '<td class="text-center font-weight-bold"> Tipo de Volume </td>';
                $html .= '<td class="text-center font-weight-bold"> Valor por Volume </td>';
                $html .= '</tr>';

                $sql = $db->query("SELECT * FROM fornecedores WHERE 1 $condicao ");
                $dados = $sql->fetchAll();
                foreach($dados as $dado){

                    $html .= '<tr>';
                    $html .= '<td>'.$dado['idfornecedores']. '</td>';
                    $html .= '<td>'.$dado['nome_fornecedor']. '</td>';
                    $html .= '<td>'. $dado['departamento']. '</td>';
                    $html .= '<td>'. $dado['tipo_volume']. '</td>';
                    $html .= '<td>'. number_format($dado['valor_volume'],2,",",".") . '</td>';
                    $html .= '</tr>';

                }

                $html .= '</table>';

                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$arquivo.'"');
                header('Cache-Control: max-age=0');
                header('Cache-Control: max-age=1');

                echo $html;

            }
        
        ?>
    </body>
</html>