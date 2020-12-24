<?php
return function($str){
    return filter_var($str, FILTER_VALIDATE_URL);
}
?>
