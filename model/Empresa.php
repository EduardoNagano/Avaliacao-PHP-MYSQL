<?php
class Empresa
{
    private $id;
    private $nome;

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

    function __construct($dados)
    {
        $this->id = $dados['id_empresa'] ? $dados['id_empresa'] : $dados[0];
        $this->nome = $dados['nome'] ? $dados['nome'] : $dados[1];
    }

    // Recupera todos as empresas
    public static function getAllEmpresas(): array
    {
        require_once "../db/conexao.php";

        // Recupera os funcionários
        $sql = "SELECT * FROM tbl_empresa ORDER BY nome";

        $conexao = novaConexao();
        $resultado = $conexao->query($sql);

        $registros = [];

        if ($resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $registros[] = $row;
            }
        } elseif ($conexao->error) {
            echo "Erro: " . $conexao->error;
        }

        $conexao->close();

        $empresas = [];
        foreach ($registros as $registro) {
            $empresas[] = new Empresa($registro);
        }

        return $empresas;
    }

    // Validação dos dados da empresa
    public static function validateEmpresa(Empresa $empresa): array
    {
        // Remove os Warnings da tela
        error_reporting(~E_DEPRECATED);

        $erros = [];

        // Validação dos campos
        if (trim($empresa->nome) === "") {
            $erros['nome_empresa'] = 'Nome é obrigatório';
        }

        return $erros;
    }

    // Salva a empresa
    public function save()
    {
        require_once "../db/conexao.php";
        $conexao = novaConexao();

        $sql = "INSERT INTO tbl_empresa 
        (nome)
        VALUES (?)";

        $stmt = $conexao->prepare($sql);

        $params = [
            $this->nome
        ];

        $stmt->bind_param("s", ...$params);

        if ($stmt->execute()) {
            unset($dados);
            return [$this, "Sucesso"];
        } else {
            return [null, "ERRO"];
        }

        $sucesso = "Empresa cadastrada com sucesso!";

    }
}
