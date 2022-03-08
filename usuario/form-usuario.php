<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false && ($_SESSION['tipousuario']==99)){

    $tipoUsuario = $_SESSION['tipousuario'];
    
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
                        <img src="../assets/images/icones/icon-usuario.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Cadastrar Usuário</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="add-usuario.php" method="post" >
                        <div id="formulario">
                            <div class="form-row">
                                <div class="form-group col-md-3 espaco ">
                                    <label for="nome">Nome </label>
                                    <input type="text" required name="nome" class="form-control" id="nome">
                                </div>
                                <div class="form-group col-md-3 espaco ">
                                    <label for="cpf"> CPF(Somente Números) </label>
                                    <input type="text" required name="cpf" id="cpf" class="form-control">
                                </div>
                                <div class="form-group col-md-3 espaco ">
                                    <label for="email">E-mail </label>
                                    <input type="email" required name="email" id="email" class="form-control">
                                </div>
                                <div class="form-group col-md-3 espaco ">
                                    <label for="senha">Senha</label>
                                    <input type="password" required name="senha" id="senha" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3 espaco ">
                                    <label for="tipoUsuario">Tipo de Usuário</label>
                                    <select name="tipoUsuario" id="tipoUsuario" class="form-control">
                                        <option value=""></option>
                                    <?php 
                                    $select = $db->query("SELECT * FROM tipo_usuario");
                                    $tipos = $select->fetchAll();
                                    foreach($tipos as $tipo):
                                    ?>
                                        <option value="<?=$tipo['idtipo_usuario']?>"><?=$tipo['nome_tipo_usuario']?></option>
                                    <?php
                                    endforeach;
                                    ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"> Cadastrar </button>
                    </form>
                </div>
            </div>
        </div>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>        
    </body>
</html>