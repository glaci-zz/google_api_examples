<?php

/*
Note:
1.because google api is https, please at least install curl on your linux server
2.this api is billing required, so please enable billing on google cloud platform before use the api
*/

//global config vars
$geo_api = 'https://www.googleapis.com/geolocation/v1/geolocate?key=';
$api_key = '';

//examples
print_r(lang_list());

//google api format:
// {
// "wifiAccessPoints": [
//   {
//    "macAddress": "01:23:45:67:89:AB",
//    "signalStrength": 8,
//    "age": 0,
//    "signalToNoiseRatio": -65,
//    "channel": 8
//   },
//   {
//    "macAddress": "01:23:45:67:89:AC",
//    "signalStrength": 4,
//    "age": 0
//   }
//  ]
// }

//scan example:
//{"macAddress": "bc:ae:c5:c2:fd:5f","signalStrength": 54,"age": 0,"channel": 11,"signalToNoiseRatio": 40}

//note:
//more scans, more accurate
//curl gis api
function geo_locate($scan){
    global $geo_api, $api_key;
    //vars
    $endpoint = $geo_api.$api_key;
    //json string
    $json = '{wifiAccessPoints:['.$scan.']}';
    //  Initiate curl
    $ch = curl_init();
    // Set The Response Format to Json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Accept: application/json', 'Content-Type: application/json'));
    // Disable SSL verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // Will return the response, if false it print the response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    // Set the url
    curl_setopt($ch, CURLOPT_URL,$endpoint);
    // Execute
    $result = curl_exec($ch);
    // Closing
    curl_close($ch);
    if($result){
        $message = $gis_ary['message'];
        //if error will have message
        if($message){
            return $message;
        }
        return $gis_ary = json_decode($result, true);
    }else{
        return $result;
    }
}






