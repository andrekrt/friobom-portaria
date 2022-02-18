<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario'] ==1 || $_SESSION['tipousuario']==99 || $_SESSION['tipousuario'] == 2)){

    $tipoUsuario = $_SESSION['tipousuario'];

    $idDescarga = filter_input(INPUT_GET, 'idDesc');
    $descarga = $db->prepare("SELECT * FROM descarga LEFT JOIN fornecedores ON descarga.fornecedor = fornecedores.idfornecedores LEFT JOIN transportadoras ON descarga.transportadora = transportadoras.idtransportadoras WHERE iddescarga = :id");
    $descarga->bindValue(':id', $idDescarga);
    $descarga->execute();
    $dado = $descarga->fetch();
    
}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Friobom - Descarga</title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="../assets/favicon/site.webmanifest">
        <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    </head>
    <body>
        <div class="container-fluid corpo">
            <?php require('../menu-lateral.php') ?>
            <!-- Tela com os dados -->
            <div class="tela-principal">
                <div class="menu-superior">
                    <div class="icone-menu-superior">
                            <img src="../assets/images/icones/fornecedor.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Descarga</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="atualiza-descarga.php" method="post" >
                        <div id="formulario">
                            <input type="hidden" name="idDesc" id="idDesc" value="<?=$dado['iddescarga']?>">
                            <div class="form-row">
                                <div class="form-group col-md-1 espaco ">
                                    <label for="token"> Código</label>
                                    <input type="text" readonly required name="token" class="form-control" id="token" value="<?=$dado['token']?>">
                                </div>
                                <div class="form-group col-md-3 espaco ">
                                    <label for="tempoChegada"> Data/Hora de Chegada</label>
                                    <input type="datetime-local" required name="tempoChegada" class="form-control" id="tempoChegada" value="<?=date("Y-m-d\TH:i", strtotime($dado['data_hora_chegada']))?>">
                                </div>
                                <div class="form-group col-md-3 espaco ">
                                    <label for="fornecedor"> Fornecedor </label>
                                    <select required name="fornecedor" id="fornecedor" class="form-control">
                                        <option value="<?=$dado['fornecedor']?>"><?=$dado['nome_fornecedor']?></option>
                                    <?php
                                    $sql = $db->query("SELECT * FROM fornecedores");
                                    $fornecedores = $sql->fetchAll();
                                    foreach($fornecedores as $fornecedor):
                                    ?>
                                        <option value="<?=$fornecedor['idfornecedores']?>"><?=$fornecedor['nome_fornecedor']?></option>
                                    <?php
                                    endforeach;
                                    ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-2 espaco">
                                    <label for="departamento">Departamento</label>
                                    <input type="text" name="departamento" id="departamento" class="form-control" readonly value="<?=$dado['departamento']?>">
                                </div>
                                <div class="form-group col-md-3 espaco ">
                                    <label for="tipoFrete">Tipo de Frete </label>
                                    <select required name="tipoFrete" id="tipoFrete" class="form-control">
                                        <option value="<?=$dado['tipo_frete']?>"><?=$dado['tipo_frete']?></option>
                                        <option value="CIF (NÃO ESTAMOS NO REMETENTE)">CIF (NÃO ESTAMOS NO REMETENTE)</option>
                                        <option value="FOB (ESTAMOS NO REMETENTE)">FOB (ESTAMOS NO REMETENTE)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3 espaco ">
                                    <label for="transportadora">Transportadora</label>
                                    <select required name="transportadora" id="transportadora" class="form-control">
                                        <option value="<?=$dado['transportadora']?>"><?=$dado['nome_transportadora']?></option>
                                    <?php 
                                    $select = $db->query("SELECT * FROM transportadoras ");
                                    $transportadoras = $select->fetchAll();
                                    foreach($transportadoras as $transportadora):
                                    ?>  
                                        <option value="<?=$transportadora['idtransportadoras']?>"><?=$transportadora['nome_transportadora']?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3 espaco ">
                                    <label for="motorista"> Nome Motorista </label>
                                    <input type="text" required name="motorista" class="form-control" id="motorista" value="<?=$dado['nome_motorista']?>">
                                </div>
                                <div class="form-group col-md-2 espaco ">
                                    <label for="rgMotorista"> RG Motorisa </label>
                                    <input type="text" required name="rgMotorista" class="form-control" id="rgMotorista" value="<?=$dado['rg_motorista']?>">
                                </div>
                                <div class="form-group col-md-2 espaco ">
                                    <label for="contatoMotorista"> Contato Motorista </label>
                                    <input type="text" required name="contatoMotorista" class="form-control" id="contatoMotorista" value="<?=$dado['contato_motorista']?>">
                                </div>
                                <div class="form-group col-md-2 espaco ">
                                    <label for="placa"> Placa do Veículo </label>
                                    <input type="text" required name="placa" class="form-control" id="placa" value="<?=$dado['placa']?>">
                                </div>
                            </div>
                            <?php 
                            $totalDescarga = 0;
                            $sql = $db->prepare("SELECT * FROM descarga  LEFT JOIN fornecedores ON descarga.fornecedor = fornecedores.idfornecedores LEFT JOIN transportadoras ON descarga.transportadora = transportadoras.idtransportadoras WHERE token = :token");
                            $sql->bindValue(':token', $dado['token']);
                            $sql->execute();
                            
                            $nfs = $sql->fetchAll();
                            foreach($nfs as $nf):
                            
                            if($nf['transportadora'] == 1){
                                $valorVolume = $nf['valor_volume'];
                            }else{
                                $valorVolume = $nf['valor_volume_transp'];
                            }
                            ?>
                            <div class="form-row">
                                <input type="hidden" class="form-control" name="idDesc[]" value="<?=$nf['iddescarga']?>">
                                <div class="form-group col-md-2 espaco">
                                    <label for="numNf">Nº NF</label>
                                    <input type="text" readonly class="form-control" name="numNf[]" id="numNf" value="<?=$nf['num_nf']?>">
                                </div>
                                <div class="form-group col-md-2 espaco">
                                    <label for="qtdVolume">Qtd de Volume</label>
                                    <input type="text" class="form-control" name="qtdVolume[]" id="qtdVolume" value="<?=$nf['qtd_volume']?>">
                                </div>
                                <div class="form-group col-md-2 espaco">
                                    <label for="valorVol">Valor por Volume</label>
                                    <input type="text" readonly class="form-control" name="valorVol[]" id="valorVol" value="<?=$valorVolume?>">
                                </div>
                                <div class="form-group col-md-2 espaco">
                                    <label for="valorDescarga">Valor da Decarga</label>
                                    <input type="text" readonly class="form-control" name="valorDescarga[]" id="valorDescarga" value="<?=$nf['valor_descarga']?>">
                                </div>
                            </div>      
                            <?php
                            $totalDescarga = $totalDescarga+$nf['valor_descarga'];
                            endforeach;
                            ?>
                            <div class="form-row">
                                <div class="form-group col-md-2 espaco">
                                    <label for="totalDescarga">Total de Descarga</label>
                                    <input type="text" readonly class="form-control" name="totalDescarga" id="totalDescarga" value="<?=$totalDescarga?>">
                                </div>
                                <div class="form-group col-md-2 espaco">
                                    <label for="pagamento">Forma de Pagamento</label>
                                    <input type="text" readonly name="pagamento" id="pagamento" class="form-control" value="<?=$dado['forma_pagamento']?>">
                                </div>
                                <?php
                                if($tipoUsuario==2 || $tipoUsuario==99):
                                ?>
                                <div class="form-group col-md-3 espaco">
                                    <label for="situacao">Status</label>
                                    <select name="situacao" id="situacao" class="form-control">
                                        <option value="<?=$dado['situacao']?>"><?=$dado['situacao']?></option>
                                        <option value="Pago">Pago</option>
                                    </select>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"> Alterar </button>
                    </form>
                </div>
            </div>
        </div>

        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <script src="../assets/js/jquery.mask.js"></script>
        <script>
            $(document).ready(function(){
                var cont = 1;

                $('#add-nf').click(function(){
                    cont++;

                    $('#formulario').append('<div class="form-row"> <div class="form-group col-md-5 espaco "> <label for="chaveNf">Chave NF (Digitalisar)</label> <input type="text" required name="chaveNf[]" class="form-control" id="chaveNf" onkeydown="return(event.keyCode!=13);"> </div> <div class="form-group col-md-2 espaco "> <label for="qtdVol">Qtd de Volume</label> <input type="text" required name="qtdVol[]" class="form-control" id="qtdVol"> </div> </div>');
                });
            });
        </script>
        <script>
            $(document).ready(function(){
                $('#fornecedor').select2();
                $('#transportadora').select2();
            });
        </script>
        <script>
            jQuery(function($){
                $("#contatoMotorista").mask("(99) 9 9999-9999");
            });
        </script>
    </body>
</html>