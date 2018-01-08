<?php
/**
 * Created by PhpStorm.
 * User: hxq
 * Date: 2017/7/11
 * Time: 22:18
 */
/* Connect to a MySQL server  连接数据库服务器 */
$link = mysqli_connect(
    'w.rdc.sae.sina.com.cn',  /* The host to connect to 连接MySQL地址 */
    '0wzonno3yw',      /* The user to connect as 连接MySQL用户名 */
    'mi5y5i2yki3i1xy3jl4iimki41jh0hhkki0zyx3i',  /* The password to use 连接MySQL密码 */
    'app_hld123');    /* The default database to query 连接数据库名称*/

if (!$link) {
    printf("Can't connect to MySQL Server. Errorcode: %s ", mysqli_connect_error());
    exit;
}


/* Close the connection 关闭连接*/
//mysqli_close($link);