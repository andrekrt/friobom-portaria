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
                                <th scope="col" class="text-center text-nowrap">Doca</th>
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
                                <th scope="col" class="text-center text-nowrap">Pendência </th>
                                <th scope="col" class="text-center text-nowrap">Anexos Pendência </th>
                                <th scope="col" class="text-center text-nowrap">Problema na Descarga </th>
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
                    { data: 'doca'},
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
                    { data: 'pendencia'},
                    { data: 'anexo_pendencia'},
                    { data: 'problema'},
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                order: [[0, 'desc']]
            });
        });

        $('#tableDesc').on('click', '#iniciar', function(event){
            var table = $('#tableDesc').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');
            
            $('#modalIniciar').modal('show');

            $.ajax({
                url:"get_desc.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#tokenIniciar').val(json.token);
                    
                }
            })
            
        });

        $('#tableDesc').on('click', '#pendencia', function(event){
            
            var table = $('#tableDesc').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');
            
            $('#modalPendencia').modal('show');

            $.ajax({
                url:"get_desc.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#iddescarga').val(json.iddescarga);
                    $('#token').val(json.token);
                    
                }
            })
            
        });

        $('#tableDesc').on('click', '#finalizar', function(event){
            
            var table = $('#tableDesc').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');
            
            $('#modalFinalizar').modal('show');

            $.ajax({
                url:"get_desc.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#tokenDesc').val(json.token);
                    $('#idFinalizar').val(json.iddescarga);
                    
                }
            })
            
        });
    </script>

<!-- Modal finalizar descarga -->
<div class="modal fade" id="modalFinalizar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Finalizar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="finalizar-descarga.php" method="post" enctype="multipart/form-data" >
                    <input type="hidden" id="tokenDesc" name="tokenDesc">
                    <input type="hidden" id="idFinalizar" name="idFinalizar">
                    <p>Houve Problema na Descarga</p>
                    <input type="radio" name="problema" id="SIM" value="SIM"> <label for="SIM">SIM</label>
                    <input type="radio" name="problema" id="NÃO" value="NÃO"> <label for="NÃO">NÃO</label>   
            </div>
            <div class="modal-footer">
                <button type="submit" name="analisar" class="btn btn-primary">Finalizar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pendencia -->
<div class="modal fade" id="modalPendencia" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pendência</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="add-pendencia.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="iddescarga" name="iddescarga">
                    <input type="hidden" id="token" name="token">
                    <div class="form-row">
                        <div class="form-group form-check check-os">
                            <input type="checkbox" class="form-check-input" id="cadastro" name="cadastro">
                            <label class="form-check-label" for="cadastro">Cadastro</label>
                        </div>
                        <div class="form-group form-check check-os">
                            <input type="checkbox" class="form-check-input" id="noPedido" name="noPedido">
                            <label class="form-check-label" for="noPedido">Pedido Não Encontrado</label>
                        </div>
                    </div>  
                    <div class="form-row">
                        <div class="form-group form-check check-os">
                            <input type="checkbox" class="form-check-input" id="preco" name="preco">
                            <label class="form-check-label" for="preco">Preço Divergente</label>
                        </div>
                        <div class="form-group form-check check-os">
                            <input type="checkbox" class="form-check-input" id="qtd" name="qtd">
                            <label class="form-check-label" for="qtd">Qtd Divergente</label>
                        </div>
                        <div class="form-group  form-check check-os">
                            <input type="checkbox" class="form-check-input" id="noProduto" name="noProduto">
                            <label class="form-check-label" for="noProduto">Produto Inexistente</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12  ">
                            <label for="anexo"> Anexar Arquivos </label>
                            <input type="file" multiple name="anexo[]" class="form-control" id="anexo">
                        </div>                                         
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12  ">
                            <label for="obs"> Obs.</label>
                            <textarea name="obs" id="obs" class="form-control" rows="3"></textarea>
                        </div> 
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="analisar" class="btn btn-primary">Registrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal iniciar descarga -->
<div class="modal fade" id="modalIniciar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Inicia Descarga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="iniciar-descarga.php" method="post">
                    <input type="hidden" id="tokenIniciar" name="tokenIniciar">
                    <div class="form-row">
                        <div class="form-group col-md-6 ">
                            <label for="bonus"> Bônus</label>
                            <input type="text" required name="bonus" class="form-control" id="bonus">
                        </div> 
                        <div class="form-group col-md-6  ">
                            <label for="doca"> Doca </label>
                            <input type="text" required name="doca" class="form-control" id="doca">
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