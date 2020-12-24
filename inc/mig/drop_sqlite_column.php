<?php
return function($tableName,$columnName){
    global $fn;
    $db=$fn('db');
    $columns = null;
    $options=null;
    //pega o nome das colunas da tabela antiga
    $colsRAW=$db->query("PRAGMA table_info($tableName);")->fetchAll();
    foreach($colsRAW as $col){
        if($col['name']!=$columnName){
            $columns[]=$col['name'];
            if($col['name']=='id'){
                $options[$col['name']]=[
		            "INT",
		            "AUTO_INCREMENT"
                ];
            }else{
                $options[$col['name']]=[
		            "TEXT"
                ];
            }
        }
    }
    //criar tabela temporária sem a coluna a ser eliminada
    $tmpTable='tmp_'.$tableName;
    $db->create($tmpTable, $columns, $options);
    //inserir dados da tabela antiga na tabela temporária
    $values=$db->select($tableName,$columns);
    $db->insert($tmpTable,$values);
    //apagar a tabela antiga
    $db->drop($tableName);
    //renomear a tabela temporária
    $sql="ALTER TABLE `$tmpTable` RENAME TO `$tableName`;";
    $db->query($sql);
};
