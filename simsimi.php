<?php
function callSimsimi($keyword)
{
    $params['key'] = "f8d7897a-45bd-415a-8e1f-0bd8a0adb103";
    $params['lc'] = "ch";
    $params['ft'] = "1.0";
    $params['text'] = $keyword;

    $url = "http://sandbox.api.simsimi.com/request.p?".http_build_query($params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);

    $message = json_decode($output,true);
    $result = "";
    if ($message['result'] == 100){
        $result = $message['response'];
    }else{
        $result = $message['result']."-".$message['msg'];
    }
    return $result;
}