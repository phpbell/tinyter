<?php
//variáveis e requires
$cfg=require __DIR__.'/../cfg.php';
$get=$cfg['inc_get'];
$url='https://www.radioprogresso.com.br/ultimas-noticias/';
$html=false;
$links=false;
$selector='.content .mobileM .c100';
$dom=$cfg['inc_dom'];
$mesesPT=[
    'janeiro',
    'fevereiro',
    'março',
    'abril',
    'maio',
    'junho',
    'julho',
    'agosto',
    'setembro',
    'outubro',
    'novembro',
    'dezembro'
];
$mesesEN=[
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
];
//exibir erros
$cfg['inc_errors']($cfg['site_errors']);
//baixar html
//$html=$get($url);
$html=file_get_contents(__DIR__.'/../test_html_rpi.txt');
print '<pre>';
//extrair os as datas, as capas, os links e o títulos
$tags=$dom($html,$selector);
$articles=false;
foreach ($tags as $tag) {
    $title=null;
    $created_at=null;
    $link=null;
    $image=null;
    foreach ($tag->childNodes as $node) {
        switch ($node->localName) {
            case 'span':
            $str=$node->textContent;
            $str=substr($str,0,-1);
            $str=str_replace(' de ', ' ',$str);
            $str=str_replace($mesesPT,$mesesEN,$str);
            date_default_timezone_set("America/Sao_Paulo");
            //https://www.php.net/manual/pt_BR/function.date.php
            $str=$str.' 15:00';//em gmt
            $parsed=date_parse_from_format('d F Y H:i', $str);
            $unix_time = gmmktime(
                $parsed['hour'],
                $parsed['minute'],
                $parsed['second'],
                $parsed['month'],
                $parsed['day'],
                $parsed['year']
            );
            //$created_at=date("r",$unix_time);
            $created_at=$unix_time;
            //converter a data
            break;
            case 'div':
            $class=$node->getAttributeNode("class")->value;
            if($class=='c100 imgfull'){
                $link=$node->childNodes{1}->getAttributeNode("href")->value;
                $str=$node->childNodes{1}->getAttributeNode("style")->value;
                $image=explode('\'',$str)[1];
            }
            if($class=='c100'){
                $title=trim($node->textContent).'<br>';
            }
        }
    }
    if(!is_null($title)){
        $articles[]=[
            'title'=>$title,
            'original_created_at'=>$created_at,
            'link'=>$link,
            'image'=>$image,
            'created_at'=>time()
        ];
    }
}
var_dump($articles);
//verificar se o md5 do html existe no db
//salvar o md5 do html no db
//fazer o parser do html (caso o md5 dele não exist no db)
//salvar os artigos no banco de dados (caso eles não existam)
//exibir os links
?>
