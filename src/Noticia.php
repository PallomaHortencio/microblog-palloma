<?php
namespace Microblog;
use PDO, Exception;

final class Noticia{
    private int $id;
    private string $data;
    private string $titulo;
    private string $texto;
    private string $resumo;
    private string $imagem;
    private string $destaque;
    private int $categoriaId;

    /* Criando uma propreidade do tipo USUARIO, ou seja, a partir de uma classe que criamos com o objetivo de reutilizar recursos dela.
    
    Isto permitira fazer uma ASSOCIAÇÃO entre classes*/
    public Usuario $usuario;
  

    private PDO $conexao;

    public function __construct()
    {
        /* No momento em que um objeto Noticia for instanciado nas páginas, 
        aproveitaremoa para também instanciar um objeto Usuario e com isso acessar recursos desta classe */
        $this->usuario = new Usuario;
       

        /* Reapriveitando a conexão já existente a partir da classe de Usuario */
        $this->conexao = $this->usuario->getConexao();
       
    }

    public function inserir ():void {
        $sql = "INSERT INTO noticias(titulo, texto, resumo, imagem, destaque, usuarios_id, categorias_id)
        VALUES (:titulo, :texto, :resumo, :imagem, :destaque, :usuarios_id, :categorias_id)";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindParam(":titulo", $this->titulo, PDO::PARAM_STR);
            $consulta->bindParam(":texto", $this->texto, PDO::PARAM_STR);
            $consulta->bindParam(":resumo", $this->resumo, PDO::PARAM_STR);
            $consulta->bindParam(":imagem", $this->imagem, PDO::PARAM_STR);
            $consulta->bindParam(":destaque", $this->destaque, PDO::PARAM_STR);
            $consulta->bindParam(":categorias_id", $this->categoriaId, PDO::PARAM_INT);

            /* Aqui, primeiro chamamos o getter de ID a partir do objeto/classe de Usuario. E so depois atribuimos ele ao parâmetro :usuarios_id usando para isso o bindValue. 
            OBS: bindParam pode ser usado, mas há riscos de erro devido a forma como ele é executado pelo PHP. Por isso, recomenda-se o uso do bindValue em situações como essa. */
            $consulta->bindValue(":usuarios_id", $this->usuario->getId(), PDO::PARAM_INT);

            $consulta->execute();
        } catch (Exception $erro) {
            die("Erro: ". $erro->getMessage());
        }
    }



}