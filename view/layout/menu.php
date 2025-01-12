<?php
require_once('cabecalho.php'); 
?>

<nav class="menu">
    <div>
        <a class="opcao" href="/index.php">Home</a>
        <a class="opcao" href="/view/novo_funcionario.php">Novo Funcionário</a>
        <a class="opcao" href="/view/nova_empresa.php">Nova Empresa</a>
    </div>

    <div>
        <span class="usuario">Usuário: <?= $_SESSION['usuario'] ?></span>
        <a href="auth/logout.php" class="vermelho">Sair</a>
    </div>
</nav>