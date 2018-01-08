<?php
include "function.php";
$access_token = get_token();
$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;
$jsonmenu=my_json_encode($_POST);
$result =https_request($url, $jsonmenu);
$arr=json_decode($result,true);
if($arr['errmsg']=="ok"){
    echo 1;
}else{
    echo 2;
}

