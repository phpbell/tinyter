<?php
return function($urls,$autoIndent=true){
    if(is_string($urls)){
        $arr[]=$urls;
        $urls=$arr;
    }
    foreach ($urls as $key=>$url) {
        $filename=__DIR__.'/../../'.$url;
        $path_parts = pathinfo($url);
        $ext=$path_parts['extension'];
        if(file_exists($filename)){
            $md5=md5_file($filename);
            $url=$url."?$md5";
            if($autoIndent AND $key<>0){
                print '    ';
            }
            if($ext=='css'){
                print '<link rel="stylesheet" href="'.$url.'" />';
            }
            if($ext=='js'){
                print '<script src="'.$url.'"></script>';
            }
            print PHP_EOL;
        }
    }
};
