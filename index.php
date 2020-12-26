<?php
$cfg=require 'cfg.php';
$db=$cfg['inc_db']($cfg['site_medoo']);
$articles=$db->select('articles','*');
?>
<!DOCTYPE html>
<html lang="<?php print $cfg['site_language']; ?>" dir="ltr">
<head>
    <meta charset="utf-8">
    <title><?php print $cfg['site_name']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap.min.css" integrity="sha512-dhpxh4AzF050JM736FF+lLVybu28koEYRrSJtTKfA4Z7jKXJNQ5LcxKmHEwruFN2DuOAi9xeKROJ4Z+sttMjqw==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/css/bootstrap-responsive.min.css" integrity="sha512-S6hLYzz2hVBjcFOZkAOO+qEkytvbg2k9yZ1oO+zwXNYnQU71syCWhWtIk3UYDvUW2FCIwkzsTcwkEE58EZPnIQ==" crossorigin="anonymous" />
    <?php
    $cfg['inc_asset']('css/style.css');
    ?>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="span12 text-center">
                <h1><?php print $cfg['site_name']; ?></h1>
            </div>
            <!-- span12 -->
        </div>
        <!-- row -->
        <div class="row">
            <div class="span12">
                <ul class="nav nav-tabs nav-stacked">
                    <?php
                    if($articles){
                        foreach ($articles as $article) {
                            $data=$article['original_created_at'];
                            $data=date('d/M/Y',$data);
                            ?>
                            <li>
                                <a class="articleLink" href="<?php print $article['link']; ?>" title="Clique para abrir">
                                    <div class="articleThumb" style="background:url('<?php print $article['image_thumb']; ?>')">
                                    </div>
                                    <div class="articleMeta">
                                        <h4>
                                            <small>
                                                <?php print $data; ?>
                                            </small><br>
                                            <?php
                                            print htmlentities($article['title']);
                                            ?>
                                        </h4>
                                    </div>
                                </a>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <!-- container -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.3.2/js/bootstrap.min.js" integrity="sha512-28e47INXBDaAH0F91T8tup57lcH+iIqq9Fefp6/p+6cgF7RKnqIMSmZqZKceq7WWo9upYMBLMYyMsFq7zHGlug==" crossorigin="anonymous"></script>
    <?php
    $cfg['inc_asset']('js/main.js');
    ?>
</body>
</html>
