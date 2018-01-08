<?php
header("Content-Type:text/html;charset=utf-8");
/**
 * Created by PhpStorm.
 * User: nahuanjie
 * Date: 2017/7/12
 * Time: 10:04
 */
$pdo=new PDO("mysql:host=w.rdc.sae.sina.com.cn;dbname=app_hld123;port=3306",'0wzonno3yw','mi5y5i2yki3i1xy3jl4iimki41jh0hhkki0zyx3i');
$pdo->exec("set names utf8");
$sql="select openid,headimgurl,nickname,province,city,utime,status from clientuser order by utime desc";
$pdos=$pdo->query($sql);
$result=$pdos->fetchAll(PDO::FETCH_ASSOC);
echo "<h1>用户会话列表</h1>";
echo "<table width='80%' border='1' cellpadding='0' cellspacing='0'>";
echo "<tr><th>头像</th><th>昵称</th><th>所在区域</th><th>关注时间</th><th>操作</th></tr>";
foreach ($result as $v){
    /*
      如果没有查看过的消息显示蓝色
      @param status 1 消息未查看 0 消息已查看
     * */
    if($v['status']==0){
        $bg="";
    }else{
        $bg="green";
    }
echo "<tr bgcolor='{$bg}'>";
echo "<td><img src=".$v['headimgurl']." width='60'></td><td>{$v['nickname']}</td><td>{$v['province']}-{$v['city']}</td><td>".date("Y-m-d H:i:s",$v['utime'])."</td><td><a href='message.php?openid=".$v['openid']."'>查看</a></td>";
echo "</tr>";
}
echo "</table>";


