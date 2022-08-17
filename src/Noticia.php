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
    private string $termo; 

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
        $sql = "INSERT INTO noticias(titulo, texto, resumo, imagem, destaque, usuario_id, categoria_id)
        VALUES (:titulo, :texto, :resumo, :imagem, :destaque, :usuario_id, :categoria_id)";

        try {
            $consulta = $this->conexao->prepare($sql);
            $consulta->bindParam(":titulo", $this->titulo, PDO::PARAM_STR);
            $consulta->bindParam(":texto", $this->texto, PDO::PARAM_STR);
            $consulta->bindParam(":resumo", $this->resumo, PDO::PARAM_STR);
            $consulta->bindParam(":imagem", $this->imagem, PDO::PARAM_STR);
            $consulta->bindParam(":destaque", $this->destaque, PDO::PARAM_STR);
            $consulta->bindParam(":categoria_id", $this->categoriaId, PDO::PARAM_INT);

            /* Aqui, primeiro chamamos o getter de ID a partir do objeto/classe de Usuario. E so depois atribuimos ele ao parâmetro :usuarios_id usando para isso o bindValue. 
            OBS: bindParam pode ser usado, mas há riscos de erro devido a forma como ele é executado pelo PHP. Por isso, recomenda-se o uso do bindValue em situações como essa. */
            $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);

            $consulta->execute();
        } catch (Exception $erro) {
            die("Erro: ". $erro->getMessage());
        }
    }


    public function upload(array $arquivo) {
        // Defininco os formatos aceitos
        $tiposValidos = [
            "image/png",
            "image/jpeg",
            "image/gif",
            "image/gif",
            "image/svg+xml"
        ];

        if( !in_array($arquivo['type'], $tiposValidos)) {
            die("
            <script>
            alert('Formato Inválido!')
            history.back();
            </script>
            ");
        }
           /*  } else {
                die("<script>alert('Formato Válido!');history.back();</script>");
            } */

            //Acessando apenas o nome do arquivo
            $nome = $arquivo['name'];

            // Acessando os dados de acesso temporário
            $temporario = $arquivo['tmp_name'];

            //Definindo a pasta de destino junto com o nome do arquivo
            $destino = "../imagem/".$nome;

            // Usamos a função abaixo para pegar da area temporaria 
            // e enviar para a pasta de destino (com o nome do arquivo)
            move_uploaded_file($temporario, $destino);
        }
        

        public function listar():array {
            /* Se o tipo de usuario for admin */
            if( $this->usuario->getTipo() === 'admin'){
                /* Então ele poderá acessar as noticias de todo mundo */
                $sql = "SELECT noticias.id, noticias.titulo, noticias.data, noticias.destaque, usuarios.nome AS autor FROM noticias LEFT JOIN usuarios 
                ON noticias.usuario_id = usuarios.id
                ORDER BY data DESC";
            } else {
                /* Senão (ou seja, um editor) este usuario (editor) poderá acessar SOMENTE suas próprias noticias */
                $sql = "SELECT id, titulo, data, destaque FROM noticias WHERE usuario_id = :usuario_id ORDER BY data DESC";
            }

            try {
                $consulta = $this->conexao->prepare($sql);
                /* se NÃO FOR um usuario admin, trate o parâmentro de usuario_id antes de executar*/
                if ($this->usuario->getTipo() !== 'admin') {
                    $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);
                }
                $consulta->execute();
                $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $erro) {
                die("Erro: ".$erro->getMessage());
            }
            return $resultado;
        } // final do listar


        public function listarUm():array {
            if( $this->usuario->getTipo() === 'admin'){
                $sql = "SELECT titulo, texto, resumo, imagem, usuario_id, categoria_id, destaque FROM noticias WHERE id = :id";
            } else {
                $sql = "SELECT titulo, texto, resumo, imagem, usuario_id, categoria_id, destaque FROM noticias WHERE id = :id AND usuario_id = :usuario_id";
            }

            try {
                $consulta = $this->conexao->prepare($sql);

                /* parametro id da noticia (para todos os usuarios) */
                $consulta->bindParam(":id", $this->id, PDO::PARAM_INT);

                if ($this->usuario->getTipo() !== 'admin') {

                    /* parametro usuario_id */
                    $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);

                }
                
                $consulta->execute();
                $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $erro) {
                die("Erro: ".$erro->getMessage());
            }
            return $resultado;
        } 


        public function atualizar():void {
            if( $this->usuario->getTipo() === 'admin'){
                $sql = "UPDATE noticias SET titulo = :titulo, texto = :texto, resumo = :resumo, imagem = :imagem, categoria_id = :categoria_id, destaque = :destaque 
                WHERE id = :id";
            } else {
                $sql = "UPDATE noticias SET titulo = :titulo, texto = :texto, resumo = :resumo, imagem = :imagem, categoria_id = :categoria_id, destaque = :destaque 
                WHERE id = :id, usuario_id = :usuario_id";
            }

            try {
                $consulta = $this->conexao->prepare($sql);
                $consulta->bindParam(":id", $this->id, PDO::PARAM_INT);
                $consulta->bindParam(":titulo", $this->titulo, PDO::PARAM_STR);
                $consulta->bindParam(":texto", $this->texto, PDO::PARAM_STR);                
                $consulta->bindParam(":resumo", $this->resumo, PDO::PARAM_STR);
                $consulta->bindParam(":imagem", $this->imagem, PDO::PARAM_STR);
                $consulta->bindParam(":categoria_id", $this->categoriaId, PDO::PARAM_INT);
                $consulta->bindParam(":destaque", $this->destaque, PDO::PARAM_STR);

                /* parametro id da noticia (para todos os usuarios) */
                $consulta->bindParam(":id", $this->id, PDO::PARAM_INT);

                if ($this->usuario->getTipo() !== 'admin') {

                    /* parametro usuario_id */
                    $consulta->bindValue(":usuario_id", $this->usuario->getId(), PDO::PARAM_INT);

                }
                
                $consulta->execute();
            } catch (Exception $erro) {
                die("Erro: ".$erro->getMessage());
            }
        } 


        public function excluir():void{
            $sql = "DELETE FROM noticias WHERE id = :id";

            try {
                $consulta = $this->conexao->prepare($sql);
                $consulta->bindParam(":id", $this->id, PDO::PARAM_INT);
                $consulta->execute();
            } catch (Exception $erro) {
                die("Erro: ". $erro->getMessage());
            }
        }


        /* Métodos para área pública do site */
        public function listarDestaques():array {
            $sql = "SELECT titulo, imagem, resumo, id FROM noticias WHERE destaque = :destaque ORDER BY data DESC";

            try {
                $consulta = $this->conexao->prepare($sql);
                $consulta->bindParam(":destaque", $this->destaque, PDO::PARAM_STR);
                $consulta->execute();
                $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $erro){
                die("Erro: ".$erro->getMessage());
            }
            return $resultado;
        }


        public function listarTodas():array {
            $sql = "SELECT data, titulo, resumo, id FROM noticias ORDER BY data DESC";

            try {
                $consulta = $this->conexao->prepare($sql);
                $consulta->execute();
                $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $erro){
                die("Erro: ".$erro->getMessage());
            }
            return $resultado;
        }


        public function listarDetalhes():array {
    
           $sql = "SELECT noticias.id, noticias.titulo, noticias.data, noticias.imagem, noticias.texto, usuarios.nome AS autor FROM noticias LEFT JOIN usuarios 
                ON noticias.usuario_id = usuarios.id WHERE noticias.id = :id";

            try {
                $consulta = $this->conexao->prepare($sql);
                $consulta->bindParam(":id", $this->id, PDO::PARAM_INT);
                $consulta->execute();
                $resultado = $consulta->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $erro){
                die("Erro: ".$erro->getMessage());
            }
            return $resultado;
        }


        public function listarPorCategoria():array {
            $sql = "SELECT noticias.id, noticias.titulo, noticias.data, noticias.resumo, usuarios.nome AS autor, categorias.nome AS categoria FROM noticias LEFT JOIN usuarios ON noticias.usuario_id = usuarios.id INNER JOIN categorias ON noticias.categoria_id = categorias.id WHERE noticias.categoria_id = :categoria_id";

            try {
                $consulta = $this->conexao->prepare($sql);
                $consulta->bindParam(":categoria_id", $this->categoriaId, PDO::PARAM_INT);
                $consulta->execute();
                $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $erro){
                die("Erro: ".$erro->getMessage());
            }
            return $resultado;
        }


        public function busca():array {
            $sql = "SELECT titulo, data, resumo, id FROM noticias WHERE titulo LIKE :termo OR texto LIKE :termo OR resumo LIKE :termo ORDER BY data DESC ";
            
            try {
                $consulta = $this->conexao->prepare($sql);
                $consulta->bindValue(":termo", '%'.$this->termo.'%', PDO::PARAM_STR);
                $consulta->execute();
                $resultado = $consulta->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $erro){
                die("Erro: ".$erro->getMessage());
            }
            return $resultado;
        }

     


 
    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): self
    {
        $this->id = filter_var($id, FILTER_SANITIZE_SPECIAL_CHARS);

        return $this;
    }



    public function getTitulo(): string
    {
        return $this->titulo;
    }


    public function setTitulo(string $titulo): self
    {
        $this->titulo = filter_var($titulo, FILTER_SANITIZE_SPECIAL_CHARS);

        return $this;
    }


    public function getTexto(): string
    {
        return $this->texto;
    }


    public function setTexto(string $texto): self
    {
        $this->texto = filter_var($texto, FILTER_SANITIZE_SPECIAL_CHARS);

        return $this;
    }


    public function getResumo(): string
    {
        return $this->resumo;
    }


    public function setResumo(string $resumo): self
    {
        $this->resumo = filter_var($resumo, FILTER_SANITIZE_SPECIAL_CHARS);

        return $this;
    }


    public function getImagem(): string
    {
        return $this->imagem;
    }


    public function setImagem(string $imagem): self
    {
        $this->imagem = filter_var($imagem, FILTER_SANITIZE_SPECIAL_CHARS);

        return $this;
    }


    public function getDestaque(): string
    {
        return $this->destaque;
    }


    public function setDestaque(string $destaque): self
    {
        $this->destaque = filter_var($destaque, FILTER_SANITIZE_SPECIAL_CHARS);

        return $this;
    }


    public function getCategoriaId(): int
    {
        return $this->categoriaId;
    }


    public function setCategoriaId(int $categoriaId): self
    {
        $this->categoriaId = filter_var($categoriaId, FILTER_SANITIZE_NUMBER_INT);

        return $this;
    }


    public function getTermo()
    {
        return $this->termo;
    }


    public function setTermo($termo)
    {
        $this->termo = $termo;

        return $this;
    }
}