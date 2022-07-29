<?php
namespace Microblog;

final class ControleDeAcesso{

    public function __construct()
    {
        /* Se NÃO EXISTIR uma sessão em funcionamento */
        if( !isset($_SESSION) ){
            // Então iniciamos a sessão
            session_start();
        }
    }


    public function verificaAcesso():void{
        /* Se NÃO EXISTIR uma variável de sessão relacionada ao id do usuário logado */
        if( !isset($_SESSION['id'])){
            /* Então significa que o usuário não esta logado, portanto apague qualquer resquício de sessão e force o usuário a ir para login.php */
            session_destroy();
            header("location:../login.php?acesso_proibido");
            die(); // tambem pode se encontrar como exit;
        }
    }


    public function login(int $id, string $nome, string $tipo ):void {
        /* No momento em que ocorrer o login, adcionamos a sessão variáveis de sessão contendo os dados necessários para o sistema */
        $_SESSION['id'] = $id;
        $_SESSION['nome'] = $nome;
        $_SESSION['tipo'] = $tipo;
    }

    public function logout():void{
        session_start();
        session_destroy();
        header("location:../login.php?logout");
        die(); //exit
    }

    public function verificaAcessoAdmin(){
        if($_SESSION['tipo'] !== 'admin') {
            header("location:nao-autorizado.php");
        }
    }


}