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

function getSecret(){
    $ob = new Application_Model_DbTable_Parent();
    $row = $ob->scalarSP(__FUNCTION__, "public.get_secret() id", array(), 'id');
    return $row['value'];
}
function get_url_http_code($theURL) {
    $headers = get_headers($theURL);
    return substr($headers[0], 9, 3);
}

function filter_arr(&$value) {
    $value = htmlspecialchars($value);
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
function formatPhoneNumber($phone_number) {
    // Удаление всех нецифровых символов
    $just_numbers = preg_replace('/[^\d]/', '', $phone_number);

    // Замена первой цифры с '8' на '7'
    if (substr($just_numbers, 0, 1) === '8') {
        $just_numbers = '7' . substr($just_numbers, 1);
    }

    // Добавление '7', если длина равна 10
    if (strlen($just_numbers) === 10) {
        $just_numbers = '7' . $just_numbers;
    }

    // Обрезка до 11 символов, если нужно
    if (strlen($just_numbers) > 11) {
        $just_numbers = substr($just_numbers, 0, 11);
    }

    return $just_numbers;
}