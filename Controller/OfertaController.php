<?php
require_once '../Model/OfertaModel.php';

class OfertaController
{

    private $oferta;

    public function __construct()
    {
        $this->oferta = new Oferta;
    }

    public function listarOferta()
    {
        try {
            $dados = $this->oferta->listarOferta();
            echo json_encode([
                "status" => "success",
                "data" => $dados
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Erro ao listar ofertas: " . $e->getMessage()
            ]);
        }
        exit;
    }

    public function salvarOferta()
    {
        try {
            if (isset($_POST['produto_id']) && isset($_POST['quantidade_levar']) && isset($_POST['quantidade_pagar'])) {

                if ($_POST['quantidade_levar'] <= $_POST['quantidade_pagar']) {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Quantidade a levar deve ser maior que a quantidade a pagar."
                    ]);
                    exit;
                } else {
                    $this->oferta->setProdutoId($_POST['produto_id']);
                    $this->oferta->setQuantidadeLevar($_POST['quantidade_levar']);
                    $this->oferta->setQuantidadePagar($_POST['quantidade_pagar']);
                }
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Dados insuficientes para salvar."
                ]);
                exit;
            }

            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $this->oferta->setId($_POST['id']);
                $resultado = $this->oferta->atualizarOferta();
            } else {
                $resultado = $this->oferta->salvarOferta();
            }

            if ($resultado) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Oferta salvo com sucesso."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Falha ao salvar o oferta."
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

    public function deletarOferta()
    {
        try {
            if (!isset($_POST['id'])) {
                echo json_encode([
                    "status" => "error",
                    "message" => "ID da oferta não fornecido."
                ]);
                exit;
            }

            $this->oferta->setId($_POST['id']);
            $resultado = $this->oferta->deletarOferta();

            if ($resultado) {
                echo json_encode([
                    "status" => "success",
                    "message" => "Oferta deletado com sucesso."
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Falha ao deletar o oferta."
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
    $controller = new OfertaController();

    switch ($action) {
        case 'listar':
            $controller->listarOferta();
            break;

        case 'salvar':
            $controller->salvarOferta();
            break;

        case 'deletar':
            $controller->deletarOferta();
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
