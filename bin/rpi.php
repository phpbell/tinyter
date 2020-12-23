<?php
//variáveis e requires
$cfg=require __DIR__.'/../cfg.php';
$get=$cfg['inc_get'];
$url='https://www.radioprogresso.com.br/ultimas-noticias/';
$html=false;
$links=false;
$selector='.content .mobileM .posts .c100';
$dom=$cfg['inc_dom'];
//baixar html
//$html=$get($url);
$html=file_get_contents("test_html_rpi.txt");
print '<pre>';
//extrair os links, títulos e capas das reportagens
var_dump($dom($html,$selector));
//adicionar o bin/mig.php
//criar a tabela cronlog com o registro do hash do html baixado
//criar a tabela artigos
//adicionar o inc/db/db.php
//salvar no banco de dados
 ?>
