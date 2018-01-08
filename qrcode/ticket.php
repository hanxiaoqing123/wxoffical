<?php
include  "../fc.php";
/*临时二维码:创建二维码ticket
 $url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=TOKEN";
 $jsonstr="{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": 123}}}";
*/

//永久二维码：创建二维码ticket
$access_token =get_token();

$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$access_token}";

$jsonstr='{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 2}}}';

$result	= https_request($url,$jsonstr);
//将返回来的json转成数组操作
$groups = json_decode($result, true);

$ticket=$groups['ticket'];
/*通过ticket换取二维码
 提醒： 1)本接口无须登录即可调用  2)TICKET记得进行UrlEncode
  */

$url="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".urlencode($ticket);
$info=downImage($url);
$filename="wx.jpg";
file_put_contents($filename,$info);
function downImage($url){
    $curl=curl_init($url);
    curl_setopt($curl,CURLOPT_HEADER,0);
    curl_setopt($curl,CURLOPT_NOBODY,0);
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
    curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
    // 返回的内容作为变量储存，而不是直接输出
    $output=curl_exec($curl);
    curl_close($curl);
    return $output;
}
echo '<img src="'.$filename.'"/>';