<?php
$pdo=new PDO("mysql:host=w.rdc.sae.sina.com.cn;dbname=app_hld123;port=3306",'0wzonno3yw','mi5y5i2yki3i1xy3jl4iimki41jh0hhkki0zyx3i');
$pdo->exec("set names utf8");
$pdoS=$pdo->query("select * from news");
$arr=$pdoS->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($arr);
echo "</pre>";