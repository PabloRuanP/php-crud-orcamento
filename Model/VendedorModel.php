<?php
require_once(__DIR__ . '/../Core/conexao.php');

class Vendedor extends Connection
{

    private $id;
    private $codigo;
    private $nome;


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

    public function getNome()
    {
        return $this->nome;
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

    public function setNome($nome)
    {
        $this->nome = $nome;
    }


    /* CRUD */
    public function listarVendedor()
    {
        $sql = "SELECT * FROM vendedores";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function salvarVendedor()
    {
        $sql = "INSERT INTO vendedores (codigo, nome) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $this->codigo);
        $stmt->bindValue(2, $this->nome);
        return $stmt->execute();
    }

    public function atualizarVendedor()
    {
        $sql = "UPDATE vendedores SET  codigo = ?, nome = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $this->codigo);
        $stmt->bindValue(2, $this->nome);
        $stmt->bindValue(3, $this->id);
        return $stmt->execute();
    }

    public function deletarVendedor()
    {
        $sql = "DELETE FROM vendedores WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(1, $this->id);
        return $stmt->execute();
    }
};