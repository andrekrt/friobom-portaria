<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false) {
    $tipoUsuario = $_SESSION['tipousuario'];
    
} else {
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRIOBOM - PORTARIA</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <!-- arquivos para datatable -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.css"/>

</head>
<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/icon-descarga.png" alt="">
                </div>
                <div class="title">
                    <h2>Descargas</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="icon-exp">
                    <a href="descarga-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table id='tableDesc' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap" > Código Descarga </th>
                                <th scope="col" class="text-center text-nowrap">Qtd. NF </th>
                                <th scope="col" class="text-center text-nowrap">Data Chegada</th>
                                <th scope="col" class="text-center text-nowrap">Departamento</th>
                                <th scope="col" class="text-center text-nowrap">Bônus</th>
                                <th scope="col" class="text-center text-nowrap">Fornecedor</th>
                                <th scope="col" class="text-center text-nowrap">Tipo de Frete</th>
                                <th scope="col" class="text-center text-nowrap">Transportadora</th>
                                <th scope="col" class="text-center text-nowrap">Nome Motorista</th>
                                <th scope="col" class="text-center text-nowrap">RG Motorista </th>
                                <th scope="col" class="text-center text-nowrap">Contato Motorista</th>
                                <th scope="col" class="text-center text-nowrap">Placa</th>
                                <th scope="col" class="text-center text-nowrap"> Qtd(Kg/Cx)</th>
                                <th scope="col" class="text-center text-nowrap"> Valor por Qtd </th>
                                <th scope="col" class="text-center text-nowrap"> Valor Total Descarga </th>
                                <th scope="col" class="text-center text-nowrap">Forma de Pagamento </th>
                                <th scope="col" class="text-center text-nowrap">Status </th>
                                <th scope="col" class="text-center text-nowrap"> Registrado </th>
                                <th scope="col" class="text-center text-nowrap"> Ações</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('#tableDesc').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_desc.php'
                },
                'columns': [
                    { data: 'token'},
                    { data: 'qtdNf'},
                    { data: 'data'},
                    { data: 'departamento'},
                    { data: 'bonus'},
                    { data: 'nome_fornecedor'},
                    { data: 'tipo_frete'},
                    { data: 'transportadora'},
                    { data: 'motorista'},
                    { data: 'rgMotorista'},
                    { data: 'contatoMotorista'},
                    { data: 'placa'},
                    { data: 'qtdVol'},
                    { data: 'valorVol'},
                    { data: 'valorTotalDescarga'},
                    { data: 'forma_pagamento'},
                    { data: 'situacao'},
                    { data: 'usuario'},
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                order: [[0, 'desc']]
            });
        });

        $('#tableDesc').on('click', '.editbtn', function(event){
            

            $('#modalBonus').modal('show');

            var dado = $('.editbtn').data('id');
             $('#token').val(dado);

            
        });
    </script>


<div class="modal fade" id="modalBonus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Validar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="validar.php" method="post">
                    <input type="hidden" id="token" name="token">
                    <div class="form-row">
                        <div class="form-group col-md-12  ">
                            <label for="bonus"> Bônus</label>
                            <input type="text" required name="bonus" class="form-control" id="bonus">
                        </div>                                         
                    </div>    
            </div>
            <div class="modal-footer">
                <button type="submit" name="analisar" class="btn btn-primary">Validar</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>