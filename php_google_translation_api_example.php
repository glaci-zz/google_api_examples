<?php

/*
Note:
1.because google api is https, please at least install curl on your linux server
2.this api is billing required, so please enable billing on google cloud platform before use the api
*/

//global config vars
$trans_api = 'https://www.googleapis.com/language/translate/v2?';
$api_key = '';

//en to zh-TW
print_r(google_trans('en', 'zh-TW', 'morning'));

//for translation
function google_trans($src, $to, $str){
    global $trans_api, $api_key;
    //vars
    $src = trim($src);
    $to = trim($to);
    $str = urlencode($str);
    //check input
    if(!$src || !$to || !$str){
        return 'please check input!';
    }
    $param_str = '';
    $count = 0;
    $params = array(
        //'callback'    => false,
        'format'      => 'text',
        'key'         => $api_key,
        //'prettyprint' => false,
        'q'           => $str,
        'source'      => $src,
        'target'      => $to,
    );
    //connect string
    foreach($params as $k => $v){
        if($count > 0) $param_str .= '&';
        $param_str .= $k.'='.$v;
        $count++;
    }
    //query api
    $url = $trans_api.$param_str;
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
            return $data_ary['data']['translations'][0]['translatedText'];
        }else{
            //error
            return $data_ary['messages'];
        }
    }else{
        return $result; //could be false or error
    }
}






