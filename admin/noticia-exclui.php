<?php

use Microblog\ControleDeAcesso;
use Microblog\Noticia;

require_once "../vendor/autoload.php";

$sessao = new ControleDeAcesso;
$sessao->verificaAcesso();

$noticia = new Noticia;
$noticia->setId($_GET['id']);
$noticia->excluir();

header("location:noticias.php");