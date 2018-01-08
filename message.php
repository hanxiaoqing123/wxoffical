<?php
include "fc.php";
$pdo=new PDO("mysql:host=w.rdc.sae.sina.com.cn;dbname=app_hld123;port=3306",'0wzonno3yw','mi5y5i2yki3i1xy3jl4iimki41jh0hhkki0zyx3i');
$openid=$_GET['openid'];
/*
 如果网页端公众号消息提交了,则将消息写入数据库
@param $who    integer 1 公众号 0 用户（默认）
 * */
if(isset($_POST['dosubmit'])){
    //发送文本消息
    if (!empty($_POST['text'])){
        sendText($openid,$_POST['text']);
        insertmessage($openid,$_POST['text'],$who="1",$mtype="text");
    }
    //发送图片消息
    if (!empty($_POST['image'])){
        sendImage($openid,$_POST['image']);
        insertmessage($openid,$_POST['image'],$who="1","image");
    }
    //发送语音消息
    if (!empty($_POST['voice'])){
        sendVoice($openid,$_POST['voice']);
        insertmessage($openid,$_POST['voice'],$who="1","voice");
    }
    //发送视频
    if (!empty($_POST['video'])){
        sendVideo($openid,$_POST['video'],$_POST['title'],$_POST['description']);
        insertmessage($openid,$_POST['video'],$who="1","video");
    }
}
/*
 更新clientuser中的status
 @param status 1 消息未查看 0 消息已查看
 * */
$sql="update clientuser set status=0 where openid='{$openid}'";
$pdo->exec($sql);
//获取用户信息
$userarr=getUserinfo($openid);
$sql="select msg,who from message where openid='{$openid}'";
$pdos=$pdo->query($sql);
$result=$pdos->fetchAll(PDO::FETCH_ASSOC);
echo "<h1>用户会话列表</h1>";
echo "<table width='600' border='1' cellpadding='0' cellspacing='0'>";
echo "<tr><th>头像</th><th>昵称</th><th>消息</th></tr>";
foreach ($result as $v){
    echo "<tr>";
    //@param $who    integer 1 公众号 0 用户（默认）
    if($v['who']==0){
        echo "<td align='left'><img src=".$userarr['headimgurl']." width='60'></td><td>{$userarr['nickname']}</td><td>{$v['msg']}</td>";
    }else{
        echo "<td  colspan='3' align='right'>{$v['msg']}：【公众号】</td>";
    }


    echo "</tr>";
}
echo "</table>";
?>
<form action="message.php?openid=<?php echo $openid?>" method="post">
    文本内容：<textarea name="text" cols="40" rows="6"></textarea><br/><br/>
     图片:<input type="text" name="image" placeholder="MEDIA_ID"/><br/><br/>
     语音:<input type="text" name="voice" placeholder="MEDIA_ID"/><br/><br/>
     视频标题:<input type="text" name="title" placeholder="标题" /><br/><br/>
     视频描述:<input type="text" name="description" placeholder="描述"/><br/><br/>
     视频:<input type="text" name="video" placeholder="MEDIA_ID"/><br/><br/>
    <input type="submit" value="回复" name="dosubmit"/>
</form>
<a href="userinfo.php">返回用户列表</a>
