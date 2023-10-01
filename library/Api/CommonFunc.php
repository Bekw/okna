<?php
    function clearParam($param){
        $pos = strpos($param,'-p1');
        if($pos > 0){
            $str = substr($param,$pos+2,3);
            if(preg_match("/^[\d\+]+$/",$str) == true){
                return substr($param,0,strlen($param)-5);
            }
            else{
                return $param;
            }
        }
        else{
            return $param;
        }
    }
    function getHttpHost(){
        if(isset($_SERVER['HTTPS'])){
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        }
        else{
            $protocol = 'http';
        }
        return $protocol . "://" . $_SERVER['HTTP_HOST'];
    }

    function checkForMobile(){
        $phone_array = array('iphone', 'android', 'pocket', 'palm', 'windows ce', 'windowsce', 'cellphone', 'opera mobi', 'ipod', 'small', 'sharp', 'sonyericsson', 'symbian', 'opera mini', 'nokia', 'htc_', 'samsung', 'motorola', 'smartphone', 'blackberry', 'playstation portable', 'tablet browser');
        $agent = strtolower( $_SERVER['HTTP_USER_AGENT'] );
        foreach ($phone_array as $value) {
            if (strpos($agent, $value) !== false ) return true;
        }

        return false;
    }

function json_fix_cyr($json_str) {
    $cyr_chars = array (
        '\u0430' => 'а', '\u0410' => 'А',
        '\u0431' => 'б', '\u0411' => 'Б',
        '\u0432' => 'в', '\u0412' => 'В',
        '\u0433' => 'г', '\u0413' => 'Г',
        '\u0434' => 'д', '\u0414' => 'Д',
        '\u0435' => 'е', '\u0415' => 'Е',
        '\u0451' => 'ё', '\u0401' => 'Ё',
        '\u0436' => 'ж', '\u0416' => 'Ж',
        '\u0437' => 'з', '\u0417' => 'З',
        '\u0438' => 'и', '\u0418' => 'И',
        '\u0439' => 'й', '\u0419' => 'Й',
        '\u043a' => 'к', '\u041a' => 'К',
        '\u043b' => 'л', '\u041b' => 'Л',
        '\u043c' => 'м', '\u041c' => 'М',
        '\u043d' => 'н', '\u041d' => 'Н',
        '\u043e' => 'о', '\u041e' => 'О',
        '\u043f' => 'п', '\u041f' => 'П',
        '\u0440' => 'р', '\u0420' => 'Р',
        '\u0441' => 'с', '\u0421' => 'С',
        '\u0442' => 'т', '\u0422' => 'Т',
        '\u0443' => 'у', '\u0423' => 'У',
        '\u0444' => 'ф', '\u0424' => 'Ф',
        '\u0445' => 'х', '\u0425' => 'Х',
        '\u0446' => 'ц', '\u0426' => 'Ц',
        '\u0447' => 'ч', '\u0427' => 'Ч',
        '\u0448' => 'ш', '\u0428' => 'Ш',
        '\u0449' => 'щ', '\u0429' => 'Щ',
        '\u044a' => 'ъ', '\u042a' => 'Ъ',
        '\u044b' => 'ы', '\u042b' => 'Ы',
        '\u044c' => 'ь', '\u042c' => 'Ь',
        '\u044d' => 'э', '\u042d' => 'Э',
        '\u044e' => 'ю', '\u042e' => 'Ю',
        '\u044f' => 'я', '\u042f' => 'Я',

        '\r' => '',
        '\n' => '<br />',
        '\t' => ''
    );

    foreach ($cyr_chars as $cyr_char_key => $cyr_char) {
        $json_str = str_replace($cyr_char_key, $cyr_char, $json_str);
    }
    return $json_str;
}

function send_to_post_express($result){
    $opts = array(
        'http'=>array(
            'method'  => 'POST',
            'header'  => 'Content-type: text/xml',
            'charset' => 'utf-8',
            'content' => $result
        )
    );

    $context = stream_context_create($opts);
    if (!$contents = @file_get_contents('https://home.courierexe.ru/api/', false, $context)) {
        if (!$curl = curl_init()) {
            $this->errors = 'Возможно не поддерживается передача по HTTPS. Проверьте наличие open_ssl';
            return false;
        }
        curl_setopt($curl, CURLOPT_URL, 'https://home.courierexe.ru/api/');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $result);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: text/xml; charset=utf-8'));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $contents = curl_exec($curl);
        curl_close($curl);
    }

    if (!$contents) {
        $this->errors = 'Ошибка сервиса';
        return false;
    }
    return $contents;
}

function sizeFilter( $bytes ){
    $label = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
    for( $i = 0; $bytes >= 1024 && $i < ( count( $label ) -1 ); $bytes /= 1024, $i++ );
    return( round( $bytes, 2 ) . " " . $label[$i] );
}

function zeroToNull($s){
    if ($s === "0" || $s === "" || $s === 0)
        return null;
    else
        return $s;
}
function zeroToMinusOne($s){
    if ($s === "0" || $s === "" || $s === 0)
        return -1;
    else
        return $s;
}

function zeroToNullInt($s){
    if ($s === 0 || $s === "")
        return null;
    else
        return $s;
}

function emptyToNull($s){
    if ($s === "")
        return null;
    else
        return $s;
}

function nvl($s, $r){
    if ($s == null)
        return $r;
    else
        return $s;
}


function log_kkb($what, $error, $status = "ERROR"){
    $file = $_SERVER['DOCUMENT_ROOT']."/log/log_kkb/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". mb_substr($error, 0, 1000)."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". mb_substr($error, 0, 1000)."\n\n", FILE_APPEND);
    }
}
function log_sql($func, $str, $p, $error){
    $file = $_SERVER['DOCUMENT_ROOT']."/log_sql/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$func."\n\n".$error." \n\n".$str."\n\n".json_encode($p)."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$func."\n\n".$error." \n\n".$str."\n\n".json_encode($p)."\n\n", FILE_APPEND);
    }
}

function log_unisender($what, $error, $status = "ERROR"){
    $file = $_SERVER['DOCUMENT_ROOT']."/log_unisender/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". mb_substr($error, 0, 1000)."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". mb_substr($error, 0, 1000)."\n\n", FILE_APPEND);
    }
}

function log_sync($what, $error, $status = "ERROR"){
    $file = $_SERVER['DOCUMENT_ROOT']."/log_sync/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". mb_substr($error, 0, 1000)."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". mb_substr($error, 0, 1000)."\n\n", FILE_APPEND);
    }
}

function log_1C($what, $response = "", $request = ""){
    $dir = $_SERVER['DOCUMENT_ROOT']. '/log/log_1c';
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    $file_name = $dir . "/" . date('Y.m.d H') . ".log";
    $timezone  = 5;
    $cur_time = gmdate("d.m.Y H:i:s", time() + 3600*($timezone+date("I")));
    file_put_contents($file_name, $cur_time.";".$what.";\n".$request.";\n".$response.";\n", FILE_APPEND);

}

function log_warehouse($what, $response = "", $request = ""){
    $dir = $_SERVER['DOCUMENT_ROOT']. '/log/log_warehouse';
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    $file_name = $dir . "/" . date('Y.m.d H') . ".log";
    $timezone  = 5;
    $cur_time = gmdate("d.m.Y H:i:s", time() + 3600*($timezone+date("I")));
    file_put_contents($file_name, $cur_time.";".$what.";\n".$request.";\n".$response.";\n", FILE_APPEND);

}

function log_cp($what, $error, $status = "ERROR"){
    $file = $_SERVER['DOCUMENT_ROOT']."/log/log_cp/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }
}

function log_kaspi($what, $error, $status = "ERROR"){
    $file = $_SERVER['DOCUMENT_ROOT']."/log/log_kaspi/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }
}

function send_to_unisender($post, $type){
    $url = '';
    if($type == 1){
        $url = 'https://api.unisender.com/ru/api/sendEmail?format=json';
    }else if($type == 2){
        $url = 'https://api.unisender.com/ru/api/checkEmail?format=json';
    }
    // Устанавливаем соединение
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_URL,$url);
    $result = curl_exec($ch);
    return $result;
}

function stage_status_color($stage_status_color){
    $color_stage = '';
    switch ($stage_status_color){
        case -1: $color_stage = ''; break;
        case 1: $color_stage = 'yellow'; break;
        case 2: $color_stage = 'red'; break;
        case 3: $color_stage = 'orange'; break;
        case 4: $color_stage = 'lightgreen'; break;
    }
    return $color_stage;
}



function buildTree(array $elements, $parentId = 0) {
    $branch = array();

    foreach ($elements as $element) {
        if ($element['check_list_pid'] == $parentId) {
            $children = buildTree($elements, $element['check_list_id']);
            if ($children) {
                $element['child'] = $children;
            }
            $branch[] = $element;
        }
    }

    return $branch;
}

function num_padezh($num){
    if($num == ''){
        return 'НЕ УКАЗАНО В РЕКВИЗИТАХ';
    }
    $nul = 'ноль';
    $ten = array(
        array('','одного','двух','трех','четырех','пяти','шести','семи', 'восьми','девяти'),
    );
    $a20 = array('десяти','одиннадцати','двенадцати','тринадцати','четырнадцати' ,'пятнадцати','шестнадцати','семнадцати','восемнадцати','девятнадцати');
    $tens = array(2=>'двадцати','тридцати','сорока','пятидесяти','шестидесяти','семидесяти' ,'восемидесяти','девяноста');
    $hundred = array('','ста','двести','триста','четыреста','пятиста','шестиста', 'семита','восемиста','девятиста');
    $unit = array(
        array('' ,'' ,'', 1),
        array('' ,'' ,'' ,0),
        array('тысячи' ,1),
        array('миллион' ,0),
        array('миллиард',0),
    );

    list($n) = explode('.',sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($n) > 0) {
        foreach(str_split($n, 3) as $uk => $v)
        {
            if (!intval($v)) continue;
            $uk = sizeof($unit)-$uk-1;
            $gender = $unit[$uk][3];
            list($i1,$i2,$i3) = array_map('intval',str_split($v,1));

            $out[] = $hundred[$i1];
            if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3];
            else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3];

            if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
        }
    }
    else $out[] = $nul;
    return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}

function num2str($num){
    $nul = 'ноль';
    $ten = array(
        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
    );
    $a20 = array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
    $tens = array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
    $hundred = array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
    $unit = array(
        array('' ,'' ,'', 1),
        array('' ,'' ,'' ,0),
        array('тысяча' ,'тысячи' ,'тысяч' ,1),
        array('миллион' ,'миллиона','миллионов' ,0),
        array('миллиард','милиарда','миллиардов',0),
    );

    list($n) = explode('.',sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($n) > 0) {
        foreach(str_split($n, 3) as $uk => $v)
        {
            if (!intval($v)) continue;
            $uk = sizeof($unit)-$uk-1;
            $gender = $unit[$uk][3];
            list($i1,$i2,$i3) = array_map('intval',str_split($v,1));

            $out[] = $hundred[$i1];
            if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3];
            else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3];

            if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
        }
    }
    else $out[] = $nul;
    return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}
function morph($n, $f1, $f2, $f5){
    $n = abs(intval($n)) % 100;
    if ($n>10 && $n<20) return $f5;
    $n = $n % 10;
    if ($n>1 && $n<5) return $f2;
    if ($n==1) return $f1;
    return $f5;
}

function num_padezh2($number, $one, $two, $five) {
    if($number == ''){
        return 'НЕ УКАЗАНО В РЕКВИЗИТАХ';
    }
    if (($number - $number % 10) % 100 != 10) {
        if ($number % 10 == 1) {
            $result = $one;
        } elseif ($number % 10 >= 2 && $number % 10 <= 4) {
            $result = $two;
        } else {
            $result = $five;
        }
    } else {
        $result = $five;
    }
    return $result;
};

function russian_date($date, $year_short = false, $quotes = true){
    if($date == ''){
        return 'НЕ УКАЗАНО В РЕКВИЗИТАХ';
    }
    $date=explode(".", $date);
    switch ($date[1]){
        case 1: $m='января'; break;
        case 2: $m='февраля'; break;
        case 3: $m='марта'; break;
        case 4: $m='апреля'; break;
        case 5: $m='мая'; break;
        case 6: $m='июня'; break;
        case 7: $m='июля'; break;
        case 8: $m='августа'; break;
        case 9: $m='сентября'; break;
        case 10: $m='октября'; break;
        case 11: $m='ноября'; break;
        case 12: $m='декабря'; break;
    }
    if($year_short){
        if($quotes){
            return '«'.$date[0].'» '.$m.' '.$date[2].' г.';
        }else{
            return $date[0].' '.$m.' '.$date[2].' г.';
        }
    }else{
        if($quotes){
            return '«'.$date[0].'» '.$m.' '.$date[2].' года';
        }else{
            return $date[0].' '.$m.' '.$date[2].' года';
        }
    }
}

function short_fio($fio){
    if($fio == '' or is_null($fio)){
        return 'НЕ УКАЗАНО В РЕКВИЗИТАХ';
    }
    $words = explode(" ", $fio);
    $short_fio = "";
    foreach ($words as $key => $w) {
        if(!in_array($key, array(1, 2))){
            $short_fio .= $w.' ';
        }else{
            $short_fio .= mb_substr($w, 0,1, 'UTF-8').'. ';
        }
    }
    return $short_fio;
}

function mb_ucfirst($string, $encoding)
{
    $strlen = mb_strlen($string, $encoding);
    $firstChar = mb_substr($string, 0, 1, $encoding);
    $then = mb_substr($string, 1, $strlen - 1, $encoding);
    return mb_strtoupper($firstChar, $encoding) . $then;
}

function formatSizeUnits($url){
    if (file_exists($_SERVER['DOCUMENT_ROOT'].$url)) {
        $bytes = filesize($_SERVER['DOCUMENT_ROOT'].$url);
        if ($bytes >= 1073741824){
            $bytes = round(number_format($bytes / 1073741824, 2)) . ' GB';
        }
        elseif ($bytes >= 1048576){
            $bytes = round(number_format($bytes / 1048576, 2)) . ' MB';
        }
        elseif ($bytes >= 1024){
            $bytes = round(number_format($bytes / 1024, 2)) . ' KB';
        }
        elseif ($bytes > 1){
            $bytes = round($bytes) . ' B';
        }
        elseif ($bytes == 1){
            $bytes = round($bytes) . ' B';
        }
        else{
            $bytes = '0 bytes';
        }
    } else {
        $bytes = 'NONE';
    }


    return $bytes;
}
function subArraysToString($ar, $sep = ', ') {
    $str = '';
    foreach ($ar as $val) {
        $str .= implode($sep, $val);
        $str .= $sep; // add separator between sub-arrays
    }
    $str = rtrim($str, $sep); // remove last separator
    return $str;
}

function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

function save_image($inPath,$outPath){
    //Download images from remote server
    $in = fopen($inPath, "rb");
    $out = fopen($outPath, "wb");

    while ($chunk = fread($in,8192)) {
        fwrite($out, $chunk, 8192);
    }
    fclose($in);
    fclose($out);
}
function does_url_exists($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($code == 200) {
        $status = true;
    } else {
        $status = false;
    }
    curl_close($ch);
    return $status;
}
function log_flats($what, $response = "", $request = ""){
    $dir = $_SERVER['DOCUMENT_ROOT']. '/log/log_flats';
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    $file_name = $dir . "/" . date('Y.m.d H') . ".log";
    $timezone  = 5;
    $cur_time = gmdate("d.m.Y H:i:s", time() + 3600*($timezone+date("I")));
    file_put_contents($file_name, $cur_time.";".$what.";\n".$request.";\n".$response.";\n", FILE_APPEND);

}
function log_internal_images($what, $response = "", $request = ""){
    $dir = $_SERVER['DOCUMENT_ROOT']. '/log/log_internal_images';
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    $file_name = $dir . "/" . date('Y.m.d H') . ".log";
    $timezone  = 5;
    $cur_time = gmdate("d.m.Y H:i:s", time() + 3600*($timezone+date("I")));
    file_put_contents($file_name, $cur_time.";".$what.";\n".$request.";\n".$response.";\n", FILE_APPEND);

}
function log_images($id){
    $dir = $_SERVER['DOCUMENT_ROOT']. '/log/log_images';
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    $file_name = $dir . "/" . date('Y.m.d H') . ".log";
    $timezone  = 5;
    file_put_contents($file_name, $id.",\n", FILE_APPEND);

}
function log_bi_redirect($what, $error, $status = "ERROR"){
    $file = $_SERVER['DOCUMENT_ROOT']."/log/log_bi_redirect/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }
}
function log_sberbank($what, $error, $status = ""){
    $file = $_SERVER['DOCUMENT_ROOT']."/log/log_sberbank/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }
}
function log_tilda_form($what, $error, $status = "ERROR"){
    $file = $_SERVER['DOCUMENT_ROOT']."/log/log_tilda_form/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }
}
function log_fb_form($what, $error, $status = "ERROR"){
    $file = $_SERVER['DOCUMENT_ROOT']."/log/log_fb_form/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }
}
function log_bigapp_form($what, $error, $status = "ERROR"){
    $file = $_SERVER['DOCUMENT_ROOT']."/log/log_bigapp_form/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }
}
function log_send_request($what, $error, $status = "ERROR"){
    $file = $_SERVER['DOCUMENT_ROOT']."/log/log_send_request/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }
}
function log_bigcrm_form($what, $error, $status = "ERROR"){
    $file = $_SERVER['DOCUMENT_ROOT']."/log/log_bigcrm_form/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }
}
function log_sr_render_avail($what, $error, $status = "ERROR"){
    $file = $_SERVER['DOCUMENT_ROOT']."/log/log_sr_render_avail/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }
}
function log_sr_stage($what, $error, $status = "ERROR"){
    $file = $_SERVER['DOCUMENT_ROOT']."/log/log_sr_stage/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }
}
function log_tilda_api($what, $error, $status = "ERROR"){
    $file = $_SERVER['DOCUMENT_ROOT']."/log/log_tilda_api/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }
}
function get_bi_flat_img_url($real_estate_guid, $flat_guid, $size = 400){
    $bi_url = 'https://s3.bi.group/biclick';
    $result['status'] = true;
    $result['error'] = null;
    $result['value'] = null;
    if($real_estate_guid == ''){
        $result['status'] = false;
        $result['error'] = 'Пустой REALESTATE_GUID';
        return $result;
    }
    if($flat_guid == ''){
        $result['status'] = false;
        $result['error'] = 'Пустой FLAT_GUID';
        return $result;
    }

    $result['value'] = $bi_url.'/'.$real_estate_guid.'/'.$flat_guid.'_'.$size.'.png';

    return $result;
}

function check_ob_content(){
    if (ob_get_length() > 0){
        echo ob_get_contents();
        return true;
    }
    return false;
}

function createDirectory($newFile){
    $path_parts = pathinfo($newFile);
    if (!file_exists($path_parts['dirname'])) {
        mkdir($path_parts['dirname'], 0777, true);
    }
    return true;
}

function move_uploaded_file_smart($tmpFile, $newFile){
    /*
    $str = $_SERVER['DOCUMENT_ROOT'].'/documents/';
    if (strpos($newFile, $str) !== false){
        $newFile = str_replace($str, $str . date('Y.m.d').'/', $newFile);
    }*/
    $path_parts = pathinfo($newFile);
    if (!file_exists($path_parts['dirname'])) {
        mkdir($path_parts['dirname'], 0777, true);
    }
    if(move_uploaded_file($tmpFile, $newFile)) {
        return true;
    } else {
        return false;
    }

}

function log_camunda($what, $error, $status = ""){
    $file = $_SERVER['DOCUMENT_ROOT']."/log/log_camunda/".date("Y.m.d H")."-00.txt";
    if (file_exists($file)){
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }  else {
        file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status." ". $error ."\n\n", FILE_APPEND);
    }
}

function planoplanAuth(){
    $data = array('grant_type' => 'client_credentials', 'scope' => 'team');
    $payload = http_build_query($data);
    $headers = array(
        'Authorization: Basic '. base64_encode("DxmWCrCJPQSb92rP:BBg9kCpvvbbe374MRSYNcv"),
        'Content-Type : application/x-www-form-urlencoded'
    );

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.planoplan.com/team/v2/auth/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => $headers,
    ));

    $result = curl_exec($curl);

    if($errno = curl_errno($curl)) {
        $error_message = curl_strerror($errno);
        echo "cURL error ({$errno}):\n {$error_message}";
    }

    curl_close($curl);

    return $result;
}

function planoplanGet($bearer_token, $url, $step){
    if($step > 3){
        $row['value'] = '';
        $row['token'] = $bearer_token;
        $row['error'] = 'Лимит исчерпан';
        $row['code'] = 302;
        return $row;
    }

    $headers = array(
        'Authorization: Bearer '. $bearer_token
    );

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $headers,
    ));

    $response = curl_exec($curl);
    $header_data= curl_getinfo($curl);

    if (curl_errno($curl)) {
        $row['value'] = '';
        $row['token'] = $bearer_token;
        $row['error'] = curl_error($curl);
        $row['code'] = 302;
        return $row;
    }

    curl_close($curl);

    $row['value'] = json_decode($response, true);
    $row['error'] = '';
    $row['code'] = 200;

    if(in_array($header_data['http_code'], array(400, 401))){
        $auth = planoplanAuth();
        $auth = json_decode($auth, true);
        $response = planoplanGet($auth['access_token'], $url, $step + 1);

        $row = $response;
    }

    return $row;
}
function getClientActiveMenu($action_name, $url){
    $tmp = '';
    if($action_name == $url){
        $tmp = ' active';
    }
    return $tmp;
}

function get_from_remarket($token, $url){
    $authorization = "Authorization: Bearer ".$token; // Prepare the authorisation token

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function request_to_remarket($token, $url, $data){
    $authorization = "Authorization: Bearer ".$token;

    $ch = curl_init();
    //$payload = json_encode( array( "customer"=> $data ) );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $authorization));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function request_to_remarket_without_token($url, $data){
    $ch = curl_init();
    //$payload = json_encode( array( "customer"=> $data ) );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function getSecret(){
    $ob = new Application_Model_DbTable_Parent();
    $row = $ob->scalarSP(__FUNCTION__, "public.get_secret() id", array(), 'id');
    return $row['value'];
}
function get_url_http_code($theURL) {
    $headers = get_headers($theURL);
    return substr($headers[0], 9, 3);
}
function progressShow($str){
    if (ob_get_level()) {
        echo $str.'<br/>';
        ob_flush();
    }
    flush();
}

function filter_arr(&$value) {
    $value = htmlspecialchars($value);
}

function get_api_url(){
    $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', 'production');
    $host = $config->constants->api_url;
    return $host;
}
function get_remarket_url(){
    $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', 'production');
    $host = $config->constants->remarket_url;
    return $host;
}
function get_account_url(){
    $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini', 'production');
    $host = $config->constants->account_url;
    return $host;
}
function downloadFileFormTilda($url, $file_name, $dir){
    // Initialize the cURL session
    $ch = curl_init($url);
    // Save file into file location
    $save_file_loc = $dir . $file_name;
    // Open file
    $fp = fopen($save_file_loc, 'wb');
    // It set an option for a cURL transfer
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    // Perform a cURL session
    curl_exec($ch);
    // Closes a cURL session and frees all resources
    curl_close($ch);
    // Close file
    fclose($fp);
}
function createHtmlTilda($filename, $html){
    $myfile = fopen($filename, "w") or die("Unable to open file!");
    fwrite($myfile, $html);
    fclose($myfile);
}
function myFilter($var){
    return ($var !== NULL && $var !== FALSE && $var !== "");
}
function isBool($s){
    if (intval($s) > 0 || $s === true || $s === 'true')
        return 'true';
    else
        return 'false';
}
function isBoolLocal($s){
    if (intval($s) > 0 || $s === true || $s === 'true')
        return true;
    else
        return false;
}
function isBoolSelect($s){
    if (intval($s) > 0 || $s === true || $s === 'true')
        return 'true';
    if (intval($s) == 0 || $s === false || $s === 'false')
        return 'false';
    if (intval($s) == -1 || $s === null || $s === 'null')
        return null;

    return null;
}
?>