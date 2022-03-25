<div class="menu-lateral" id="menu-lateral">
    <div class="logo">  
        <img src="../assets/images/logo.png" alt="">
    </div>
    <div class="opcoes" >
        <div class="item">
            <a href="../index.php">
                <img src="../assets/images/menu/inicio.png" alt="">
            </a>
        </div>
        <div class="item">
            <a class="" onclick="menuDescarga()">
                <img src="../assets/images/menu/menu-descarga.png" >
            </a>
            <nav id="submenuDescarga">
                <ul class="nav flex-column">
                    <?php if($tipoUsuario==1 || $tipoUsuario==99): ?>
                    <li class="nav-item"> <a class="nav-link" href="../descarga/form-descarga.php"> Nova Descarga </a> </li>
                    <?php endif; ?>
                    <li class="nav-item"> <a class="nav-link" href="../descarga/descargas.php">Descargas </a> </li>
                    <li class="nav-item"> <a class="nav-link" href="../descarga/pendencias.php">Pendências </a> </li>
                </ul>
            </nav>
        </div>
        <?php if($tipoUsuario==99 || $tipoUsuario ==3): ?>
        <div class="item">
            <a onclick="menuFornecedor()">
                <img src="../assets/images/menu/menu-fornecedor.png" >
            </a>
            <nav id="submenuFornecedor">
                <ul class="nav flex-column">
                    <li class="nav-item"> <a class="nav-link" href="../fornecedor/form-fornecedor.php">Cadastrar Fornecedor</a> </li>
                    <li class="nav-item"> <a class="nav-link" href="../fornecedor/fornecedores.php">Listar Fornecedores</a> </li>
                </ul>
            </nav>
        </div>
        <div class="item">
            <a class="" onclick="menuTransportadora()">
                <img src="../assets/images/menu/menu-transportadora.png">
            </a>
            <nav id="submenuTransportadora">
                <ul class="nav flex-column">
                    <li class="nav-item"> <a class="nav-link" href="../transportadora/form-transportadora.php"> Cadastrar Transportadora </a> </li>
                    <li class="nav-item"> <a class="nav-link" href="../transportadora/transportadoras.php">Listar Transportadoras </a> </li>
                </ul>
            </nav>
        </div>
        <div class="item">
            <a onclick="menuUsuario()">
                <img src="../assets/images/menu/usuarios.png">
            </a>
            <nav id="submenuUsuario">
                <ul class="nav flex-column">
                    <li class="nav-item"> <a class="nav-link" href="../usuario/form-usuario.php"> Cadastrar Usuário </a> </li>
                </ul> 
            </nav> 
        </div>
        <?php endif; ?>
        <div class="item">
            <a href="../sair.php">
                <img src="../assets/images/menu/sair.png" alt="">
            </a>
        </div>
    </div>                
</div>