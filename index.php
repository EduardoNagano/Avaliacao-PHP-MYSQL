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

require_once('view/layout/menu.php');

// Recupera os funcionários
require_once('model/Funcionario.php');
$funcionarios = Funcionario::getAllFuncionarios();
?>

<main class="principal">
    <div class="conteudo">

        <h3>Funcionários</h3>

        <?php if (count($funcionarios) > 0): ?>
            <table class="registros" id="registros">
                <thead class="funcionario_row">
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>RG</th>
                        <th>E-mail</th>
                        <th>Empresa</th>
                        <th>Dt Cadastro</th>
                        <th>Salário</th>
                        <th>Bonificação</th>
                        <th style="width: 9%;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($funcionarios as $funcionario): ?>
                        <?php
                        // Verifica o tempo de casa do funcionário para incluir o destaque da linha
                        if ($funcionario->getDataCadastro()) {
                            $dataCadastro = new DateTime($funcionario->getDataCadastro());
                            $tempoCasa = date_diff($dataCadastro, new DateTime())->format('%Y');
                            if ($tempoCasa >= 5) {
                                $destaqueLinha = "bonus20";
                            } elseif ($tempoCasa >= 1) {
                                $destaqueLinha = "bonus10";
                            } else {
                                $destaqueLinha = "";
                            }
                        }
                        ?>
                        <tr class="funcionario_row <?= $destaqueLinha ?>">
                            <td><?= $funcionario->getId() ?></td>
                            <td><?= $funcionario->getNome() ?></td>
                            <td><?= $funcionario->getCpf() ?></td>
                            <td><?= $funcionario->getRg() ?></td>
                            <td><?= $funcionario->getEmail() ?></td>
                            <td><?= $funcionario->getNomeEmpresa() ?></td>
                            <td><?= $funcionario->getDataCadastro() ? $dataCadastro->format('d/m/Y') : '' ?> </td>
                            <td><?= $funcionario->getSalario() ? "R$ " . str_replace(".", ",",  $funcionario->getSalario()) : null ?></td>
                            <td><?= $funcionario->getBonificacao() ? "R$ " . str_replace(".", ",",  $funcionario->getBonificacao()) : null ?></td>
                            <td class="actions">
                                <a href="\view\editar_funcionario.php?codigo=<?= $funcionario->getId() ?>" class="default">Editar</a>
                                <a href="\view\excluir_funcionario.php?codigo=<?= $funcionario->getId() ?>" class="warning">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

            <div>
                <script type="text/javascript" src="\resources\js\geral.js"></script>
                <a href="#" class="gerarPDF" onclick="gerarPDF();">Gerar PDF</a>
            </div>
        <?php else: ?>
            <div class="erro">Nenhum registro encontrado</div>
        <?php endif ?>
    </div>
</main>

<?php
require_once('view\layout\rodape.php');
?>