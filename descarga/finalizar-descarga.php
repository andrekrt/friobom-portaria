<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario']==99 || $_SESSION['tipousuario']==4)){

    $tipoUsuario = $_SESSION['tipousuario'];

    $token = filter_input(INPUT_POST, 'tokenDesc' );
    $problema = filter_input(INPUT_POST, 'problema');
    $idDescarga = filter_input(INPUT_POST, 'idFinalizar');
    $data = date('Y-m-d H:i');

    $pendencias = $db->prepare("SELECT * FROM pendencias WHERE token_descarga = :token AND situacao_pendencia = :situacao");
    $pendencias->bindValue(':token', $idDescarga);
    $pendencias->bindValue(':situacao', "Devolução Parcial");
    $pendencias->execute();
    
    $pendencias = $pendencias->rowCount();

    if($problema=="NÃO" && $pendencias==0){
        $atualiza = $db->prepare("UPDATE descarga SET situacao = :situacao, data_hora_fimdesc = :dataFimDesc WHERE token = :token");
        $atualiza->bindValue(':situacao', "Descarga Finalizada");
        $atualiza->bindValue(':dataFimDesc', $data);
        $atualiza->bindValue(':token', $token);
        if($atualiza->execute()){
            echo "<script>alert('Descarga Finalizada!');</script>";
            echo "<script>window.location.href='descargas.php'</script>";
        }else{
            print_r($atualiza->errorInfo());
        }
    }

}else{

    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";

}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
                        <h2>Finalizar Descarga</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="finalizar.php" method="post"  enctype="multipart/form-data">
                        <div id="formulario">
                            <input type="hidden" name="token" id="token" value="<?=$token?>">
                            <div class="form-row">
                                <?php if($problema=="SIM"): ?>
                                <div class="form-group col-md-6 espaco">
                                    <label for="problemaDescarga"> Problemas na Descarga</label>
                                <select class="form-control" required name="problemaDescarga" id="problemaDescarga">
                                    <option value=""></option>
                                        <option value="Avaria">Avaria</option>
                                        <option value="Item Faltando">Item Faltando</option>
                                        <option value="Intem Trocado">Item Trocado</option>
                                </select>
                                </div>
                                <?php endif; ?>
                                <?php if($pendencias>0): ?>
                                <div class="form-group col-md-6 espaco  ">
                                    <label for="nfDev">NF de Devolução</label>
                                    <input type="file" multiple name="nfDev[]" class="form-control" id="nfDev">
                                </div>    
                                <?php endif; ?>
                            </div>
                            <div class="form-row">
                                <?php if($problema=="SIM"): ?>
                                <div class="form-group col-md-12 espaco">
                                    <label for="obsProblema"> Obs. do Problema</label>
                                    <textarea name="obsProblema" id="obsProblema" rows="3" class="form-control"></textarea>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                        </div>
                        <button type="submit" class="btn btn-primary"> Registrar </button>
                    </form>
                </div>
            </div>
        </div>
</body>
</html>