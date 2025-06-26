<?php
require_once '../Model/OrcamentoModel.php';

class OrcamentoController
{

    private $orcamento;

    public function __construct()
    {
        $this->orcamento = new Orcamento();
    }

    public function listarOrcamento()
    {
        try {
            $dados = $this->orcamento->listarOrcamento();
            echo json_encode([
                "status" => "success",
                "data" => $dados
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Erro ao listar orçamentos: " . $e->getMessage()
            ]);
        }
        exit;
    }

    public function salvarOrcamento()
    {
        try {
            if (
                isset($_POST['cliente_id']) &&
                isset($_POST['vendedor_id']) &&
                isset($_POST['itens'])
            ) {
                if ($_POST['cliente_id'] == $_POST['vendedor_id']) {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Cliente e vendedor não podem ser a mesma pessoa."
                    ]);
                    exit;
                }

                $this->orcamento->setClienteId($_POST['cliente_id']);
                $this->orcamento->setVendedorId($_POST['vendedor_id']);

                $itens = $_POST['itens'];

                if (empty($itens)) {
                    echo json_encode([
                        "status" => "error",
                        "message" => "É necessário adicionar ao menos um item no orçamento."
                    ]);
                    exit;
                }
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Dados insuficientes para salvar o orçamento."
                ]);
                exit;
            }

            if (isset($_POST['id']) && !empty($_POST['id'])) {

                $this->orcamento->setId($_POST['id']);
                $resultado = $this->orcamento->atualizarOrcamento($itens);
            } else {

                $resultado = $this->orcamento->salvarOrcamento($itens);
            }

            if ($resultado) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Orçamento salvo com sucesso."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Falha ao salvar o orçamento."
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Erro no servidor: " . $e->getMessage()
            ]);
        }
        exit;
    }

    public function deletarOrcamento()
    {
        try {
            if (!isset($_POST['id'])) {
                echo json_encode([
                    "status" => "error",
                    "message" => "ID do orçamento não fornecido."
                ]);
                exit;
            }

            $this->orcamento->setId($_POST['id']);
            $resultado = $this->orcamento->deletarOrcamento();

            if ($resultado) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Orçamento deletado com sucesso."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Falha ao deletar o orçamento."
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Erro no servidor: " . $e->getMessage()
            ]);
        }
        exit;
    }

    public function buscarItens()
    {
        try {
            if (!isset($_GET['id'])) {
                echo json_encode([
                    "status" => "error",
                    "message" => "ID do orçamento não fornecido."
                ]);
                exit;
            }

            $itens = $this->orcamento->buscarItensPorOrcamento($_GET['id']);

            echo json_encode([
                "status" => "success",
                "data" => $itens
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Erro ao buscar itens: " . $e->getMessage()
            ]);
        }
        exit;
    }

    public function relatorioOrcamento()
    {
        try {
            $data_inicio = $_GET['data_inicio'] ?? null;
            $data_fim = $_GET['data_fim'] ?? null;
            $produto_id = $_GET['produto_id'] ?? null;

            if (!$data_inicio || !$data_fim) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Período (Data início e fim) são obrigatórios."
                ]);
                exit;
            }

            $dados = $this->orcamento->relatorioOrcamentos($data_inicio, $data_fim, $produto_id);

            echo json_encode([
                "status" => "success",
                "data" => $dados
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Erro ao gerar relatório: " . $e->getMessage()
            ]);
        }
        exit;
    }

    public function buscarOrcamento()
    {
        try {
            if (!isset($_GET['id'])) {
                echo json_encode([
                    "status" => "error",
                    "message" => "ID do orçamento não fornecido."
                ]);
                exit;
            }

            $orcamento = $this->orcamento->buscarOrcamentoPorId($_GET['id']);
            $itens = $this->orcamento->buscarItensPorOrcamento($_GET['id']);

            if (!$orcamento) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Orçamento não encontrado."
                ]);
                exit;
            }

            echo json_encode([
                "status" => "success",
                "data" => [
                    "orcamento" => $orcamento,
                    "itens" => $itens
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Erro ao buscar orçamento: " . $e->getMessage()
            ]);
        }
        exit;
    }
};


if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $controller = new OrcamentoController();

    switch ($action) {
        case 'listar':
            $controller->listarOrcamento();
            break;

        case 'salvar':
            $controller->salvarOrcamento();
            break;

        case 'deletar':
            $controller->deletarOrcamento();
            break;

        case 'buscarItens':
            $controller->buscarItens();
            break;
        case 'buscarOrcamento':
            $controller->buscarOrcamento();
            break;

        case 'relatorio':
            $controller->relatorioOrcamento();
            break;

        default:
            echo json_encode([
                "status" => "error",
                "message" => "Ação inválida."
            ]);
            exit;
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Nenhuma ação especificada."
    ]);
    exit;
};