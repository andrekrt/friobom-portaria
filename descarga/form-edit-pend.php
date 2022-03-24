<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario'] ==1 || $_SESSION['tipousuario']==99 || $_SESSION['tipousuario'] == 6 || $_SESSION['tipousuario'] == 4)){

    $tipoUsuario = $_SESSION['tipousuario'];

    $idpendencia = filter_input(INPUT_GET, 'idPend');
    $tokenDescarga = filter_input(INPUT_GET, 'token');
    $pendencia = $db->prepare("SELECT * FROM pendencias LEFT JOIN descarga ON descarga.iddescarga = pendencias.token_descarga LEFT JOIN fornecedores ON descarga.fornecedor = fornecedores.idfornecedores WHERE id = :id");
    $pendencia->bindValue(':id', $idpendencia);
    $pendencia->execute();
    $dado = $pendencia->fetch();
    
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
                            <img src="../assets/images/icones/icon-descarga.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Pendência</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="atualiza-pendencia.php" method="post" >
                        <div id="formulario">
                            <input type="hidden" name="idDesc" id="idDesc" value="<?=$dado['iddescarga']?>">
                            <div class="form-row">
                                <input type="hidden" value="<?=$dado['id']?>" name="id">
                                <div class="form-group col-md-2 espaco ">
                                    <label for="token"> Cód Descarga</label>
                                    <input type="text" readonly required name="token" class="form-control" id="token" value="<?=$dado['token']?>">
                                </div>
                                <div class="form-group col-md-2 espaco ">
                                    <label for="cadastro"> Produto sem Cadastro</label>
                                    <input type="text" readonly name="cadastro" class="form-control" id="cadastro" value="<?=$dado['cadastro']?"SIM":"NÃO"?>">
                                </div>
                                <div class="form-group col-md-2 espaco ">
                                    <label for="pedido"> Pedido não Encontrado</label>
                                    <input type="text" readonly name="pedido" class="form-control" id="pedido" value="<?=$dado['sem_pedido']?"SIM":"NÃO"?>">
                                </div>
                                <div class="form-group col-md-2 espaco">
                                    <label for="preco"> Preço Divergente</label>
                                    <input type="text" readonly name="preco" class="form-control" id="preco" value="<?=$dado['preco_divergente']?"SIM":"NÃO"?>">
                                </div>
                                <div class="form-group col-md-2 espaco ">
                                    <label for="qtd"> Qtd Divergente</label>
                                    <input type="text" readonly name="qtd" class="form-control" id="qtd" value="<?=$dado['qtd_divergente']?"SIM":"NÃO"?>">
                                </div>
                                <div class="form-group col-md-2 espaco ">
                                    <label for="produto"> Produto Inexistente</label>
                                    <input type="text" readonly name="produto" class="form-control" id="produto" value="<?=$dado['qtd_divergente']?"SIM":"NÃO"?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 espaco ">
                                    <label for="fornecedor">Fornecedor</label>
                                    <input type="text" readonly name="fornecedor" class="form-control" id="fornecedor" value="<?=$dado['nome_fornecedor']?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12 espaco ">
                                    <label for="obs"> Obs. Pendência</label>
                                    <textarea readonly name="obs" id="obs" class="form-control" rows="3"> <?=$dado['obs']?> </textarea>
                                </div> 
                            </div>
                            <?php 
                            $sql = $db->prepare("SELECT * FROM descarga  LEFT JOIN fornecedores ON descarga.fornecedor = fornecedores.idfornecedores LEFT JOIN transportadoras ON descarga.transportadora = transportadoras.idtransportadoras WHERE token = :token");
                            $sql->bindValue(':token', $tokenDescarga);
                            $sql->execute();
                            
                            $nfs = $sql->fetchAll();
                            foreach($nfs as $nf):
                            
                          
                            ?>
                            <div class="form-row">
                                <input type="hidden" class="form-control" name="idDesc[]" value="<?=$nf['iddescarga']?>">
                                <div class="form-group col-md-2 espaco">
                                    <label for="numNf">Nº NF</label>
                                    <input type="text" readonly class="form-control" name="numNf[]" id="numNf" value="<?=$nf['num_nf']?>">
                                </div>
                                <div class="form-group col-md-2 espaco">
                                    <label for="qtdVolume">Qtd de Volume</label>
                                    <input type="text" readonly class="form-control" name="qtdVolume[]" id="qtdVolume" value="<?=$nf['qtd_volume']?>">
                                </div>
                                
                            </div>      
                            <?php endforeach; ?> 
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-5 espaco">
                                <label for="situacao">Tipo de Solução</label> <br>
                                <input type="radio" required name="situacao" id="aceito" value="Aceito" <?=($dado['situacao_pendencia'] == "Aceito") ? "checked" : null; ?>> <label for="aceito"> Aceito </label> <br>
                                <input type="radio" required name="situacao" id="devTotal" value="Devolução Total" <?=($dado['situacao_pendencia'] == "Devolução Total") ? "checked" : null; ?>> <label for="devTotal"> Devolução Total </label> <br>
                                <input type="radio" required name="situacao" id="devParcial" value="Devolução Parcial" <?=($dado['situacao_pendencia'] == "Devolução Parcial") ? "checked" : null; ?>> <label for="devParcial"> Devolução Parcial (Descreva quais NF's, itens e Qtd.) </label>
                            </div>
                            <div class="form-group col-md-6  espaco">
                                <label for="obs"> Obs. de Solução</label>
                                <textarea name="obs" id="obs" class="form-control" rows="3"></textarea>
                            </div>  
                        </div>
                        <?php if($tipoUsuario==6):?>  
                        <button type="submit" class="btn btn-primary"> Registrar </button>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <script src="../assets/js/jquery.mask.js"></script>
    </body>
</html>