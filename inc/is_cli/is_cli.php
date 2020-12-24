<?php
return function(){
    if (php_sapi_name() == "cli") {
        return true;
    } else{
        return false;
    }

};
