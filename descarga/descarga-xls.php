<?php

session_start();
require("../conexao.php");

$tipoUsuario = $_SESSION['tipousuario'];

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
                $html .= '<td class="text-center font-weight-bold"> ID </td>';
                $html .= '<td class="text-center font-weight-bold"> Token </td>';
                $html .= '<td class="text-center font-weight-bold"> Data e Hora de Chegada </td>';
                $html .= '<td class="text-center font-weight-bold"> Data e Hora de Registro </td>';
                $html .= '<td class="text-center font-weight-bold"> Fornecedor </td>';
                $html .= '<td class="text-center font-weight-bold"> Bônus </td>';
                $html .= '<td class="text-center font-weight-bold"> Doca </td>';
                $html .= '<td class="text-center font-weight-bold"> Transportadora </td>';
                $html .= '<td class="text-center font-weight-bold"> Tipo de Frete </td>';
                $html .= '<td class="text-center font-weight-bold"> Nome Motorista </td>';
                $html .= '<td class="text-center font-weight-bold"> RG Motorista </td>';
                $html .= '<td class="text-center font-weight-bold"> Contato Motorista </td>';
                $html .= '<td class="text-center font-weight-bold"> Placa </td>';
                $html .= '<td class="text-center font-weight-bold"> Chave NF </td>';
                $html .= '<td class="text-center font-weight-bold"> Num NF </td>';
                $html .= '<td class="text-center font-weight-bold"> Qtd de Volume</td>';
                $html .= '<td class="text-center font-weight-bold"> Valor Descarga </td>';
                $html .= '<td class="text-center font-weight-bold"> Forma de Pagamento</td>';
                $html .= '<td class="text-center font-weight-bold"> Situação </td>';
                $html .= '<td class="text-center font-weight-bold"> Pendência </td>';
                $html .= '<td class="text-center font-weight-bold"> Problema </td>';
                $html .= '<td class="text-center font-weight-bold"> Data de Pagamento </td>';
                $html .= '<td class="text-center font-weight-bold"> Data da Pendência </td>';
                $html .= '<td class="text-center font-weight-bold"> Data da Validação </td>';
                $html .= '<td class="text-center font-weight-bold"> Data do Início Descarga </td>';
                $html .= '<td class="text-center font-weight-bold"> Data do Fim Descarga </td>';
                $html .= '<td class="text-center font-weight-bold"> Data de Recebimento</td>';
                $html .= '</tr>';

                $sql = $db->query("SELECT * FROM descarga LEFT JOIN fornecedores ON descarga.fornecedor = fornecedores.idfornecedores LEFT JOIN transportadoras ON descarga.transportadora = transportadoras.idtransportadoras");
                $dados = $sql->fetchAll();
                foreach($dados as $dado){

                    $html .= '<tr>';
                    $html .= '<td>'.$dado['iddescarga']. '</td>';
                    $html .= '<td>'.$dado['token']. '</td>';
                    $html .= '<td>'. date("d/m/Y H:i", strtotime($dado['data_hora_chegada'])) . '</td>';
                    $html .= '<td>'.date("d/m/Y H:i", strtotime($dado['data_hora_registro'])) . '</td>';
                    $html .= '<td>'. $dado['nome_fornecedor']. '</td>';
                    $html .= '<td>'. $dado['bonus']. '</td>';
                    $html .= '<td>'. $dado['doca']. '</td>';
                    $html .= '<td>'.$dado['nome_transportadora']. '</td>';
                    $html .= '<td>'.$dado['tipo_frete'] . '</td>';
                    $html .= '<td>'.$dado['nome_motorista'] . '</td>';
                    $html .= '<td>'.$dado['rg_motorista']. '</td>';
                    $html .= '<td>'.$dado['contato_motorista']. '</td>';
                    $html .= '<td>'.$dado['placa']. '</td>';
                    $html .= '<td>'.$dado['chave_nf']. '</td>';
                    $html .= '<td>'.$dado['num_nf']. '</td>';
                    $html .= '<td>'.str_replace(".",",", $dado['qtd_volume'] ). '</td>';
                    $html .= '<td>'.str_replace(".",",",$dado['valor_descarga']) . '</td>';
                    $html .= '<td>'.$dado['forma_pagamento']. '</td>';
                    $html .= '<td>'.$dado['situacao']. '</td>';
                    $html .= '<td>'.$dado['pendencia']. '</td>';
                    $html .= '<td>'.$dado['problema']. '</td>';
                    $html .= '<td>'.$dado['data_hora_pago']. '</td>';
                    $html .= '<td>'.$dado['data_hora_pendencia']. '</td>';
                    $html .= '<td>'.$dado['data_hora_validacao']. '</td>';
                    $html .= '<td>'.$dado['data_hora_iniciodesc']. '</td>';
                    $html .= '<td>'.$dado['data_hora_fimdesc']. '</td>';
                    $html .= '<td>'.$dado['data_hora_recebido']. '</td>';
                    $html .= '</tr>';

                }

                $html .= '</table>';

                /*header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="'.$arquivo.'"');
                header('Cache-Control: max-age=0');
                header('Cache-Control: max-age=1');*/

                echo $html;

            }
        
        ?>
    </body>
</html>