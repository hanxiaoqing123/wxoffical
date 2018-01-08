<?php
include "../fc.php";
if(isset($_POST['dosubmit'])){
    $token=get_token();
    $url="https://api.weixin.qq.com/cgi-bin/groups/create?access_token={$token}";
    $jsonstr='{"group":{"name":"'.$_POST['groupname'].'"}}';
    $result=https_request($url,$jsonstr);
    $resultarr=json_decode($result,true);
    if($resultarr['group']){
       header("location:group.php");
    }
}
?>
<form action="create.php" method="post">
    分组名称：<input type="text" name="groupname" />
    <input type="submit" name="dosubmit" value="添加分组">
</form>
