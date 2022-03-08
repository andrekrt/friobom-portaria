<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario']==99 || $_SESSION['tipousuario']==3)) {
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
                    <img src="../assets/images/icones/icon-fornecedor.png" alt="">
                </div>
                <div class="title">
                    <h2>Fornecedores</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="table-responsive">
                    <table id='tableForn' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap" >Código </th>
                                <th scope="col" class="text-center text-nowrap" >Fornecedor </th>
                                <th scope="col" class="text-center text-nowrap">Departamento</th>
                                <th scope="col" class="text-center text-nowrap">Tipo de Volume</th>
                                <th scope="col" class="text-center text-nowrap"> Valor por Volume </th>
                                <th scope="col" class="text-center text-nowrap"> Usuário</th> 
                                <th scope="col" class="text-center text-nowrap"> Ações</th> 
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/menu.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('#tableForn').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_forn.php'
                },
                'columns': [
                    {data: 'idfornecedores'},
                    { data: 'nome_fornecedor'},
                    { data: 'departamento'},
                    { data: 'tipo_volume'},
                    { data: 'valor_volume'},
                    { data: 'usuario_registro'},
                    { data: 'acoes'},
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                }
            });
        });

        //abrir modal
        $('#tableForn').on('click', '.editbtn', function(event){
            var table = $('#tableForn').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_forn.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#idForn').val(json.idfornecedores);
                    $('#fornecedor').val(json.nome_fornecedor);
                    $('#departamento').val(json.departamento);
                    $('#tipoVolume').val(json.tipo_volume);
                    $('#valorVolume').val(json.valor_volume);
                }
            })
        });
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Fornecedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza-forn.php" method="post" >
                    <input type="hidden" name="id" id="idcolab" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-1">
                            <label for="idForn" class="col-form-label">Código</label>
                            <input type="text" readonly name="idForn" class="form-control" id="idForn" value="">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="fornecedor" readonly  class="col-form-label">Fornecedor</label>
                            <input type="text" required name="fornecedor" id="fornecedor" class="form-control" value=""> 
                        </div>
                        <div class="form-group col-md-2">
                            <label for="departamento" class="col-form-label">Departamento</label>
                            <select name="departamento" required id="departamento" required class="form-control">
                                <option value="FRIOS">FRIOS</option>
                                <option value="SECOS">SECOS</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="tipoVolume" class="col-form-label">Tipo de Volume</label>
                            <select name="tipoVolume" id="tipoVolume" required class="form-control">
                                <option value=""></option>
                                <option value="PESO BRUTO (KG)">PESO BRUTO (KG)</option>
                                <option value="CAIXA">CAIXA</option>
                                <option value="NÃO PAGA">NÃO PAGA</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2 ">
                            <label for="valorVolume"  class="col-form-label"> Valor por Volume</label>
                            <input type="text" required name="valorVolume" id="valorVolume" class="form-control">
                        </div>
                    </div>   
            </div>
            <div class="modal-footer">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                    </div>
                    <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </form> 
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/jquery.mask.js"></script>
<script>
    jQuery(function($){
        $('#valorVolume').mask("#.##0,000", {reverse: true});
    });
</script>
</body>
</html>