<?php
/*1.API的使用
    use sinacloud\sae\Storage as Storage;
    $s = new Storage();
    var_dump($s->getObject("hxq", "1.txt"));
    $s->putObject("This is string.", "test", "string.txt", array(), array('Content-Type' => 'text/plain'));
 * */
/*2.SAE如何在本地写入文件
    1)在PATH前加'saemc://'即可把文件写到Memcache中,
    2)在PATH前加'saestor://'.$DOMAIN.'/'的方式把文件保存到Storage中
 * */
$file="saestor://hxq/log.txt";
//只能覆盖写，不能追加
file_put_contents($file,'测试');
//错误输出到屏幕
ini_set('display_errors',1);
//目前storage不支持追加写即FILE_APPEND，日志可以通过 sae_debug函数写到errorlog里去
sae_debug("测试写日志2");