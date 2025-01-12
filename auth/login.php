<?php
session_start();

error_reporting(~E_WARNING);

require_once "../db/conexao.php";
$conexao = novaConexao();

$login = $_POST['login'];
$senha = $_POST['senha'];
$erros = [];

if ($login) {

    if (!filter_var($login, FILTER_VALIDATE_EMAIL)) {
        $erros['login'] = 'Email inválido';
    } else {
        $sql = "SELECT * FROM tbl_usuario WHERE login = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $login);

        if ($stmt->execute()) {
            $resultado = $stmt->get_result();

            if ($resultado->num_rows > 0) {
                $dados = $resultado->fetch_assoc();
                if ($dados['senha'] == md5($senha)) {
                    $_SESSION['usuario'] = $dados['login'];
                    $exp = time() + 60 * 60 * 24;
                    setcookie('usuario', $dados['login'], $exp);
                    header('Location: ../index.php');
                }
            }
        }

        if (!$_SESSION['usuario']) {
            $erros['invalido'] = 'Usuário/Senha inválido!';
        }
    }
} elseif (count($_POST) > 0) {
    $erros['invalido'] = 'Por favor, informe o login!';
}

require_once('../view/layout/cabecalho.php');
?>

<link rel="stylesheet" href="\resources\css\login.css">

<main class="principal">

    <div class="conteudo">
        <h3>Identifique-se</h3>
        <?php if ($erros['invalido']): ?>
            <div class="erros">
                <p><?= $erros['invalido'] ?></p>
            </div>
        <?php endif ?>
        <form action="" method="post">
            <div>
                <label for="login">Login</label>
                <input type="login" name="login" id="login" value="<?= $login ?>">
                <div class="erro">
                    <?php
                    if (!is_null($erros) and key_exists('login', $erros)) {
                        echo $erros['login'];
                    }
                    ?>
                </div>
            </div>
            <div>
                <label for="senha">Senha</label>
                <input type="password" name="senha" id="senha">
            </div>
            <button>Entrar</button>
        </form>
    </div>
</main>

<?php
require_once('../view/layout/rodape.php');
?>