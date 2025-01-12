<?php
class Funcionario
{
    private $id;
    private $nome;
    private $cpf;
    private $rg;
    private $email;
    private $empresa;
    private $dataCadastro;
    private $salario;
    private $bonificacao;
    private $nomeEmpresa;

    // Getters and Setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome): void
    {
        $this->nome = $nome;
    }

    public function getCpf()
    {
        return $this->cpf;
    }

    public function setCpf($cpf): void
    {
        $this->cpf = $cpf;
    }

    public function getRg()
    {
        return $this->rg;
    }

    public function setRg($rg): void
    {
        $this->rg = $rg;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getEmpresa()
    {
        return $this->empresa;
    }

    public function setEmpresa($empresa): void
    {
        $this->empresa = $empresa;
    }

    public function getDataCadastro()
    {
        return $this->dataCadastro;
    }

    public function setDataCadastro($dataCadastro): void
    {
        $this->dataCadastro = $dataCadastro;
    }

    public function getSalario()
    {
        return $this->salario;
    }

    public function setSalario($salario): void
    {
        $this->salario = $salario;
    }

    public function getBonificacao()
    {
        return $this->bonificacao;
    }

    public function setBonificacao($bonificacao): void
    {
        $this->bonificacao = $bonificacao;
    }

    public function getNomeEmpresa()
    {
        return $this->nomeEmpresa;
    }

    public function setNomeEmpresa($nomeEmpresa): void
    {
        $this->nomeEmpresa = $nomeEmpresa;
    }

    // Construtor
    function __construct($dados)
    {
        $this->id = $dados['id_funcionario'] ? $dados['id_funcionario'] : '';
        $this->nome = $dados['nome'] ? $dados['nome'] : '';
        $this->cpf = $dados['cpf'] ? $dados['cpf'] : '';
        $this->rg = $dados['rg'] ? $dados['rg'] : '';
        $this->email = $dados['email'] ? $dados['email'] : '';
        $this->empresa = $dados['id_empresa'] ? $dados['id_empresa'] : '';
        $this->dataCadastro = $dados['data_cadastro'] ? $dados['data_cadastro'] : '';
        $this->salario = $dados['salario'] ? $dados['salario'] : '';
        $this->bonificacao = $dados['bonificacao'] ? $dados['bonificacao'] : '';
        $this->nomeEmpresa = $dados['nome_empresa'] ? $dados['nome_empresa'] : '';
    }

    // Recupera um funcionário específico
    public static function getFuncionario($id): Funcionario
    {
        require_once("../db/conexao.php");
        $conexao = novaConexao();

        $retorno = null;

        if ($id) {
            $sql = "SELECT * FROM tbl_funcionario WHERE id_funcionario = ?";
            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                if ($resultado->num_rows > 0) {
                    $retorno = (new Funcionario($resultado->fetch_assoc()));
                }
            }
        }

        $conexao->close();

        return $retorno;
    }

    // Recupera todos os funcionários incluindo o nome da empresa associada
    public static function getAllFuncionarios(): array
    {
        require_once "db/conexao.php";
        $conexao = novaConexao();

        // Recupera os funcionários
        $sql = "SELECT f.*, e.nome as nome_empresa
            FROM tbl_funcionario as f 
                INNER JOIN tbl_empresa as e 
                ON f.id_empresa = e.id_empresa
            ORDER BY id_funcionario";

        $resultado = $conexao->query($sql);

        $registros = [];

        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $registros[] = $row;
            }
        }

        $conexao->close();

        $funcionarios = [];
        foreach ($registros as $registro) {
            $funcionarios[] = new Funcionario($registro);
        }

        return $funcionarios;
    }

    // Validação dos dados do funcionário
    public static function validateFuncionario(Funcionario $funcionario): array
    {
        // Remove os Warnings da tela
        error_reporting(~E_DEPRECATED);

        $erros = [];

        // Validação dos campos
        if (trim($funcionario->getNome()) === "") {
            $erros['nome_funcionario'] = 'Nome é obrigatório';
        }

        if (trim($funcionario->getCPF()) === "") {
            $erros['cpf'] = 'CPF é obrigatório';
        }

        if (trim($funcionario->getEmail()) === "") {
            $erros['email'] = 'E-mail é obrigatório';
        } else if (!filter_var($funcionario->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $erros['email'] = 'Email inválido';
        }

        if (trim($funcionario->getEmpresa()) === "") {
            $erros['empresa'] = 'Empresa é obrigatório';
        }

        return $erros;
    }

    // Salva o funcionário
    public function save()
    {
        require_once "../db/conexao.php";
        $conexao = novaConexao();

        $sql = "INSERT INTO tbl_funcionario
        (nome, cpf, rg, email, id_empresa, data_cadastro, salario, bonificacao)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conexao->prepare($sql);

        $params = [
            $this->nome,
            $this->cpf,
            $this->rg,
            $this->email,
            $this->empresa,
            $this->dataCadastro,
            $this->salario,
            $this->calcularBonificacao($this->dataCadastro, $this->salario)
        ];

        $stmt->bind_param("ssssisdd", ...$params);

        if ($stmt->execute()) {
            unset($params);
            return [$this, "Sucesso"];
        }else{
            return [null, "ERRO"];
        }
    }

    public function update()
    {
        require_once "../db/conexao.php";
        $conexao = novaConexao();

        $sql = "UPDATE tbl_funcionario
        SET nome = ?, cpf = ?, rg = ?, email = ?, id_empresa = ?, data_cadastro = ?, salario = ?, bonificacao = ?
        WHERE id_funcionario = ?";

        $stmt = $conexao->prepare($sql);

        $params = [
            $this->nome,
            $this->cpf,
            $this->rg,
            $this->email,
            $this->empresa,
            $this->dataCadastro,
            $this->salario,
            $this->calcularBonificacao($this->dataCadastro, $this->salario),
            $this->id
        ];

        $stmt->bind_param("ssssisddi", ...$params);

        if ($stmt->execute()) {
            unset($params);
            return [$this, "Sucesso"];
        }else{
            return [null, "ERRO"];
        }
    }

    // Calcula a bonificação do funcionário
    public static function calcularBonificacao($dataCadastro, $salario): float
    {
        if ($dataCadastro and $salario) {
            $dataCadastro = new DateTime($dataCadastro);
            $tempoCasa = date_diff($dataCadastro, new DateTime())->format('%Y');
            if ($tempoCasa >= 5) {
                $bonus = $salario * 0.2;
            } elseif ($tempoCasa >= 1) {
                $bonus = $salario * 0.1;
            } else {
                $bonus = 0;
            }

            return $bonus;
        } else {
            return 0;
        }
    }
}
