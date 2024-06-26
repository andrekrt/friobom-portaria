<?php

session_start();
require("conexao.php");

if(isset($_SESSION['idusuario']) && empty($_SESSION['idusuario'])==false){

    $idUsuario = $_SESSION['idusuario'];
    $tipoUsuario = $_SESSION['tipousuario'];
    $nomeUsuario = $_SESSION['nome'];
    $situacao = "Finalizada";
    
    $sql = $db->query("SELECT * FROM descarga GROUP BY token");
    $total = $sql->rowCount();
    
    $sqlPend = $db->prepare("SELECT * FROM descarga WHERE situacao = :situacao GROUP BY token ");
    $sqlPend->bindValue(':situacao', 'Aguardando Validação');
    $sqlPend->execute();
    $numValidando = $sqlPend->rowCount();

    $sqlValidade = $db->prepare("SELECT * FROM descarga WHERE situacao = :situacao AND pago = :pago GROUP BY token");
    $sqlValidade->bindValue(':situacao', 'Validada');
    $sqlValidade->bindValue(':pago', 1);
    $sqlValidade->execute();
    $numValidada = $sqlValidade->rowCount();

    $sqlValidada = $db->prepare("SELECT * FROM descarga WHERE situacao = :situacao AND pago = :pago GROUP BY token");
    $sqlValidada->bindValue(':situacao', 'Validada');
    $sqlValidada->bindValue(':pago', 1);
    $sqlValidada->execute();
    $numValidada = $sqlValidada->rowCount();

    $sqlDescarregando = $db->prepare("SELECT * FROM descarga WHERE situacao = :situacao GROUP BY token");
    $sqlDescarregando->bindValue(':situacao', 'Descarga Iniciada');
    $sqlDescarregando->execute();
    $numDescarregando = $sqlDescarregando->rowCount();

    $sqlFinalizada = $db->prepare("SELECT * FROM descarga WHERE situacao = :situacao GROUP BY token");
    $sqlFinalizada->bindValue(':situacao', 'Descarga Finalizada');
    $sqlFinalizada->execute();
    $numFinalizada = $sqlFinalizada->rowCount();

    $sqlRecebido = $db->prepare("SELECT * FROM descarga WHERE situacao = :situacao GROUP BY token");
    $sqlRecebido->bindValue(':situacao', 'Recebido');
    $sqlRecebido->execute();
    $numRecebido = $sqlRecebido->rowCount();

}else{
    header("Location:login.php");
}

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FRIOBOM - DESCARGA</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="assets/favicon/site.webmanifest">
        <link rel="mask-icon" href="assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
    </head>
    <body>
        <div class="container-fluid corpo">
            <div class="menu-lateral" id="menu-lateral">
                <div class="logo">  
                    <img src="assets/images/logo.png" alt="">
                </div>
                <div class="opcoes" >
                    <div class="item">
                        <a href="index.php">
                            <img src="assets/images/menu/inicio.png" alt="">
                        </a>
                    </div>
                    <?php if($tipoUsuario<>7 && $tipoUsuario<>8 && $tipoUsuario<>9): ?>
                    <div class="item">
                        <a class="" onclick="menuDescarga()">
                            <img src="assets/images/menu/menu-descarga.png" >
                        </a>
                        <nav id="submenuDescarga">
                            <ul class="nav flex-column">
                                <?php if($tipoUsuario==1 || $tipoUsuario==99 || $tipoUsuario==10): ?>
                                <li class="nav-item"> <a class="nav-link" href="descarga/form-descarga.php"> Nova Descarga </a> </li>
                                <?php endif; ?>
                                <li class="nav-item"> <a class="nav-link" href="descarga/descargas.php">Descargas </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="descarga/pendencias.php">Pendências </a> </li>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>
                    <?php if($tipoUsuario==99 || $tipoUsuario ==3) : ?>
                    <div class="item">
                        <a onclick="menuFornecedor()">
                            <img src="assets/images/menu/menu-fornecedor.png" >
                        </a>
                        <nav id="submenuFornecedor">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="fornecedor/form-fornecedor.php">Cadastrar Fornecedor</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="fornecedor/fornecedores.php">Listar Fornecedores</a> </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="item">
                        <a class="" onclick="menuTransportadora()">
                            <img src="assets/images/menu/menu-transportadora.png">
                        </a>
                        <nav id="submenuTransportadora">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="transportadora/form-transportadora.php"> Cadastrar Transportadora </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="transportadora/transportadoras.php">Listar Transportadoras </a> </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="item">
                        <a onclick="menuUsuario()">
                            <img src="assets/images/menu/usuarios.png">
                        </a>
                        <nav id="submenuUsuario">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="usuario/form-usuario.php"> Cadastrar Usuário </a> </li>
                            </ul> 
                        </nav> 
                    </div>
                    <?php endif; ?>
                  
                    <div class="item">
                        <a href="sair.php">
                            <img src="assets/images/menu/sair.png" alt="">
                        </a>
                    </div>
                </div>                
            </div>
            <!-- Tela com os dados -->
            <div class="tela-principal">
                <div class="menu-superior">
                   <div class="icone-menu-superior">
                        <img src="assets/images/icones/home.png" alt="">
                   </div>
                   <div class="title">
                        <h2>Bem-Vindo <?php echo $nomeUsuario ?></h2>
                   </div>
                   <div class="menu-mobile">
                        <img src="assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                   </div>
                </div>
                <div class="menu-principal">
                    <div class="area-indice-val">
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Total de Descargas</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/icon-descarga.png" alt="">
                                <p class="qtde">  <?= $total?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Aguardando Validação</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/validacao.png" alt="">
                                <p class="qtde"> <?=$numValidando?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Prontas para Descarregar</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/icon-descarga.png" alt="">
                                <p class="qtde"> <?=$numValidada?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Descarregando</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/icon-descarga.png" alt="">
                                <p class="qtde"> <?=$numDescarregando?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Descargas Finalizadas</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/icon-descarga.png" alt="">
                                <p class="qtde"> <?=$numFinalizada?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Recebidas</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/recebido.png" alt="">
                                <p class="qtde"> <?=$numRecebido?> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="assets/js/jquery.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/menu.js"></script>
        <!-- sweert alert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>

    
<!-- msg de sucesso ou erro -->
<?php
    // Verifique se há uma mensagem de confirmação na sessão
    if (isset($_SESSION['msg']) && $_SESSION['icon']) {
        // Exiba um alerta SweetAlert
        echo "<script>
                Swal.fire({
                  icon: '$_SESSION[icon]',
                  title: '$_SESSION[msg]',
                  showConfirmButton: true,
                });
              </script>";

        // Limpe a mensagem de confirmação da sessão
        unset($_SESSION['msg']);
        unset($_SESSION['status']);
    }
?>
</html>