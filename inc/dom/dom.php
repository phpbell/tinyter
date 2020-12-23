<?php
return function($html,$selector='body'){
    libxml_use_internal_errors(true);//$dom->loadHTML($html)
    $dom = new DOMDocument;
    $dom->loadHTML($html);
    $xpath = new DOMXpath($dom);
    $xpathQuery = PhpCss::toXpath($selector);
    return $xpath->query($xpathQuery);
};
