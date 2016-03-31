<?php

/*
Note:
1.because google api is https, please at least install curl on your linux server
2.this api is billing required, so please enable billing on google cloud platform before use the api
*/

//global config vars
$lang_api = 'https://www.googleapis.com/language/translate/v2/languages?';
$api_key = '';

//examples
print_r(lang_list());

//for get supported languages list
function lang_list(){
    //vars
    global $lang_api, $api_key;
    $params = array(
        'key'         => $api_key,
    );
    $param_str = '';
    $count = 0;
    //connect string
    foreach($params as $k => $v){
        if($count > 0) $param_str .= '&';
        $param_str .= $k.'='.$v;
        $count++;
    }
    //query api
    $url = $lang_api.$param_str;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    //get result
    if($result){
        $data_ary = json_decode($result, true);
        if(is_array($data_ary['data']) && count($data_ary['data']) > 0){
            return $data_ary['data']['languages'];
        }else{
            //error
            return $data_ary['messages'];
        }
    }else{
        return $result; //could be false or error
    }
}






