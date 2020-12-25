<?php
return function($data=null){
    $filename=stream_get_meta_data(tmpfile())['uri'];
    if(!is_null($data)){
        file_put_contents($filename,$data);
    }
    return $filename;
};
?>
