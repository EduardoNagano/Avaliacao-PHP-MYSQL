<?php
error_reporting(~E_WARNING);

session_start();

if ($_COOKIE['usuario']) {
    $_SESSION['usuario'] = $_COOKIE['usuario'];
}

if (!$_SESSION['usuario']) {
    header('Location: /auth/login.php');
}

require_once('../model/Empresa.php');
$empresa = new Empresa([]);

// Verifica se o formulário foi submetido
if (count($_POST) > 0) {
    $dados = $_POST;
    $erros = [];

    // Define os dados da empresa
    $empresa->setNome($dados['nome_empresa']);

    // Valida os dados inseridos
    $erros = Empresa::validateEmpresa($empresa);

    // Se os dados foram validados, cria a empresa
    $retorno = "";
    if (!count($erros)) {
        [$empresa, $retorno] = $empresa->save();
    }
}

require_once('layout/menu.php');
?>

<main class="principal">
    <div class="conteudo">

        <h3>Nova Empresa</h3>

        <div class="sucesso">
            <?= $retorno == "Sucesso" ? 'Empresa cadastrada com sucesso!' : '' ?>
        </div>

        <div class="erro">
            <?= $retorno == "ERRO" ? 'Erro ao cadastrar, por favor tente novamente!' : '' ?>
        </div>

        <form action="#" method="post">
            <div>
                <span class="rotulo">
                    <label for="nome_empresa">Nome*</label>
                </span>
                <span class="campo">
                    <input type="text" name="nome_empresa" id="nome_empresa" maxlength="40"
                        value="<?= $empresa->getNome() ? $empresa->getNome() : '' ?>">
                </span>
            </div>
            <div class="erro">
                <?php
                if (!is_null($erros) and key_exists('nome_empresa', $erros)) {
                    echo $erros['nome_empresa'];
                }
                ?>
            </div>
            <button>Cadastrar</button>
        </form>
    </div>
</main>

<?php
require_once('layout\rodape.php');
?>