<?php
return function($url,$data=false){
    $ch = curl_init();
    if(isset($data['data'])){
        $url='?'.http_build_query($data['data']);
    }
    if(isset($data['header'])){
        curl_setopt ($ch , CURLOPT_HTTPHEADER, $data['header']);
    }
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    if(isset($data['ua'])){
        curl_setopt($ch, CURLOPT_USERAGENT,$data['ua']);
    }
    $result= curl_exec ($ch);
    curl_close ($ch);
    if($result){
        return $result;
    }else{
        return false;
    }
};
?>
