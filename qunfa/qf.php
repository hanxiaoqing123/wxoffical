<?php
header("Content-Type:text/html;charset=utf-8");
include "../fc.php";
//连接数据库
$pdo=new PDO("mysql:host=w.rdc.sae.sina.com.cn;dbname=app_hld123;port=3306",'0wzonno3yw','mi5y5i2yki3i1xy3jl4iimki41jh0hhkki0zyx3i');
$pdo->exec("set names utf8");

if(empty($_GET['type']) || $_GET['type'] == "group") {
    $type = "group";
    echo "<h1>群发(按群组->";

}else{

    $type="openid";
    echo "<h1>群发(按openid列表->群发";
}


switch($_GET['message']) {
    case 'text':
        echo "文本";
        break;
    case 'voice':
        echo "语音";
        break;
    case 'image':
        echo "图片";
        break;
    case 'video':
        echo "视频";
        break;
    default:
        $_GET['message'] = "news";
        echo "图文";
        break;


}

echo '消息):</h1>';

?>


    <p>
    <h2><a href="qf.php?type=group">按组群发</a> || <a href="qf.php?type=openid">按指定用户列表群发</a></h2>
    </p>


    <p>
    <h3>
        <a href="qf.php?type=<?php echo $type ?>&message=news">图文</a> ||
        <a href="qf.php?type=<?php echo $type ?>&message=text">文本</a> ||
        <a href="qf.php?type=<?php echo $type ?>&message=voice">语音</a> ||
        <a href="qf.php?type=<?php echo $type ?>&message=image">图片</a> ||
        <a href="qf.php?type=<?php echo $type ?>&message=video">视频</a>
    </h3>
    </p>

<?php
echo '<form action="qfaction.php" method="post">';

if(empty($_GET['type']) || $_GET['type'] == "group") {
    $type = "group";
    //获取access_token
    $access_token = get_token();

    $url = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token={$access_token}";



    $result	= https_request($url);
    //将返回来的json转成数组操作
    $groups = json_decode($result, true);

    //遍历数组形成分组列表
    echo '<ul>';
    foreach($groups['groups'] as $g) {
        echo '<li><input type="radio" name="group" value="'.$g['id'].'"> '.$g['name'].'('.$g['count'].')</li>';
    }
    echo '</ul>';






}else{

    $type="openid";

    echo '<table border="1" width="60%">';

    //要全部关注的subscribe=1的
    $sql ="select * from wuser where subscribe='1'";


    $pdoS =$pdo->query($sql);
    $result=$pdoS->fetchAll(PDO::FETCH_ASSOC);


    foreach ($result as $user){
        echo '<tr>';
        echo '<td><input type="checkbox" name="openid[]" value="'.$user['openid'].'"></td>';
        echo '<td><img width="60" src="'.$user['headimgurl'].'"></td>';
        echo '<td>'.$user['nickname'].'</td>';

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

        echo '</tr>';
    }


    echo '</table>';

}


switch($_GET['message']) {
    case 'text':
        echo '请输入要群发的文本:<br><textarea name="content" rows="4" cols="40"></textarea>';

        break;
    case 'voice':
        echo '请输入要群发的语音media_id:<br><textarea name="content" rows="4" cols="40"></textarea>';
        break;
    case 'image':
        echo '请输入要群发的图片media_id:<br><textarea name="content" rows="4" cols="40"></textarea>';

        break;
    case 'video':
        echo '请输入要群发的视频media_id:<br><textarea name="content" rows="4" cols="40"></textarea>';

        break;
    default:
        echo '请输入要群发的图文media_id:<br><textarea name="content" rows="4" cols="40"></textarea>';
        break;


}

echo '<input type="hidden" name="dtype" value="'.$type.'">';
echo '<input type="hidden" name="type" value="'.$_GET['message'].'">';

echo '<br><input type="submit" name="dosubmit" value="群发">';

echo '</form>';

