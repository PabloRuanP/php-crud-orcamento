<?php
require_once '../Model/ProdutoModel.php';

class ProdutoController
{

    private $produto;

    public function __construct()
    {
        $this->produto = new Produto;
    }

    public function listarProduto()
    {
        try {
            $dados = $this->produto->listarProduto();
            echo json_encode([
                "status" => "success",
                "data" => $dados
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Erro ao listar produto: " . $e->getMessage()
            ]);
        }
        exit;
    }

    public function salvarProduto()
    {
        try {
            if (isset($_POST['codigo']) && isset($_POST['descricao']) && isset($_POST['preco'])) {
                $this->produto->setCodigo($_POST['codigo']);
                $this->produto->setDescricao($_POST['descricao']);
                $this->produto->setPreco($_POST['preco']);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Dados insuficientes para salvar."
                ]);
                exit;
            }

            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $this->produto->setId($_POST['id']);
                $resultado = $this->produto->atualizarProduto();
            } else {
                $resultado = $this->produto->salvarProduto();
            }

            if ($resultado) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Produto salvo com sucesso."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Falha ao salvar o produto."
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

    public function deletarProduto()
    {
        try {
            if (!isset($_POST['id'])) {
                echo json_encode([
                    "status" => "error",
                    "message" => "ID do produto não fornecido."
                ]);
                exit;
            }

            $this->produto->setId($_POST['id']);
            $resultado = $this->produto->deletarProduto();

            if ($resultado) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Produto deletado com sucesso."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Falha ao deletar o produto."
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
};

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    $controller = new ProdutoController();

    switch ($action) {
        case 'listar':
            $controller->listarProduto();
            break;

        case 'salvar':
            $controller->salvarProduto();
            break;

        case 'deletar':
            $controller->deletarProduto();
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