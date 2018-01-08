<?php
include "../fc.php";

	//获取access_token
	$access_token =get_token();

	$url = "https://api.weixin.qq.com/cgi-bin/groups/get?access_token={$access_token}";

	$result	= https_request($url);
	//将返回来的json转成数组操作
	$groups = json_decode($result, true);

	//遍历数组形成分组列表
	echo '<ul>';
		foreach($groups['groups'] as $g) {
            echo '<li><a href="userlist.php?groupid='.$g['id'].'&name='.$g['name'].'&count='.$g['count'].'">'.$g['name'].'('.$g['count'].')</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="mgroup.php?name='.$g['name'].'&id='.$g['id'].'">修改</a></li>';
        }
	echo '</ul>';


	echo '<br><a href="create.php">创建分组</a>';
	echo  "&nbsp;&nbsp;&nbsp;&nbsp;";
	echo '<a href="togroup.php">移动分组</a>';
?>