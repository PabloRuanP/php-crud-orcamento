<?php
require_once '../Model/ClienteModel.php';

class ClienteController
{

    private $cliente;

    public function __construct()
    {
        $this->cliente = new Cliente;
    }

    public function listarCliente()
    {
        try {
            $dados = $this->cliente->listarCliente();
            echo json_encode([
                "status" => "success",
                "data" => $dados
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Erro ao listar clientes: " . $e->getMessage()
            ]);
        }
        exit;
    }

    public function salvarCliente()
    {
        try {
            if (isset($_POST['codigo']) && isset($_POST['nome'])) {
                $this->cliente->setCodigo($_POST['codigo']);
                $this->cliente->setNome($_POST['nome']);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Dados insuficientes para salvar."
                ]);
                exit;
            }

            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $this->cliente->setId($_POST['id']);
                $resultado = $this->cliente->atualizarCliente();
            } else {
                $resultado = $this->cliente->salvarCliente();
            }

            if ($resultado) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Cliente salvo com sucesso."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Falha ao salvar o cliente."
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

    public function deletarCliente()
    {
        try {
            if (!isset($_POST['id'])) {
                echo json_encode([
                    "status" => "error",
                    "message" => "ID do cliente não fornecido."
                ]);
                exit;
            }

            $this->cliente->setId($_POST['id']);
            $resultado = $this->cliente->deletarCliente();

            if ($resultado) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Cliente deletado com sucesso."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Falha ao deletar o cliente."
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
    $controller = new ClienteController();

    switch ($action) {
        case 'listar':
            $controller->listarCliente();
            break;

        case 'salvar':
            $controller->salvarCliente();
            break;

        case 'deletar':
            $controller->deletarCliente();
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