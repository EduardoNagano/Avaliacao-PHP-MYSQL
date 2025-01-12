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

// Recupera as empresas cadastradas no BD
require_once('../model/Empresa.php');
$empresas = Empresa::getAllEmpresas();

require_once('../model/Funcionario.php');
$funcionario = new Funcionario([]);

// Recupera o funcionário selecionado na tela anterior
if (key_exists('codigo', $_GET)) {
    $funcionario = $funcionario->getFuncionario($_GET['codigo']);
}

// Verifica se o formulário foi submetido
if (count($_POST) > 0) {
    $dados = $_POST;
    $erros = [];

    // Define os dados do funcionário
    $funcionario->setNome($dados['nome_funcionario']);
    $funcionario->setCpf($dados['cpf']);
    $funcionario->setRg($dados['rg']);
    $funcionario->setEmail($dados['email']);
    $funcionario->setEmpresa($dados['empresa']);
    $funcionario->setDataCadastro($dados['data_cadastro']);
    $funcionario->setSalario($dados['salario']);

    // Valida os dados inseridos
    $erros = Funcionario::validateFuncionario($funcionario);

    // Se os dados foram validados, atualiza o funcionário
    $retorno = "";
    if (!count($erros)) {
        [$funcionario, $retorno] = $funcionario->update();
    }
}

require_once('layout/menu.php');
?>

<main class="principal">
    <div class="conteudo">

        <h3>Editar Funcionário</h3>

        <div class="sucesso">
            <?= $retorno == "Sucesso" ? 'Funcionário cadastrado com sucesso!' : '' ?>
        </div>

        <div class="erro">
            <?= $retorno == "ERRO" ? 'Erro ao cadastrar, por favor tente novamente!' : '' ?>
        </div>

        <form action="#" method="post">
            <div>
                <span class="rotulo">
                    <label for="nome_funcionario">Nome*</label>
                </span>
                <span class="campo">
                    <input type="text" name="nome_funcionario" id="nome_funcionario" maxlength="50"
                        value="<?= $funcionario->getNome() ? $funcionario->getNome() : '' ?>">
                </span>
            </div>
            <div class="erro">
                <?php
                if (!is_null($erros) and key_exists('nome_funcionario', $erros)) {
                    echo $erros['nome_funcionario'];
                }
                ?>
            </div>

            <div>
                <span class="rotulo">
                    <label for="cpf">CPF*</label>
                </span>
                <span class="campo">
                    <input type="text" name="cpf" id="cpf" maxlength="11"
                        value="<?= $funcionario->getCpf() ?>">
                </span>
            </div>
            <div class="erro">
                <?php
                if (!is_null($erros) and key_exists('cpf', $erros)) {
                    echo $erros['cpf'];
                }
                ?>
            </div>

            <div>
                <span class="rotulo">
                    <label for="rg">RG</label>
                </span>
                <span class="campo">
                    <input type="text" name="rg" id="rg" maxlength="20"
                        value="<?= $funcionario->getRg() ?>">
                </span>
            </div>
            <div class="erro">
                <?php
                if (!is_null($erros) and key_exists('rg', $erros)) {
                    echo $erros['rg'];
                }
                ?>
            </div>

            <div>
                <span class="rotulo">
                    <label for="email">E-mail*</label>
                </span>
                <span class="campo">
                    <input type="email" name="email" id="email" maxlength="30"
                        value="<?= $funcionario->getEmail() ?>">
                </span>
            </div>
            <div class="erro">
                <?php
                if (!is_null($erros) and key_exists('email', $erros)) {
                    echo $erros['email'];
                }
                ?>
            </div>

            <div>
                <span class="rotulo">
                    <label for="empresa">Empresa*</label>
                </span>
                <span class="campo">
                    <select name="empresa" id="empresa">
                        <option value="">Selecione uma empresa</option>
                        <?php foreach ($empresas as $empresa): ?>
                            <option value="<?= $empresa->getId() ?>" <?= $funcionario->getEmpresa() == $empresa->getId() ? 'selected' : '' ?>>
                                <?= $empresa->getNome() ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </span>
            </div>
            <div class="erro">
                <?php
                if (!is_null($erros) and key_exists('empresa', $erros)) {
                    echo $erros['empresa'];
                }
                ?>
            </div>

            <div>
                <span class="rotulo">
                    <label for="email">Dt Cadastro</label>
                </span>
                <span class="campo">
                    <input type="date" name="data_cadastro" id="data_cadastro"
                        value="<?= $funcionario->getDataCadastro() ?>">
                </span>
            </div>
            <div class="erro">
                <?php
                if (!is_null($erros) and key_exists('data_cadastro', $erros)) {
                    echo $erros['data_cadastro'];
                }
                ?>
            </div>

            <div>
                <span class="rotulo">
                    <label for="rg">Salário</label>
                </span>
                <span class="campo">
                    <input type="text" name="salario" id="salario"
                        placeholder="99999,99"
                        value="<?= $funcionario->getSalario() ?>">
                </span>
            </div>
            <div class="erro">
                <?php
                if (!is_null($erros) and key_exists('salario', $erros)) {
                    echo $erros['salario'];
                }
                ?>
            </div>

            <button>Atualizar</button>
        </form>
    </div>
</main>

<?php
require_once('layout\rodape.php');
?>