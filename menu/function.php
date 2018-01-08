<?php
function https_request($url,$data=null){
    //初始化连接句柄
    $curl=curl_init();
    //设置CURL选项
    curl_setopt($curl,CURLOPT_URL,$url);
    //如果是https请求，不验证证书和hosts
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
    if(!empty($data)){
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
    }
    //1 返回结果 0直接输出(默认)
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    //执行并获取结果
    $output=curl_exec($curl);
    //释放连接句柄
    curl_close($curl);
    return $output;
}

//my_json_decode() 将数组转成json：主要针对转化json过程中中文会被转化为unicode编码,但是微信服务器不识别
function my_json_encode($p, $type="text") {
    if (PHP_VERSION >= '5.4') {
        $str = json_encode($p, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    } else {
        switch ($type)
        {
            case 'text':
                isset($p['text']['content']) && ($p['text']['content'] = urlencode($p['text']['content']));
                break;
        }
        $str = urldecode(json_encode($p));
    }
    return $str;
}
//获取access_token
function get_token() {



    $appid="wx1fde37e102ef04cb";


    $secret="2a74697a6f3154d33fad8d821c7469b0";

    $json=https_request("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}");

    $arr= json_decode($json, true);

    $access_token = $arr["access_token"];

    return $access_token;
}