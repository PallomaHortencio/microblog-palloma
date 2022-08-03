<?php

use Microblog\Categorias;
use Microblog\ControleDeAcesso;

require_once "../vendor/autoload.php";

$sessao = new ControleDeAcesso;
$sessao->verificaAcesso();
$sessao->verificaAcessoAdmin();

$categorias = new Categorias;
$categorias->setId($_GET['id']);
$categorias->excluir();

header("location:categorias.php");

?>