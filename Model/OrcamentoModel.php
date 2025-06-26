<?php
require_once(__DIR__ . '/../Core/conexao.php');

class Orcamento extends Connection
{

    private $id;
    private $cliente_id;
    private $vendedor_id;


    public function __construct()
    {
        parent::__construct();
    }

    /* Getters */
    public function getId()
    {
        return $this->id;
    }

    public function getClienteId()
    {
        return $this->cliente_id;
    }

    public function getVendedorId()
    {
        return $this->vendedor_id;
    }

    /* Setters */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setClienteId($cliente_id)
    {
        $this->cliente_id = $cliente_id;
    }

    public function setVendedorId($vendedor_id)
    {
        $this->vendedor_id = $vendedor_id;
    }


    /* CRUD */
    public function listarOrcamento()
    {
        try {
            $sql = "SELECT 
                o.id,
                o.data_orcamento,
                c.id AS cliente_id,
                c.nome AS cliente,
                v.id AS vendedor_id,
                v.nome AS vendedor
                FROM orcamentos o
                INNER JOIN clientes c ON c.id = o.cliente_id
                INNER JOIN vendedores v ON v.id = o.vendedor_id
                ORDER BY o.data_orcamento DESC
                ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Erro ao listar orçamento: ' . $e->getMessage());
        }
    }

    public function buscarItensPorOrcamento($orcamentoId)
    {
        try {
            $sql = "SELECT 
                    oi.*, 
                    p.descricao 
                FROM orcamento_itens oi
                INNER JOIN produtos p ON p.id = oi.produto_id
                WHERE oi.orcamento_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orcamentoId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception('Erro ao buscar orçamento: ' . $e->getMessage());
        }
    }

    public function salvarOrcamento($itens = [])
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO orcamentos (cliente_id, vendedor_id, data_orcamento) VALUES (?, ?, NOW())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, $this->cliente_id);
            $stmt->bindValue(2, $this->vendedor_id);
            $stmt->execute();
            $orcamentoId = $this->pdo->lastInsertId();

            foreach ($itens as $item) {
                $produto_id = $item['produto_id'];
                $quantidade = $item['quantidade'];
                $preco_unitario = $item['preco_unitario'];


                $oferta = $this->aplicarOferta($produto_id, $quantidade, $preco_unitario);

                $sqlItem = "INSERT INTO orcamento_itens 
                (orcamento_id, produto_id, quantidade, preco_unitario, desconto, preco_total) 
                VALUES (?, ?, ?, ?, ?, ?)";
                $stmtItem = $this->pdo->prepare($sqlItem);
                $stmtItem->bindValue(1, $orcamentoId);
                $stmtItem->bindValue(2, $produto_id);
                $stmtItem->bindValue(3, $quantidade);
                $stmtItem->bindValue(4, $preco_unitario);
                $stmtItem->bindValue(5, $oferta['desconto']);
                $stmtItem->bindValue(6, $oferta['preco_total']);
                $stmtItem->execute();
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception('Erro ao salvar orçamento: ' . $e->getMessage());
        }
    }

    public function atualizarOrcamento($itens = [])
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "UPDATE orcamentos SET cliente_id = ?, vendedor_id = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$this->cliente_id, $this->vendedor_id, $this->id]);

            $sqlDelete = "DELETE FROM orcamento_itens WHERE orcamento_id = ?";
            $stmtDelete = $this->pdo->prepare($sqlDelete);
            $stmtDelete->execute([$this->id]);

            foreach ($itens as $item) {
                $produto_id = $item['produto_id'];
                $quantidade = $item['quantidade'];
                $preco_unitario = $item['preco_unitario'];

                $oferta = $this->aplicarOferta($produto_id, $quantidade, $preco_unitario);

                $sqlItem = "INSERT INTO orcamento_itens 
                (orcamento_id, produto_id, quantidade, preco_unitario, desconto, preco_total) 
                VALUES (?, ?, ?, ?, ?, ?)";
                $stmtItem = $this->pdo->prepare($sqlItem);
                $stmtItem->bindValue(1, $this->id);
                $stmtItem->bindValue(2, $produto_id);
                $stmtItem->bindValue(3, $quantidade);
                $stmtItem->bindValue(4, $preco_unitario);
                $stmtItem->bindValue(5, $oferta['desconto']);
                $stmtItem->bindValue(6, $oferta['preco_total']);
                $stmtItem->execute();
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw new Exception('Erro ao atualizar orçamento: ' . $e->getMessage());
        }
    }

    public function deletarOrcamento()
    {
        try {
            $sqlItens = "DELETE FROM orcamento_itens WHERE orcamento_id = ?";
            $stmtItens = $this->pdo->prepare($sqlItens);
            $stmtItens->bindValue(1, $this->id);
            $stmtItens->execute();

            $sqlOrcamento = "DELETE FROM orcamentos WHERE id = ?";
            $stmtOrcamento = $this->pdo->prepare($sqlOrcamento);
            $stmtOrcamento->bindValue(1, $this->id);
            $stmtOrcamento->execute();

            return true;
        } catch (Exception $e) {
            throw new Exception('Erro ao deletar orçamento: ' . $e->getMessage());
        }
    }

    private function aplicarOferta($produto_id, $quantidade, $preco_unitario)
    {
        $sql = "SELECT quantidade_levar, quantidade_pagar 
            FROM ofertas 
            WHERE produto_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$produto_id]);
        $oferta = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($oferta) {
            $qtd_levar = (int)$oferta['quantidade_levar'];
            $qtd_pagar = (int)$oferta['quantidade_pagar'];

            $grupos = intdiv($quantidade, $qtd_levar);
            $itens_com_desconto = $grupos * ($qtd_levar - $qtd_pagar);

            $itens_a_pagar = $quantidade - $itens_com_desconto;

            $preco_total = $itens_a_pagar * $preco_unitario;
            $desconto = ($quantidade * $preco_unitario) - $preco_total;

            return [
                'preco_total' => $preco_total,
                'desconto' => $desconto
            ];
        } else {
            return [
                'preco_total' => $quantidade * $preco_unitario,
                'desconto' => 0
            ];
        }
    }

    public function relatorioOrcamentos($dataInicio, $dataFim, $produtoId = null)
    {
        $params = [];
        $sql = "
            SELECT 
                o.id as orcamento_id,
                o.data_orcamento,
                c.nome AS cliente,
                v.nome AS vendedor,
                p.descricao AS produto,
                oi.quantidade,
                oi.preco_unitario,
                oi.desconto,
                oi.preco_total
            FROM orcamentos o
            INNER JOIN clientes c ON c.id = o.cliente_id
            INNER JOIN vendedores v ON v.id = o.vendedor_id
            INNER JOIN orcamento_itens oi ON oi.orcamento_id = o.id
            INNER JOIN produtos p ON p.id = oi.produto_id
            WHERE o.data_orcamento BETWEEN ? AND ?
        ";

        $params[] = $dataInicio . ' 00:00:00';
        $params[] = $dataFim . ' 23:59:59';

        if ($produtoId) {
            $sql .= " AND p.id = ?";
            $params[] = $produtoId;
        }

        $sql .= " ORDER BY o.data_orcamento DESC, o.id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarOrcamentoPorId($id)
    {
        $sql = "SELECT 
                o.id,
                o.data_orcamento,
                c.id AS cliente_id,
                c.nome AS cliente,
                v.id AS vendedor_id,
                v.nome AS vendedor
            FROM orcamentos o
            INNER JOIN clientes c ON c.id = o.cliente_id
            INNER JOIN vendedores v ON v.id = o.vendedor_id
            WHERE o.id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
};