<?php
require_once(__DIR__ . '/../Core/conexao.php');

class Oferta extends Connection
{

    private $id;
    private $produto_id;
    private $quantidade_levar;
    private $quantidade_pagar;


    public function __construct()
    {
        parent::__construct();
    }

    /* Getters */
    public function getId()
    {
        return $this->id;
    }

    public function getProdutoId()
    {
        return $this->produto_id;
    }

    public function getQuantidadeLevar()
    {
        return $this->quantidade_levar;
    }

    public function getQuantidadePagar()
    {
        return $this->quantidade_pagar;
    }

    /* Setters */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setProdutoId($produto_id)
    {
        $this->produto_id = $produto_id;
    }

    public function setQuantidadeLevar($quantidade_levar)
    {
        $this->quantidade_levar = $quantidade_levar;
    }

    public function setQuantidadePagar($quantidade_pagar)
    {
        $this->quantidade_pagar = $quantidade_pagar;
    }


    /* CRUD */
    public function listarOferta()
    {
        $sql = "SELECT 
                ofertas.id, 
                ofertas.produto_id, 
                produtos.descricao AS produto, 
                ofertas.quantidade_levar AS leve, 
                ofertas.quantidade_pagar AS pague
                FROM ofertas
                INNER JOIN produtos ON produtos.id = ofertas.produto_id
                ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function salvarOferta()
    {
        $sql = "INSERT INTO ofertas (produto_id, quantidade_levar, quantidade_pagar) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $this->produto_id);
        $stmt->bindValue(2, $this->quantidade_levar);
        $stmt->bindValue(3, $this->quantidade_pagar);
        return $stmt->execute();
    }

    public function atualizarOferta()
    {
        $sql = "UPDATE ofertas SET  produto_id = ?, quantidade_levar = ?, quantidade_pagar = ?  WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $this->produto_id);
        $stmt->bindValue(2, $this->quantidade_levar);
        $stmt->bindValue(3, $this->quantidade_pagar);
        $stmt->bindValue(4, $this->id);
        return $stmt->execute();
    }

    public function deletarOferta()
    {
        $sql = "DELETE FROM ofertas WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $this->id);
        return $stmt->execute();
    }
};