<?php
// Remove os Warnings da tela
error_reporting(~E_WARNING);

// Definindo a sessão
session_start();

if ($_COOKIE['usuario']) {
    $_SESSION['usuario'] = $_COOKIE['usuario'];
}

if (!$_SESSION['usuario']) {
    header('Location: /auth/login.php');
}

require_once("../db/conexao.php");
$conexao = novaConexao();

if ($_GET['codigo']) {
    $excluirSQL = "DELETE FROM tbl_funcionario WHERE id_funcionario = ?";
    $stmt = $conexao->prepare($excluirSQL);
    $stmt->bind_param("i", $_GET['codigo']);

    if ($stmt->execute()) {
        $sucesso = "Registro excluído com sucesso!";
    } else {
        $erro = "Ocorreu um erro ao excluir o registro, por favor, tente novamente";
    }
}

$conexao->close();

require_once('layout/menu.php');
?>

<main class="principal">
    <div class="conteudo">
        <h3>Excluir Funcionário</h3>

        <div class="sucesso">
            <?= $sucesso ? $sucesso : '' ?>
        </div>

        <div class="erro">
            <?= $erro ? $erro : '' ?>
        </div>
</main>

<?php
require_once('layout\rodape.php');
?>