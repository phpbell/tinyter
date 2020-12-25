<?php
return function($str){
    //esta url https://www.radioprogresso.com.br/wp-content/uploads/2020/03/Rodoviária-de-Ijui-novo-800x600.jpg
    //^retorna true no  filter_var($str, FILTER_VALIDATE_URL);
    //https://stackoverflow.com/a/5968861
    $regex="#^https?://.+#";
    if(preg_match($regex,$str)){
        return true;
    }else{
        return false;
    }
}
?>
