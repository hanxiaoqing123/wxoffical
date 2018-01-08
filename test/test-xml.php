<?php
$str=<<<XML

<xml>
 <ToUserName><![CDATA[开发者微信号]]></ToUserName>
 <FromUserName><![CDATA[发送方账号]]></FromUserName>
 <CreateTime>1348831860</CreateTime>
 <MsgType><![CDATA[text]]></MsgType>
 <Content><![CDATA[this is a test]]></Content>
 <MsgId>1234567890123456</MsgId>
 </xml>
XML;
//将XML转化为php可操作的对象
$xml=simplexml_load_string($str,'SimpleXMLElement',LIBXML_NOCDATA);
//var_dump($xml);
echo $xml->ToUserName;
echo "<br/>";
echo $xml->Content;
