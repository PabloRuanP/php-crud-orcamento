<?php
require_once '../Model/OrcamentoModel.php';

class RelatorioController
{
    private $orcamento;

    public function __construct()
    {
        $this->orcamento = new Orcamento();
    }

    public function gerarRelatorio()
    {
        $data_inicio = $_GET['data_inicio'] ?? null;
        $data_fim = $_GET['data_fim'] ?? null;
        $produto_id = $_GET['produto_id'] ?? null;

        if (!$data_inicio || !$data_fim) {
            echo json_encode([
                "status" => "error",
                "message" => "Período é obrigatório."
            ]);
            exit;
        }

        $dados = $this->orcamento->relatorioOrcamentos($data_inicio, $data_fim, $produto_id);

        echo json_encode([
            "status" => "success",
            "data" => $dados
        ]);
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'relatorio') {
    $controller = new RelatorioController();
    $controller->gerarRelatorio();
};