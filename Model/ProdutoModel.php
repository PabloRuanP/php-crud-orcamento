<?php
require_once(__DIR__ . '/../Core/conexao.php');

class Produto extends Connection
{

    private $id;
    private $codigo;
    private $descricao;
    private $preco;


    public function __construct()
    {
        parent::__construct();
    }

    /* Getters */
    public function getId()
    {
        return $this->id;
    }

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getPreco()
    {
        return $this->preco;
    }

    /* Setters */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function setPreco($preco)
    {
        $this->preco = $preco;
    }


    /* CRUD */
    public function listarProduto()
    {
        $sql = "SELECT * FROM produtos";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function salvarProduto()
    {
        $sql = "INSERT INTO produtos (codigo, descricao, preco) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $this->codigo);
        $stmt->bindValue(2, $this->descricao);
        $stmt->bindValue(3, $this->preco);
        return $stmt->execute();
    }

    public function atualizarProduto()
    {
        $sql = "UPDATE produtos SET  codigo = ?, descricao = ?, preco = ?  WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $this->codigo);
        $stmt->bindValue(2, $this->descricao);
        $stmt->bindValue(3, $this->preco);
        $stmt->bindValue(4, $this->id);
        return $stmt->execute();
    }

    public function deletarProduto()
    {
        $sql = "DELETE FROM produtos WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $this->id);
        return $stmt->execute();
    }
};