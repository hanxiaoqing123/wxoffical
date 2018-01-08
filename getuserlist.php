<?php
include  "fc.php";
$access_token=get_token();
$url="https://api.weixin.qq.com/cgi-bin/user/get?access_token={$access_token}";
//获取关注者列表
/*
 {
  "total": 3,
  "count": 3,
  "data": {
    "openid": [
      "okzJPwzhK_h3vQH4OGtIgi-g0-c8",
      "okzJPwyDFLXcTpCt3iFmz7KL60rU",
      "okzJPw1DV4dQc86TldPmPaZ28G9U"
    ]
  },
  "next_openid": "okzJPw1DV4dQc86TldPmPaZ28G9U"
}
 * */
$result=httpRequest($url);
$listarr=json_decode($result,true);
$openidarr=$listarr['data']['openid'];
echo "<h1>关注者列表</h1>";
echo "<table width='600' border='1' cellpadding='0' cellspacing='0'>";
echo "<tr><th>序号</th><th>昵称</th><th>微信号</th></tr>";
foreach ($openidarr as $k=>$v){
    echo "<tr>";
    $url1="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$v}&lang=zh_CN";
    $result1=httpRequest($url1);
    $userarr=json_decode($result1,true);
    $num=$k+1;
    echo "<td>{$num}</td><td>{$userarr['nickname']}</td><td>$v</td>";
    echo "</tr>";
}
echo "</table>";

