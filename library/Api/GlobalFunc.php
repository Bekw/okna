<?

/**********Database common functions*************************************************************************/

//Записывает ошибки базы в лог файл
function _write_error_db_log($method, $error){
    $dir = $_SERVER['DOCUMENT_ROOT']. '/log/error_db';
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    $file_name = $dir . "/" . date('Y.m.d') . ".log";
    $timezone  = 5;
    $cur_time = gmdate("d.m.Y H:i:s", time() + 3600*($timezone+date("I")));
    file_put_contents($file_name, $cur_time.";".$method.";"."user_id=".getCurUser().";Error:".$error."\n", FILE_APPEND);
    /*$ob = new Application_Model_DbTable_Email();
    $error = '<div><pre><code>'.$error.'</code></pre></div>';
    $ob->insert_send_error('aidar_q@mail.ru;ujvjhb@inbox.ru;nightwolf.95@mail.ru', 'error_db', $error, $method);*/
}

//Записывает в лог файл процедуры, которые отрабатывают больше 1 секунды
function _write_long_time_db_log($method, $start_time){
    $dir = $_SERVER['DOCUMENT_ROOT']. '/log/long_time_db';
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    $file_name = $dir . "/" . date('Y.m.d') . ".log";
    $time = microtime(true) - $start_time;
    if ($time > 1){
        $timezone  = 5;
        $cur_time = gmdate("d.m.Y H:i:s", time() + 3600*($timezone+date("I")));
        file_put_contents($file_name, $cur_time.";".$method.";"."user_id=".getCurUser()." ". $time."\n", FILE_APPEND);
    }
}

//Записывает все вызывающиеся процедуры
function _write_db_functions($sql, $params){
    $session = new Zend_Session_Namespace('Global');
    if (isset($session->is_db_func_log)){
        if ($session->is_db_func_log){
            /*
            if (($sql != "admin.menu_read_recursive('cur',:employee_id, :menu_pid, :menu_global_id)")
               && ($sql != "get_send_email_user_cnt(:employee_id) id")){
                $dir = $_SERVER['DOCUMENT_ROOT']. '/log/db_func';
                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }
                $timezone  = 5;
                $cur_time = gmdate("d.m.Y H:i:s", time() + 3600*($timezone+date("I")));
                $file_name = $dir . "/" . Zend_Session::getId() . ".log";
                file_put_contents($file_name, $cur_time." ". $sql . "<br/>" . json_encode($params) . "<br/><br/>", FILE_APPEND);
            }*/
            $dir = $_SERVER['DOCUMENT_ROOT']. '/log/db_func';
            if (!file_exists($dir)) {
                mkdir($dir, 0777, true);
            }
            $timezone  = 5;
            $cur_time = gmdate("d.m.Y H:i:s", time() + 3600*($timezone+date("I")));
            $file_name = $dir . "/" . Zend_Session::getId() . ".log";
            file_put_contents($file_name, $cur_time." ". $sql . "<br/>" . json_encode($params) . "<br/><br/>", FILE_APPEND);
        }
    }
}


//Получить только текст ошибки из DB
function _getErrorClean($method, $error){
    if (isOnlyRead() == '1'){
        return 'Недостаточно прав или другая ошибка в системе';
    }
    $re = '/{[^@]*}/';
    $str = $error;
    preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);
    if (count($matches) > 0) {
        $str = $matches[0][0];
        $str = str_replace('{', '', $str);
        $str = str_replace('}', '', $str);
    } else {
        $str = 'Произошла необработанная ошибка в БД ' . $error ;
        _write_error_db_log($method, $error);
    }
    return $str;
}
//Получить всю ошибку из DB
function _getErrorDebug($error){
    if (isOnlyRead() == '1'){
        return 'Недостаточно прав или другая ошибка в системе';
    } else {
        return $error;
    }
}

function _getBiErrorCode($error){
    $error = substr($error, 0, 4);
    if(in_array($error, array('P001', 'P002', 'P002', 'P004', 'P005', 'P006', 'P007', 'P008', 'P009', 'P010', 'P011', 'P012', 'P013', 'P014'))){
        return $error;
    }else{
        return 'P400';
    }
}

function _write_php_exception($message, $exception_trace, $params){
    $dir = $_SERVER['DOCUMENT_ROOT']. '/log/error_php';
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    $file_name = $dir . "/" . date('Y.m.d') . ".log";
    $timezone  = 5;
    $cur_time = gmdate("d.m.Y H:i:s", time() + 3600*($timezone+date("I")));
    file_put_contents($file_name, $cur_time.";".$message.";"."user_id=".getCurUser().PHP_EOL."Exception: ".json_encode($exception_trace).PHP_EOL."Params: ".json_encode($params).PHP_EOL.PHP_EOL, FILE_APPEND);
}

/**********Other common functions*************************************************************************/

//Записывает системные ошибки в лог
function _write_error_php_log($error, $method = "DEFAULT"){
    $dir = $_SERVER['DOCUMENT_ROOT']. '/log/error_php_log';
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }
    $file_name = $dir . "/" . date('Y.m.d') . ".log";
    $timezone  = 5;
    $cur_time = gmdate("d.m.Y H:i:s", time() + 3600*($timezone+date("I")));
    file_put_contents($file_name, $cur_time.";".$method.";"."user_id=".getCurUser().";Error:".$error."\n", FILE_APPEND);
}
//Идентификатор пользователя берется либо из сотрудников (employee_tab), либо из user_tab, в зависимости от типа входа в систему
function getCurUser(){
    $user_id = 0;
    if (Zend_Auth::getInstance()->hasIdentity()){
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        if (isset($identity->client_id)){
            $user_id = $identity->client_id;
        }
        if (isset($identity->employee_id)){
            $user_id = $identity->employee_id;
        }
    }
    return $user_id;
}

function getCurClient(){
    $user_id = 0;
    if (Zend_Auth::getInstance()->hasIdentity()){
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        if (isset($identity->client_id)){
            $user_id = $identity->client_id;
        }
    }
    return $user_id;
}
function getCurEmployee(){
    $user_id = 0;
    if (Zend_Auth::getInstance()->hasIdentity()){
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        if (isset($identity->employee_id)){
            $user_id = $identity->employee_id;
        }
    }
    return $user_id;
}

function getCurProvider(){
    $provider_id = 0;
    if (Zend_Auth::getInstance()->hasIdentity()){
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        if (isset($identity->provider_id)){
            $provider_id = $identity->provider_id;
        }
    }
    if ($provider_id == null){
        return 0;
    }
    return $provider_id;
}
function getCurPositionCode(){
    $position_code = '';
    if (Zend_Auth::getInstance()->hasIdentity()){
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        if (isset($identity->position_code)){
            $position_code = $identity->position_code;
        }
    }
    if ($position_code == null){
        return '';
    }
    return $position_code;
}
function getCrmCode(){
    $crm_role_code = '';
    if (Zend_Auth::getInstance()->hasIdentity()){
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        if (isset($identity->crm_role_code)){
            $crm_role_code = $identity->crm_role_code;
        }
    }
    if ($crm_role_code == null){
        return '';
    }
    return $crm_role_code;
}
function getEmployeeWarehouse(){
    $warehouse_id = 0;
    if (Zend_Auth::getInstance()->hasIdentity()){
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        if (isset($identity->warehouse_id)){
            $warehouse_id = $identity->warehouse_id;
        }
    }
    if ($warehouse_id == null){
        return 0;
    }
    return $warehouse_id;
}
function getClientPromo(){
    $promo_code = '';
    if (Zend_Auth::getInstance()->hasIdentity()){
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        if (isset($identity->promo_code)){
            $promo_code = $identity->promo_code;
        }
    }
    if ($promo_code == null){
        return '';
    }
    return $promo_code;
}
function isKpi(){
    $is_kpi = 0;
    if (Zend_Auth::getInstance()->hasIdentity()){
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        if (isset($identity->is_kpi)){
            $is_kpi = $identity->is_kpi;
        }
    }
    if ($is_kpi != 1){
        return 0;
    }
    return $is_kpi;
}

function isOnlyRead(){
    $is_only_read = 0;
    if (Zend_Auth::getInstance()->hasIdentity()){
        $identity = Zend_Auth::getInstance()->getStorage()->read();
        if (isset($identity->is_only_read)){
            $is_only_read = $identity->is_only_read;
        }
    }
    return $is_only_read;
}


//Проверка есть ли право
/*
function _check_grant_boolean($grant_code){
    $ob = new Application_Model_DbTable_System();
    $result = $ob->check_grant_boolean($grant_code);
    return $result['value'];
}*/

function set_array($a, $b){

    foreach ($b as $key=>$value){
        $a[$key] = $value;
    }
    return $a;
}

function is_checked_html($a){
    if ($a == 1 or $a == true){
        return "checked";
    } else {
        return "";
    }
}

function is_checked_vars_html($a, $b){
    if ($a == $b){
        return " checked";
    } else {
        return "";
    }
}

function is_checked_vars_html2($a, $b, $c){
    if ($a == $b or $c == 0){
        return " checked";
    } else {
        return "";
    }
}

function is_selected_html($a, $b){
    if ($a == $b){
        return " selected";
    } else {
        return "";
    }
}

function is_selected_html_multiple($needle, $array){
    if (in_array($needle, $array)){
        return " selected";
    } else {
        return "";
    }
}

function is_selected_html_disabled($a, $b){
    if ($a == $b){
        return " disabled";
    } else {
        return "";
    }
}

function is_visible($position_code){
    if (getCurPositionCode() == 'ADMIN'){
        return "";
    }
    $arr = explode(',', $position_code);
    if (in_array(getCurPositionCode(), $arr)){
        return "";
    } else {
        return " hidden ";
    }
}

function tenge($number){
    if ($number == null) return $number;
    if ($number == '') return $number;
    try{
        if ($number == round($number))
            return number_format($number, 0, '.', '&nbsp;');
        else
            return number_format($number, 2, '.', '&nbsp;');
    } catch(Exception $e){
        return $number;
    }
}

function tenge_text($number){
    if ($number == null) return $number;
    if ($number == '') return $number;
    try{
        if ($number == round($number))
            return number_format($number, 0, '.', ' ');
        else
            return number_format($number, 2, '.', ' ');
    } catch(Exception $e){
        return $number;
    }
}

function log_mobile_sync($what, $msg, $status = "OK"){
    $file = $_SERVER['DOCUMENT_ROOT']."/log/okk/".date("Y.m.d H")."-00.txt";
    file_put_contents($file, date("d.m.y H:i:s").":" .$what." ".$status."\n\n", FILE_APPEND);
    file_put_contents($file, $msg."\n\n", FILE_APPEND);
}

function getSettingByCode($setting_code){
    $ob = new Application_Model_DbTable_Dict();
    $result = $ob->get_setting_value_by_code($setting_code)['value'];
    return $result;
}

function show_is_not_null($label, $value, $tag, $label_bold = true){
    $result = '';
    if(strlen(trim($value)) > 0 || !is_null($value)){
        if($label_bold){
            $result = '<'.$tag.'><b>'.$label. '</b> '.$value.'</'.$tag.'><br>';
        }else{
            $result = '<'.$tag.'>'.$label. ' '.$value.'</'.$tag.'><br>';
        }
    }
    return $result;
}

function get_row_fields($row, $fields){
    $result = array();
    foreach ($row as $key=>$value){
        $columns = array_keys($row[$key]);
        foreach ($columns as $key2 => $value2){
            if (in_array($value2, $fields)){
                $result[$key][$value2] = $row[$key][$value2];
            }
        }
    }
    return $result;
}

function check_access($code){
    $ob = new Application_Model_DbTable_System();
    $result = $ob->check_grant_boolean($code)['value'];
    if ($result['check_access'] == 0){
        throw new Exception('Недостаточно прав ['. $result['grant_name']."]", 20403);
    };
}

function check_access_bool($code){
    $ob = new Application_Model_DbTable_System();
    $result = $ob->check_grant_boolean($code);
    return $result['value'];
}

function mycount($a){
    if ($a === null){
        return 0;
    }
    if (is_array($a)){
        return count($a);
    }
    return 0;
}

function is_uz(){
    if(getHttpHost() == 'https://smartremont.u-nrg.uz' || getHttpHost() == 'https://uz.smart-remont.kz'){
        return true;
    } else {
        return false;
    }
}

function is_ru(){
    if(getHttpHost() == 'https://osnova.smartremont.kz'){
        return true;
    } else {
        return false;
    }
}

function is_kortros(){
    if(getHttpHost() == 'https://kortros.smartremont.kz'){
        return true;
    } else {
        return false;
    }
}

function is_account(){
    if(getHttpHost() == get_account_url()){
        return true;
    } else {
        return false;
    }
}

function is_remont(){
    if(getHttpHost() == 'https://remont.smartremarket.kz'){
        return true;
    } else {
        return false;
    }
}

function is_old_smart(){
    if(getHttpHost() == 'https://smartremont.kz'){
        return true;
    } else {
        return false;
    }
}

function getUserIP() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function getErrorXml($txn_id = null, $code, $error = null){
    $doc = new DOMDocument('1.0', 'UTF-8');
    $doc->formatOutput = true;

    $root = $doc->createElement('response');
    $root = $doc->appendChild($root);

    if($txn_id != null){
        $txn_id_xml = $doc->createElement('txn_id', $txn_id);
        $root->appendChild($txn_id_xml);
    }

    $result = $doc->createElement('result', $code);
    $root->appendChild($result);

    if($error != null){
        $comment = $doc->createElement('comment', $error);
        $root->appendChild($comment);
    }

    return $doc->saveXML();
}

function checkDepartmentRole($department_role_code){
    $ob = new Application_Model_DbTable_Dict();

    $result = $ob->check_department_role($department_role_code)['value'];
    if ($result == 1){
        return true;
    }else{
        return false;
    }
}

function isUserNazarly(){
    $ob = new Application_Model_DbTable_Dict();

    $result = $ob->is_user_nazarly()['value'];
    if ($result == 1){
        return true;
    }else{
        return false;
    }
}

function getCityFilterCode(){
    $ob = new Application_Model_DbTable_Dict();

    $result = $ob->get_city_filter_code()['value'];
    return $result;

}

function getNumberString($str){
    $int = preg_replace('/[^0-9]/', '', $str);
    return $int;
}

function kcell_send_sms($data, $resource = 'messages'){
    // Prepare new cURL resource
    $ch = curl_init('https://msg.kcell.kz/api/v3/'.$resource.'/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_USERPWD, 'SmartR_smsgw3_user' . ":" . '2c5DfEQs');

// Set HTTP Header for POST request
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data))
    );

// Submit the POST request
    $result = curl_exec($ch);

// Close cURL session handle
    curl_close($ch);

    return $result;
}

function kcell_check_sms_status($message_id, $resource = 'messages', $type = 'system'){
    $ch = curl_init('https://msg.kcell.kz/api/v3/'.$resource.'/'.$message_id.'?type='.$type);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_USERPWD, 'SmartR_smsgw3_user' . ":" . '2c5DfEQs');
    $output=curl_exec($ch);
    curl_close($ch);
    return $output;
}

function generateHttpResponse($statusCode)
{
    // массив соответствий кодов статуса и сообщений
    $statusMessages = [
        200 => 'OK',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        // добавьте больше кодов и сообщений по мере необходимости
    ];

    // проверка существования кода статуса в массиве
    if (array_key_exists($statusCode, $statusMessages)) {
        // установка заголовка с кодом статуса
        header("HTTP/1.1 " . $statusCode . " " . $statusMessages[$statusCode]);

        // возвращение сообщения статуса в формате JSON
        echo json_encode(array('status' => $statusCode, 'message' => $statusMessages[$statusCode]));
    } else {
        // установка заголовка с кодом 500, если передан неподдерживаемый код статуса
        header("HTTP/1.1 500 Internal Server Error");

        // возвращение сообщения об ошибке в формате JSON
        echo json_encode(array('status' => 500, 'message' => 'Unknown status code'));
    }
}

// Функция для шифрования куки
function encryptCookie($value, $key){
    $iv = openssl_random_pseudo_bytes(16);
    $cipherText = openssl_encrypt($value, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($iv . $cipherText);
}

// Функция для дешифрования куки
function decryptCookie($cipherTextBase64, $key){
    $cipherTextBlob = base64_decode($cipherTextBase64);
    $iv = substr($cipherTextBlob, 0, 16);
    $cipherText = substr($cipherTextBlob, 16);
    return openssl_decrypt($cipherText, 'aes-256-cbc', $key, 0, $iv);
}

