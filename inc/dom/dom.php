<?php
//Interface Element:
//https://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226/DOM3-Core.html#core-ID-745549614

//Interface Node:
//https://www.w3.org/TR/2003/WD-DOM-Level-3-Core-20030226/DOM3-Core.html#core-ID-1950641247

return function($html,$selector='body'){
    require __DIR__.'/vendor/autoload.php';
    libxml_use_internal_errors(true);//ocultar mensagens de erro
    $dom = new DOMDocument;
    $dom->loadHTML($html);
    $xpath = new DOMXpath($dom);
    $xpathQuery = PhpCss::toXpath($selector);
    return $xpath->query($xpathQuery);
};
