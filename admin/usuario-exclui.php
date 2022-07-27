<?php

use Microblog\ControleDeAcesso;
use Microblog\Usuario;

require_once "../vendor/autoload.php";

$sessao = new ControleDeAcesso;
$sessao->verificaAcesso();


// criamos um objeto para poder acessar os recursos da classe
$usuario = new Usuario; // não esqueça do autoload e de namespace

// obtemos o id da url e o passamos para o setter
$usuario->setId($_GET['id']);

// só então executamos o método de exclusão
$usuario->excluir();

// após excluir, redirecionamos para a página de lista de usuários
header("location:usuarios.php");

?>