<?php
require_once '../Model/VendedorModel.php';

class VendedorController
{

    private $vendedor;

    public function __construct()
    {
        $this->vendedor = new Vendedor;
    }

    public function listarVendedor()
    {
        try {
            $dados = $this->vendedor->listarVendedor();
            echo json_encode([
                "status" => "success",
                "data" => $dados
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Erro ao listar vendedor: " . $e->getMessage()
            ]);
        }
        exit;
    }

    public function salvarVendedor()
    {
        try {
            if (isset($_POST['codigo']) && isset($_POST['nome'])) {
                $this->vendedor->setCodigo($_POST['codigo']);
                $this->vendedor->setNome($_POST['nome']);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Dados insuficientes para salvar."
                ]);
                exit;
            }

            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $this->vendedor->setId($_POST['id']);
                $resultado = $this->vendedor->atualizarVendedor();
            } else {
                $resultado = $this->vendedor->salvarVendedor();
            }

            if ($resultado) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Vendedor salvo com sucesso."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Falha ao salvar o vendedor."
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

    public function deletarVendedor()
    {
        try {
            if (!isset($_POST['id'])) {
                echo json_encode([
                    "status" => "error",
                    "message" => "ID do vendedor não fornecido."
                ]);
                exit;
            }

            $this->vendedor->setId($_POST['id']);
            $resultado = $this->vendedor->deletarVendedor();

            if ($resultado) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Vendedor deletado com sucesso."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Falha ao deletar o vendedor."
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
    $controller = new VendedorController();

    switch ($action) {
        case 'listar':
            $controller->listarVendedor();
            break;

        case 'salvar':
            $controller->salvarVendedor();
            break;

        case 'deletar':
            $controller->deletarVendedor();
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