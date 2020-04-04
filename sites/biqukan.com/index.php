<?php

ini_set('memory_limit', '200M');
ini_set('max_execution_time', 30*60);
session_start();
require_once dirname(dirname(dirname(__FILE__))) . '/common/libs/php/simplehtmldom_1_9_1/simple_html_dom.php';

function a($v) {
    header('Content-Type: text/css; charset=utf-8');
    print_r($v);
    die();
}

class coder {

    public $is_dev;
    public $http_protocol;
    public $base_url;
    public $base_path_top;
    public $base_path_download;
    public $info_ready;
    public $pre_diyi = '第一';
    public $pre_di1 = '第1';
    public $pre_ji = '集';
    public $pre_juan = '卷';
    public $pre_zhang = '章';
    public $site_domain_pre = 'https://www.biqukan.com';
    public $novel_url;
    public $novel_title_chinese;
    public $novel_title_english;
    public $novel_order_by_asc; // 升序=1，降序=0
    public $novel_menu_page_content;
    public $novel_menu_page_dom;
    public $novel_first_chapter_title;
    public $novel_chapters;

    public function __construct() {
        $this->is_dev = 0;
        $this->check_libs();
        $this->check_parameters();
        $this->define_parameters();
        $this->show_download_page_when_txt_is_ready();
        
    }

    public function error_message($message) {
        echo '<div style="margin: 50px auto; max-width: 500px; border: 1px solid gray; padding: 30px; background: #EEE; border-radius: 10px;">';
        echo $message;
        echo '<hr /><a href="javascript:history.back();" style="color: gray; font-weight: bold; text-decoration:none;">返回</a>';
        echo '</div>';
        die();
    }

    public function check_libs() {
        if (!function_exists('curl_init')) {
            $this->error_message('抱歉，本服务器不支持curl函数，无法制作txt文件，请联系管理员。');
        }
    }

    public function check_parameters() {
        if (!isset($_SESSION['url'])) {
            $this->error_message('小说链接已丢失, 请前往<a href="../../">重新设置</a>。');
        }
        
        if(isset($_POST['submitact'])){
            $_SESSION['info_ready'] = 1;
        } else {
            $_SESSION['info_ready'] = 0;
        }
    }

    public function define_parameters() {
        $this->info_ready = 0;
        if (isset($_SESSION['info_ready']) && $_SESSION['info_ready']) {
            $this->info_ready = 1;
        }

        $this->http_protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://';
        $this->base_url = trim(str_replace('/sites/' . $_SESSION['site'] . '/index.php', '', $this->http_protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']), '/') . '/';
        $this->base_path_top = dirname(dirname(dirname(__FILE__))) . '/';
        $this->base_path_download = $this->base_path_top . 'download/';

        $this->novel_url = $_SESSION['url'];
    }

    public function set_novel_url ($url) {
        $this->novel_url = $url;
    }
    
    
    public function object2array(&$object) {
        if (is_object($object)) {
            $arr = (array) ($object);
        } else {
            $arr = &$object;
        }
        if (is_array($arr)) {
            foreach ($arr as $varName => $varValue) {
                $arr[$varName] = $this->object2array($varValue);
            }
        }
        return $arr;
    }

    function str_split_unicode($str, $l = 0) {

        if ($l > 0) {
            $ret = array();
            $len = mb_strlen($str, "UTF-8");
            for ($i = 0; $i < $len; $i += $l) {
                $ret[] = mb_substr($str, $i, $l, "UTF-8");
            }
            return $ret;
        }
        return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function getFirstCharterBulk($str, $is_lowercase = true) {
        $return = '';
        $chars = $this->str_split_unicode($str);
        foreach ($chars as $k => $v) {
            $return .= ($is_lowercase)?strtolower($this->getFirstCharterSingle($v)):$this->getFirstCharterSingle($v);
        }
        return $return;
    }

    public function getFirstCharterSingle($str) {
        if (empty($str)) {
            return '';
        }
        $fchar = ord($str{0});
        if ($fchar >= ord('A') && $fchar <= ord('z'))
            return strtoupper($str{0});
        $s1 = iconv('UTF-8', 'gb2312', $str);
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $str ? $s1 : $str;
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 && $asc <= -20284)
            return 'A';
        if ($asc >= -20283 && $asc <= -19776)
            return 'B';
        if ($asc >= -19775 && $asc <= -19219)
            return 'C';
        if ($asc >= -19218 && $asc <= -18711)
            return 'D';
        if ($asc >= -18710 && $asc <= -18527)
            return 'E';
        if ($asc >= -18526 && $asc <= -18240)
            return 'F';
        if ($asc >= -18239 && $asc <= -17923)
            return 'G';
        if ($asc >= -17922 && $asc <= -17418)
            return 'H';
        if ($asc >= -17417 && $asc <= -16475)
            return 'J';
        if ($asc >= -16474 && $asc <= -16213)
            return 'K';
        if ($asc >= -16212 && $asc <= -15641)
            return 'L';
        if ($asc >= -15640 && $asc <= -15166)
            return 'M';
        if ($asc >= -15165 && $asc <= -14923)
            return 'N';
        if ($asc >= -14922 && $asc <= -14915)
            return 'O';
        if ($asc >= -14914 && $asc <= -14631)
            return 'P';
        if ($asc >= -14630 && $asc <= -14150)
            return 'Q';
        if ($asc >= -14149 && $asc <= -14091)
            return 'R';
        if ($asc >= -14090 && $asc <= -13319)
            return 'S';
        if ($asc >= -13318 && $asc <= -12839)
            return 'T';
        if ($asc >= -12838 && $asc <= -12557)
            return 'W';
        if ($asc >= -12556 && $asc <= -11848)
            return 'X';
        if ($asc >= -11847 && $asc <= -11056)
            return 'Y';
        if ($asc >= -11055 && $asc <= -10247)
            return 'Z';
        return null;
    }

    public function init_novel_basic_info() {

        /* 新方法: 2019-10-21 */
        if (!$this->is_dev) {
            /* 这个方法不行，貌似会被对方网站检测为机器人，请使用cURL */
            //$this->novel_menu_page_dom = file_get_html($this->novel_url);
            
            $html_content = trim($this->get_content_by_curl( $this->novel_url, true ));
            if($html_content==''){ 
                $this->error_message('抱歉！无法获取小说章节信息（整个网页），请联系管理员进行升级：<br /><a href="' . $this->novel_url . '" target="_blank">' . $this->novel_url . '</a>');
            }
            $this->novel_menu_page_dom = str_get_html($html_content);
            
        } else {
            $html_content = '';
            $this->novel_menu_page_dom = str_get_html($this->dev_get_content_of_menu_page());
        }
        $this->novel_menu_page_content = $this->novel_menu_page_dom->outertext;
        if (trim($this->novel_menu_page_content) == '') {
            $this->error_message('抱歉！小说章节太多，无法处理txt文件！'); 
        }

        /* 标题： <meta property="og:title" content="大唐神级驸马"/> */
        $this->novel_title_chinese = $this->novel_menu_page_dom->find('meta[property="og:title"]', 0)->content;
        $this->novel_title_english = $this->getFirstCharterBulk($this->novel_title_chinese);
        

        /**
         * <div id="list"> ... <dd> <a style="" href="/425_425345/2233845.html">第一章 驸马饶命</a></dd>  ... </div>
         */
        $div_wrapper = $this->novel_menu_page_dom->find('div.listmain', 0); // 只有一个
        $div_wrapper_outertext = $div_wrapper->outertext;
        if (trim($div_wrapper_outertext) == '') {
            $this->error_message('抱歉！无法获取小说章节信息（目录区域），请联系管理员进行升级：<br /><a href="' . $this->novel_url . '" target="_blank">' . $this->novel_url . '</a>');
        }

        $dd_a_s = array();
        foreach ($div_wrapper->find('a') as $k => $v) {
            $full_title = $v->innertext;
            $url_part = $v->href;


            $dd_a_s[] = array(
                'url_part' => $url_part,
                'url_full' => $this->site_domain_pre . $url_part,
                'title_full' => $full_title,
            );
        }
        if (!count($dd_a_s)) {
            $this->error_message('抱歉！无法分析到正确的章节信息！ （空目录）');
        }

        /* 找到第一章 */
        $diyi = $this->pre_diyi;
        $di1 = $this->pre_di1;
        $ts = array(
            $diyi . $this->pre_juan . ' ' . $diyi . $this->pre_zhang . ' ', // '第一卷 第一章 '，
            $diyi . $this->pre_ji . ' ' . $diyi . $this->pre_zhang . ' ', // '第一集 第一章 '，
            $diyi . $this->pre_zhang . ' ', // '第一章 '，
            
            $di1 . $this->pre_juan . ' ' . $di1 . $this->pre_zhang . ' ', // '第一卷 第一章 '，
            $di1 . $this->pre_ji . ' ' . $di1 . $this->pre_zhang . ' ', // '第一集 第一章 '，
            $di1 . $this->pre_zhang . ' ', // '第一章 '，
        );
        foreach ($ts as $kts => $vts) {
            foreach ($dd_a_s as $k => $v) {
                if ($this->novel_first_chapter_title == '') {
                    if (preg_match('/^' . $vts . '/i', $v['title_full'])) {
                        $this->novel_first_chapter_title = $v['title_full'];
                    }
                }
            }
        }

        if ($this->novel_first_chapter_title == '') {
            file_put_contents($this->base_path_top . 'admin/tmp/error_html.txt', $html_content);
            $this->error_message('抱歉！成功获取小说目录后，找不到起始的第一章，无法解析。');
        }

        /* 获取正确的章节 */
        $found = 0;
        foreach ($dd_a_s as $k => $v) {
            if ($v['title_full'] == $this->novel_first_chapter_title) {
                $found = 1;
            }
            if ($found) {
                $this->novel_chapters[] = $v;
            }
        }

        $_SESSION['novel']['url'] = $this->novel_url;
        $_SESSION['novel']['md5'] = md5($this->novel_url);
        $_SESSION['novel']['title_chinese'] = $this->novel_title_chinese;
        $_SESSION['novel']['title_english'] = $this->novel_title_english;
        $_SESSION['novel']['file_name'] = $this->novel_title_english . '.txt';
        $_SESSION['novel']['chapters'] = $this->novel_chapters;
        
        
        if (!count($this->novel_chapters)) {
            $this->error_message('抱歉！成功获取小说目录后，获取了第一章，但是无法找到剩下的可用章节。');
        }
    }

    public function get_tablePlaceHolder() {
        $return = '';
        $br = PHP_EOL;
        if (count($this->novel_chapters)) {
            $return = '<table class="table table-bordered">' . $br;
            foreach ($this->novel_chapters as $k => $v) {
                $return .= "<tr><td>{$v['title_full']}</td><td><a href='{$v['url_full']}' target='_blank'>{$v['url_full']}</a></td></tr>" . $br;
            }
            $return .= '</table>' . $br;
        }

        return $return;
    }

    public function view() {
        if (!$this->info_ready) {
            $tablePlaceHolder = $this->get_tablePlaceHolder();

            $vc = file_get_contents(dirname(__FILE__) . '/view.php');

            $vc = str_replace('<!--novelTitlePlaceHolder-->', $this->novel_title_chinese, $vc);
            $vc = str_replace('<!--txtFileNamePlaceHolder-->', $this->novel_title_english.'.txt', $vc);
            $vc = str_replace(' value="" id="url"', ' value="' . $_SESSION['url'] . '" id="url"', $vc);
            $vc = str_replace('<!--tablePlaceHolder-->', $tablePlaceHolder, $vc);


            echo $vc;
            die();
        }
    }

    public function dev_get_content_of_menu_page() {
        $f = $this->base_path_top . 'admin/tmp/content_menu_page.txt';
        if(!is_file($f)){
            file_put_contents($f, '');
        }
        $page = file_get_contents( $f );
        return $page;
    }
    
    
    public function get_cache_by_url ($url) {
        $return = '';
        
        $md5 = md5($url);
        $folder = $this->base_path_top . 'cache/'.$md5.'/';
        $sub_dir = $folder . 'chapters/'; // 具体章节html不会变，请保持
        if(!is_dir($folder)){
            mkdir($folder, 0755, true);
        }
        if(!is_dir($sub_dir)){
            mkdir($sub_dir, 0755, true);
        }
        
        
        $cache_file = $folder. 'chapters.html';
        $cache_file_expire = $folder . 'expire.date';
        
        if (is_file($cache_file_expire) && is_file($cache_file)) {
            $c = file_get_contents($cache_file_expire);
            if( intval($c) >= time() ){
                $return = file_get_contents($cache_file);
            }
        }
        
        return $return;
    }
    
    public function set_cache_by_url ($url, $cache = '') {
        $md5 = md5($url);
        $folder = $this->base_path_top . 'cache/'.$md5.'/';
        $sub_dir = $folder . 'chapters/'; // 具体章节html不会变，请保持
        if(!is_dir($folder)){
            mkdir($folder, 0755, true);
        }
        if(!is_dir($sub_dir)){
            mkdir($sub_dir, 0755, true);
        }
        
        
        $cache_file = $folder. 'chapters.html';
        $cache_file_expire = $folder . 'expire.date';
        
        if (is_file($cache_file_expire) && is_file($cache_file)) {
            unlink($cache_file_expire);
            unlink($cache_file);
        }
        
        $expired_time = 3*24*60*60 + time();
        file_put_contents($cache_file_expire, $expired_time);
        file_put_contents($cache_file, $cache);
        
    }
    

    public function get_content_by_curl($url = '', $need_cache = false) {
        $return = '';

        $need_update_cache = 0;
        if($need_cache){
            $cache = $this->get_cache_by_url( $url );
            if($cache != ''){
                $return = $cache;
                return $return;

            } else {
                $need_update_cache = 1;
            }
        }
        
        
        /* GO */
        $curl = curl_init();

        error_log('[cURL] ---------------------------------------');
        error_log('[cURL] '.$url);

        // 设置你需要抓取的URL
        curl_setopt($curl, CURLOPT_URL, $url);

        //curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");


        // 设置header
        curl_setopt($curl, CURLOPT_HEADER, 0);

        // 设置cURL 参数，要求结果保存到字符串中还是输出到屏幕上。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // 在尝试连接时等待的秒数
        //curl_setopt($curl, CURLOPT_CONNECTTIMEOUT , 60);
        // 最大执行时间
        //curl_setopt($curl, CURLOPT_TIMEOUT, 200);


        // 运行cURL，请求网页
        $data = curl_exec($curl);

        // 关闭URL请求
        curl_close($curl);

        error_log('[cURL] End');

        /**
         * 转码 to utf8
         */
        if (stripos($data, 'charset=gbk') !== false) {
            $data = $this->str_to_utf8( $data );
        }
        
        
        // 显示获得的数据
        if (is_string($data)) {
            $return = $data;

            if($need_update_cache){
                /* Save into cache for 3 days */
                $this->set_cache_by_url( $url, $return );
            }
        }

        error_log('[cURL] Length: '. strlen($return));
        

        return $return;
    }
    
    
    public function show_download_page_when_txt_is_ready ($url = '') {
        if($url == ''){
            $url = $_SESSION['url'];
        }
        
        $md5 = md5($url);
        $download_folder = $this->base_path_download . $md5.'/';
        if(!is_dir($download_folder)){
            mkdir($download_folder, 0755, true);
        }
        
        $expire_date_file = $download_folder . 'expire.date';
        $fn_cn = $download_folder . 'file_name_chinese.conf';
        $fn_en = $download_folder . 'file_name_english.conf'; // without '.txt'
        
        
        /* Existing txt */
        if(is_file($fn_cn) && is_file($fn_en) && is_file($expire_date_file)){
            if (intval(file_get_contents($expire_date_file)) >= time()) {
                $en = file_get_contents($fn_en);
                $cn = file_get_contents($fn_cn);
                $file_name_en = $en.'.txt';
                $txt_file = $download_folder . $file_name_en;
                if(is_file($txt_file)){
                    /* Txt file is not expired */
                    $download_url = $this->base_url . 'download/'.$md5.'/'.$file_name_en; // .txt
                    $download_page_message = $cn . '   ['.$file_name_en.'] <br />';
                    $download_page_message .= '<a href="'.$download_url.'">'.$download_url.'</a>';

                    /* Download page */
                    $this->error_message($download_page_message);
                }
            }
        }
    }
    
    
    public function make_sure_folder_exists ($folder) {
        if(!is_dir($folder)){
            mkdir($folder, 0755, true);
        }
    }
    
    public function del_file ($file) {
        if(is_file($file)){
            unlink($file);
        }
    }
    
    
    public function str_to_utf8 ($str = '') {
        $current_encode = mb_detect_encoding($str, array("ASCII","GB2312","GBK",'BIG5','UTF-8')); 
        $encoded_str = mb_convert_encoding($str, 'UTF-8', $current_encode);
        return $encoded_str;
    }
    public function str_to_GBK ($str = '') {
        $current_encode = mb_detect_encoding($str, array("ASCII","GB2312","GBK",'BIG5','UTF-8'));
        $encoded_str = mb_convert_encoding($str, 'GBK', $current_encode);
        return $encoded_str;
    }

    /**
     * 
    Array
    (
        [url] => https://www.biquge.tw/50_50626/
        [md5] => 6d10814decb8e5e4fe04100f29778631
        [title_chinese] => 末日轮盘
        [title_english] => mrlp
        [file_name] => mrlp.txt
        [chapters] => Array
            (
                [0] => Array
                    (
                        [url_part] => /50_50626/2673362.html
                        [url_full] => https://www.biquge.tw/50_50626/2673362.html
                        [title_full] => 第一章 重回
                    )

                [1] => Array
                    (
                        [url_part] => /50_50626/2673363.html
                        [url_full] => https://www.biquge.tw/50_50626/2673363.html
                        [title_full] => 第二章 一级末日轮盘
                    )

     * 
     * 
     * 
     */
    public function make_txt_and_zip_and_download() {
        /* Build the txt file */
        $novel_info = $_SESSION['novel'];
        $novel_folder = $this->base_path_top . 'cache/' . $novel_info['md5'] . '/';
        $chapters_folder = $novel_folder . 'chapters/';
        $this->make_sure_folder_exists( $chapters_folder );
        
        
        /* #1. Check the cache for different chapters (txt only) */
        foreach ($novel_info['chapters'] as $k => $oneChapter) {
            $c_url = $oneChapter['url_full'];
            $c_md5 = md5($c_url);
            $c_file_name_html = $c_md5 . '.html';
            $c_file_name_txt = $c_md5 . '.txt';
            
            
            if (!is_file($chapters_folder . $c_file_name_html)) {
                sleep(5);
                error_log('Chapter: '.$c_url);
                $html_code = $this->get_content_by_curl( $c_url );
                file_put_contents($chapters_folder . $c_file_name_html, $html_code);
                
                /* 提取正文 */
                $preg = '/<div id="content">([\s\S]*?)<\/div>/i';
                preg_match($preg, $html_code, $result);
                $text_content = '';
                $eol = PHP_EOL;
                if(isset($result[0])){
                    $part = $result[0];
                    $part = str_replace('<br />', $eol, $part);
                    $part = str_replace('<br/>', $eol, $part);
                    $part = str_replace('</br>', $eol, $part);
                    $part = str_replace('</ br>', $eol, $part);
                    $part = str_replace('<br >', $eol, $part);
                    $part = str_replace('<br>', $eol, $part);

                    $part = str_replace('&emsp;', ' ', $part);
                    $part = str_replace('&nbsp;', ' ', $part);

                    $part = strip_tags($part);
                    $text_content = trim($part);
                }
                if(stripos($text_content, $oneChapter['title_full']) === false){
                    /* 添加标题 */
                    $text_content = $oneChapter['title_full'] . $eol . $text_content;
                }
                file_put_contents($chapters_folder . $c_file_name_txt, $text_content);
                
                error_log('Chapter ===> Length: '. strlen($text_content).'   [URL]'.$c_url.'');
            }
        }
        
        /* #2. Combine the text into one TEXT file  */
        error_log('>>>> ALL the chapter is done, we are going to combine the txt file.');
        $md5 = md5($novel_info['url']);
        $download_folder = $this->base_path_download . $md5.'/';
        if(!is_dir($download_folder)){
            mkdir($download_folder, 0755, true);
        }
        
        $expire_date_file = $download_folder . 'expire.date';
        $fn_cn = $download_folder . 'file_name_chinese.conf';
        $fn_en = $download_folder . 'file_name_english.conf'; // without '.txt'
        $txt_file = $download_folder . $novel_info['file_name']; // with '.txt'
        $this->del_file( $expire_date_file );
        $this->del_file( $fn_cn );
        $this->del_file( $fn_en );
        $this->del_file( $txt_file );
        
        file_put_contents($fn_cn, $novel_info['title_chinese']);
        file_put_contents($fn_en, $novel_info['title_english']);
        file_put_contents($expire_date_file, 7*24*60*60+time()); // 7 days
        file_put_contents($txt_file, '');
        
        foreach ($novel_info['chapters'] as $k => $oneChapter) {
            $c_url = $oneChapter['url_full'];
            $c_md5 = md5($c_url);
            $c_txt_path = $chapters_folder . $c_md5 . '.txt';
            
            if (is_file($c_txt_path)) {
                file_put_contents($txt_file, file_get_contents($c_txt_path).PHP_EOL.PHP_EOL, FILE_APPEND);
            }
        }
        
        
        
        /* #3. Cache it up  */
        $this->show_download_page_when_txt_is_ready();
    }

}

$coder = new coder();
//$coder->set_novel_url('../../../../../../tmp/delme/novel.html');
$coder->init_novel_basic_info();
$coder->view();
$coder->make_txt_and_zip_and_download();
