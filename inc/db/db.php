<?php
return function($configMedoo){
    require 'vendor/autoload.php';
    return new Medoo\Medoo($configMedoo);
};
?>
