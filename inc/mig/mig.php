<?php
return function($db,$tableFolder){
    system('clear');
    print 'migrando tabelas...'.PHP_EOL;
    //definir os variáveis de entrada e variaveis iniciais
    $type=$db->info()['driver'];
    $filename=$tableFolder;
    $ignored=array('.', '..', '.svn', '.htaccess');
    $migrations=[];
    $migrationsCols=[];
    $tables=[];
    $tablesCols=[];
    $apagarCols=[];
    //validar os dados das variveis de entrada
    if(!file_exists($filename)){
        die('folder table not found');
    }
    //transformar os dados de entrada em dados de saída
    //pegar o nome das migrations
    foreach (scandir($filename) as $key => $value) {
        if (in_array($value, $ignored)) {
            continue;
        }
        $migrations[] = $value;
    }
    //pegar o nome das tabelas existentes
    if($type=='sqlite'){
        $sql='SELECT name FROM sqlite_master WHERE type="table";';
    }else{
        $sql='SHOW TABLES';
    }
    $arr=$db->query($sql)->fetchAll();
    if (is_array($arr)) {
        foreach ($arr as $key => $value) {
            $tables[]=array_values($value)[0];
        }
    }
    //comparar o nome das migrations com o das tabelas
    $apagarTabelas=array_diff($tables,$migrations);
    //dropar tabelas que não existem nas migrations
    foreach($apagarTabelas as $key=>$value){
        $db->drop($value);
        print 'tabela '.$value.' excluida'.PHP_EOL;
    }
    //pegar as colunas das migrations
    foreach ($migrations as $key => $value) {
        $filename=$tableFolder.'/'.$value;
        $str=file_get_contents($filename);
        $str=trim($str);
        $arr=explode(PHP_EOL,$str);
        $migrationsCols[$value]=array_values($arr);
    }
    //pegar as colunas das tabelas
    $arr=[];
    foreach ($tables as $key => $value) {
        if($type=='sqlite'){
            $sql='PRAGMA table_info('.$value.');';
        }else{
            $sql='SHOW COLUMNS FROM '.$value;
        }
        $arr[$value]=$db->query($sql)->fetchAll();
    }
    foreach ($arr as $key => $value) {
        foreach ($value as $keyX => $valueX) {
            if($type=='sqlite'){
                $tablesCols[$key][]=$valueX['name'];
            }else{
                $tablesCols[$key][]=$valueX['Field'];
            }
        }
    }
    //comparar as colunas das migrations com a das tabelas
    foreach ($tablesCols as $key => $value) {
        $arr=@array_diff(
            $value,
            $migrationsCols[$key]
        );
        if($arr){
            $apagarCols[$key]=$arr;
        }
    }
    //dropar colunas que só existem nas tabelas
    foreach ($apagarCols as $tableName => $value) {
        foreach ($value as $keyX => $columnName) {
            if($type=='sqlite'){
                $drop_sqlite_column=require __DIR__.'/drop_sqlite_column.php';
                $drop_sqlite_column($tableName,$columnName);
            }else{
                $sql='ALTER TABLE ';
                $sql.=$tableName.' DROP COLUMN '.$columnName;
                $db->query($sql);
            }
            $str='coluna "'.$columnName;
            $str.='" da tabela "'.$tableName;
            $str.='" excluida'.PHP_EOL;
            print $str;
        }
    }
    //criar tabelas que não existem
    $criarTabelas=array_diff($migrations,$tables);
    foreach ($criarTabelas as $key => $tableName) {
        if($type=='sqlite'){
            $sql='CREATE TABLE IF NOT EXISTS `'.$tableName;
            $sql.='`(id INTEGER PRIMARY KEY AUTOINCREMENT);';
        }else{
            $sql='CREATE TABLE IF NOT EXISTS `'.$tableName;
            $sql.='`(id serial) ENGINE=INNODB;';
        }
        $db->query($sql);
    }
    //criar colunas que não existem
    foreach($migrationsCols as $tableName=>$cols){
        foreach ($cols as $key => $columnName) {
            //alterar colunas que existem (apenas no mysql)
            if(
                isset($tablesCols[$tableName]) AND
                in_array($columnName,$tablesCols[$tableName]) AND
                $type!='sqlite'
            ){
                $sql='ALTER TABLE `'.$tableName.'`';
                $sql.=' CHANGE `'.$columnName.'`';
                if($columnName=='id'){
                    $sql.=' `'.$columnName.'` SERIAL NOT NULL;';
                }else{
                    $sql.=' `'.$columnName.'` LONGTEXT NULL;';
                }
            }else{
                $sql='';
            }
            //adicionar colunas que não existem
            if($type=='sqlite'){
                $sql.='ALTER TABLE `'.$tableName.'` ADD ';
                if ($columnName=='id') {
                    $sql.='`'.$columnName.'` ';
                    $sql.='INTEGER PRIMARY KEY AUTOINCREMENT;';
                } else {
                    $sql.='`'.$columnName.'` ';
                    $sql.='TEXT;';
                }
            }else{
                $sql.='ALTER TABLE `'.$tableName.'` ADD ';
                if ($columnName=='id') {
                    $sql.='`'.$columnName.'` SERIAL;';
                } else {
                    $sql.='`'.$columnName.'` LONGTEXT;';//4GiB
                }
            }
            $db->query($sql);
        }
        print 'tabela "'.$tableName.'" ok'.PHP_EOL;
    }
    print 'migração concluída'.PHP_EOL;
};
