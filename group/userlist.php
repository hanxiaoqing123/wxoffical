<?php
error_reporting(E_ALL);
header("Content-Type:text/html;charset=utf-8");
//连接数据库
$pdo=new PDO("mysql:host=w.rdc.sae.sina.com.cn;dbname=app_hld123;port=3306",'0wzonno3yw','mi5y5i2yki3i1xy3jl4iimki41jh0hhkki0zyx3i');
$pdo->exec("set names utf8");

echo "<h1>用户组：{$_GET['name']}({$_GET['count']})的用户如下：</h1>";

echo '<table border="1" width="60%">';

//要全部关注的subscribe=1的
$sql ="select * from wuser where groupid = '{$_GET['groupid']}' and subscribe='1'";
$pdoS=$pdo->query($sql);
$arr=$pdoS->fetchAll(PDO::FETCH_ASSOC);
echo '<tr>';
echo '<th>头像</th><th>用户名</th><th>OpenId</th><th>性别</th><th>国家</th><th>省份</th><th>城市</th><th>关注时间</th><th>操作</th>';
echo '</tr>';

foreach ($arr as $user) {
    echo '<tr>';
    echo '<td><img width="60" src="'.$user['headimgurl'].'"></td>';
    echo '<td>'.$user['nickname'].'</td>';
    echo '<td>'.$user['openid'].'</td>';
    echo '<td>';

    switch($user['sex']){
        case 1:
            echo "男";
            break;
        case 2:
            echo "女";
            break;
        case 0:
            echo "末知";
            break;
    }

    echo '</td>';
    echo '<td>'.$user['country'].'</td>';
    echo '<td>'.$user['province'].'</td>';
    echo '<td>'.$user['city'].'</td>';
    echo '<td>'.date("Y-m-d H:i:s",$user['subscribe_time']).'</td>';
    echo '<td><a href="togroup.php?openid='.$user['openid'].'">移动到其它组</a></td>';
    echo '</tr>';
}


echo '</table>';

?>
<br><a href="group.php">返回组列表</a><br>
