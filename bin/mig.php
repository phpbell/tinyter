<?php
//variáveis básicas
$cfg=require __DIR__.'/../cfg.php';
$errors=require __DIR__.'/../inc/errors/errors.php';
//exibir erros
$cfg['inc_errors']($cfg['site_errors']);
$db=$cfg['inc_db'];
$db=$db($cfg['site_medoo']);
//regras
$tableFolder=__DIR__.'/table';
$mig=$cfg['inc_mig'];
$mig($db,$tableFolder);
