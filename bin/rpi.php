<?php
//variáveis e requires
$cfg=require __DIR__.'/../cfg.php';
$get=$cfg['inc_get'];
$url='https://www.radioprogresso.com.br/ultimas-noticias/';
$html=false;
$links=false;
//baixar html
$html=$get($url);
print '<pre>';
print htmlentities($html);
//extrair os links, títulos e capas das reportagens
//adicionar o bin/mig.php
//criar a tabela cronlog com o registro do hash do html baixado
//criar a tabela artigos
//adicionar o inc/db/db.php
//salvar no banco de dados
 ?>
