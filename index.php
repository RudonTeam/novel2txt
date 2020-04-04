<?php

    session_start();

    function a ($v) {
        header('Content-Type: text/css; charset=utf-8');
        print_r($v);
        die();
    }

    $http_protocol_pre = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='off')?'https://':'http://';
    $base_url = trim(str_replace('index.php', '', $http_protocol_pre.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']),'/').'/';
    $base_path_top = dirname(__FILE__).'/';
    $base_path_sites = $base_path_top . 'sites/';
    
    $supported_sites = array(
        'biquge.tw' => array(
            'url' => 'https://www.biquge.tw/',
            'name' => '笔趣阁'
        ),
        'xinxs.la' => array(
            'url' => 'https://www.xinxs.la/',
            'name' => '笔趣阁'
        ),
        'biqukan.com' => array(
            'url' => 'https://www.biqukan.com/',
            'name' => '笔趣看'
        ),
    );
    
    if(isset($_POST['url'])){
        $u = $_POST['url'];
        $domain = parse_url($u, PHP_URL_HOST);
        $domain = strtolower(str_replace('www.', '', $domain));
        if(!is_dir($base_path_sites.$domain.'/')){
            die("抱歉，网站{$domain}暂未支持文本转换。请尝试：<a href='https://www.biquge.tw/' target='_blank'>笔趣阁.tw</a>");
        }
        
        /* Go */
        $_SESSION['url'] = $u;
        $_SESSION['site'] = $domain;
        
        $goto = $base_url . 'sites/'.$domain.'/index.php';
        header('Location:'.$goto);
        die();
    }
    
    
    //a($u);

?>
<html>
    <head>
        <title>小说TXT生成器</title>
        <meta charset="utf-8"/>
        <link href="https://libs.baidu.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script>
        <script src="https://libs.baidu.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
        <style type="text/css">
            input::-webkit-input-placeholder {
                color: #CDCDCD;
            }
            input::-moz-input-placeholder {
                color: #CDCDCD;
            }
            input::-ms-input-placeholder {
                color: #CDCDCD;
            }
        </style>
    </head>
    <body style="padding: 30px 0; text-align: center;">
        <form action="" method="post">
            <h3 style="margin-bottom: 20px; cursor: default;">
                小说TXT生成器
            </h3>
            <label for="url">
                <span>小说目录大纲网址：</span>
                <input type="text" id="url" name="url" required="required" placeholder=" https://www.biquge.tw/425_425345/" style="width: 300px;"/>
            </label>

            <input type="submit" value="开始分析" style="margin-left: 50px;"/>
        </form>
        
        <div style="margin-top: 80px;cursor: default;">
            目前支持的小说网站：
            <?php 
            foreach ($supported_sites as $d => $info) {
                echo <<<ABC
                
            <div>
                <a href="{$info['url']}" target="_blank">
                    {$info['name']} {$d}
                </a>
            </div>

ABC;
            }
            ?>
            <div>
                
            </div>
        </div>
        
        <script type="text/javascript">
            $(document).ready(function(){
                $('#url').focus();
            });
        </script>
    </body>
</html>