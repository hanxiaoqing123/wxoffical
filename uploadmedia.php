<?php
include "fc.php";

/*临时素材:上传图片
  @param $filepath string  图片所在的绝对路径
  @param $type 媒体文件类型，分别有图片（image）、语音（voice）、视频（video）和缩略图（thumb）
 * */
function uploadimg($filepath,$type){
    $filedata=array("media"=>new CURLFile($filepath));
    $access_token=get_token();
    $url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$access_token}&type={$type}";
    $result=https_request($url,$filedata);
    return $result;
}
$filepath="C:/Users/nahuanjie/Desktop/img/6.jpg";
$type="image";
//$filepath="C:/Users/nahuanjie/Desktop/hxq/2.mp3";
$result=uploadimg($filepath,$type);
print_r($result);
/*图片
 临时素材1$media_id:g1B-LzH6fl3f38TApaWUbvPKDUPfuuL2tfmC0t34OcYcZb5AKY7JA3dOuk9cV-sz
 临时素材2$media_id:2RClngqtXg8YFFUFoBryEGfMmNBcWBCC17tIdm9eLc49PJCxtvaJQKcyyIQWCXeE
 * */

/*  获取素材总数
    $url1="https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token={$access_token}";
    $total=https_request($url1);
    echo "获取素材总数"."<br/>";
    print_r($total);
    echo "<hr/>";
 * */



