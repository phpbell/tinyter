<?php
//variáveis e requires
$cfg=require __DIR__.'/../cfg.php';
$is_cli=$cfg['inc_is_cli'];
$is_url=$cfg['inc_is_url'];
$get=$cfg['inc_get'];
$db=$cfg['inc_db']($cfg['site_medoo']);
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
$articles=false;
$html=$get($url);
$md5=md5($html);

//modo
if($is_cli()){
    system('clear');
}else{
    print '<pre>';
}

//exibir erros
$cfg['inc_errors']($cfg['site_errors']);

//verificar se o md5 do html existe no db
$where=[
    'md5'=>$md5
];
if($db->has('html_hash',$where)){
    print 'essa página html já foi processada antes'.PHP_EOL;
}else{
    //salvar o md5 do html no db
    $data=[
        'md5'=>$md5,
        'created_at'=>time()
    ];
    $db->insert("html_hash",$data);
    //fazer o parser do html e extrair os as datas, as capas, os links e o títulos
    $tags=$dom($html,$selector);
    foreach ($tags as $tag) {
        $title=null;
        $created_at=null;
        $link=null;
        $image=null;
        $image_thumb=null;
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
                    if(!$is_url($link)){
                        $link=null;
                    }
                    $str=$node->childNodes{1}->getAttributeNode("style")->value;
                    $image_url=explode('\'',$str)[1];
                    $image_url=trim($image_url);
                    $image=$image_url;
                    if($is_url($image_url)){
                        //baixar e salvar thumbs em 100x100
                        $imageObj=$cfg['inc_image']();
                        $image_str=$get($image_url);
                        $image_md5=md5($image_str);
                        $image_thumb='image/thumb/'.$image_md5.'.jpg';
                        $filename=__DIR__.'/../'.$image_thumb;
                        if(file_exists($filename)){
                            print 'a thumb já existe no disco'.PHP_EOL;
                        }else{
                            $temp_file=$cfg['inc_tmp_file']($image_str);
                            if(exif_imagetype($temp_file)) {
                                $imageObj->fromFile($temp_file);
                                $imageObj->thumbnail(100,100,'top');
                                $imageObj->toFile($filename,'image/jpeg',75);
                                print 'thumb salva no disco'.PHP_EOL;
                            }else{
                                print $temp_file.' não é uma imagem'.PHP_EOL;
                                print $image_url.' não é imagem'.PHP_EOL;
                            }
                        }
                    }
                }
                if($class=='c100'){
                    $title=strip_tags(trim($node->textContent));
                }
            }
        }
        if(!is_null($title) AND !is_null($link)){
            $articles[]=[
                'title'=>$title,
                'original_created_at'=>$created_at,
                'link'=>$link,
                'image'=>$image,
                'created_at'=>time(),
                'image_thumb'=>$image_thumb
            ];
        }
    }
}
if($articles){
    //salvar o host no banco caso ele não exista
    $host=parse_url($articles[0]['link'])['host'];
    $where=[
        'host'=>$host
    ];
    $host_id=$db->get('hosts','id',$where);
    if(!$host_id){
        $data=[
            'host'=>$host,
            'created_at'=>time()
        ];
        if($db->insert('hosts',$data)){
            $host_id=$db->id();
        }else{
            die("erro ao tentar salvar o host no db".PHP_EOL);
        }
    }
    foreach ($articles as $article) {
        $article['host_id']=$host_id;
        $where=[
            'link'=>$article['link']
        ];
        $old_article_id=$db->get('articles','id',$where);
        if($old_article_id){
            print 'artigo '.$old_article_id.' já existe no db'.PHP_EOL;
        }else{
            //salvar os artigos no banco de dados
            if($db->insert('articles',$article)){
                print 'artigo '.$db->id().' adicionado ao db'.PHP_EOL;
            }else{
                die('erro ao tentar gravar o link no db'.PHP_EOL);
            }
        }
    }
}else{
    print 'nenhum artigo adicionado'.PHP_EOL;
}
?>
