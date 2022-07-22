<?php
namespace Microblog;
use PDO, Exception;

final class Usuario {
    private int $id;
    private string $nome;
    private string $email;
    private string $senha;
    private string $tipo;
    private PDO $conexao;


    public function __construct()
    {
        $this->conexao = Banco::conecta();
    }


    public function listar(){
        $sql = "SELECT id, nome, email, tipo FROM usuarios ORDER BY nome";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->execute();
            $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $erro) {
            die("Erro: ". $erro->getMessage());
        }
        return $resultado;
    }

    // fetchAll seleciona todos
    // fetch seleciona um só 


























    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    

    public function getNome(): string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }


  
    public function getEmail(): string
    {
        return $this->email;
    }
 
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    

    public function getSenha(): string
    {
        return $this->senha;
    }

    public function setSenha(string $senha): self
    {
        $this->senha = $senha;

        return $this;
    }


    
    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }


    
    public function getConexao(): PDO
    {
        return $this->conexao;
    }

    public function setConexao(PDO $conexao): self
    {
        $this->conexao = $conexao;

        return $this;
    }
}