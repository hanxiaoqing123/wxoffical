<?php
require_once("fc.php");
define("TOKEN",'weixin');
$wechatobj=new WeChat();
//检验是token验证还是接受消息
if(!isset($_GET['echostr'])){
    //如果没有通过GET收到echostr字符串， 说明不是再使用token验证
    $wechatobj->responseMsg();
}else{
    //开发者通过检验signature对请求进行校验（下面有校验方式）。若确认此次GET请求来自微信服务器，请原样返回echostr参数内容
    $wechatobj->valid();
}
//声明一个Wechat的类， 处理接收消息， 接收事件， 响应各种消息， 以及token验证
class WeChat{
    //验证消息的确来自微信服务器：验证签名
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // 字典排序
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        //调用checkSignature方法进行用户（开发者）数字签名验证
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
        
    }
    //专门用来响应信息
    public function responseMsg()
    {
        //接收的xml
        $postStr=$GLOBALS['HTTP_RAW_POST_DATA'];
        if(!empty($postStr)){
            //将接收到的XML字符串写入日志， 用R标记表示接收消息
            $this->logger("R \n".$postStr);
            //接收到的消息写入日志
            $postObj=simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);
            $RX_TYPE=trim($postObj->MsgType);
            //返回的结果消息xml
            $resultStr="";
            switch ($RX_TYPE){
                case 'event':
                    $resultStr=$this->receiveEvent($postObj);
                    break;
                case 'text':
                    $resultStr=$this->receiveText($postObj);
                    break;
                case 'voice':
                    $resultStr=$this->receiveVoice($postObj);
                    break;
                case 'image':
                    $resultStr=$this->receiveImage($postObj);
                    break;
                case 'link':
                    $resultStr=$this->receiveLink($postObj);
                    break;
                case 'video':
                    $resultStr=$this->receiveVideo($postObj);
                    break;
                case 'location':
                    $resultStr=$this->receiveLocation($postObj);
                    break;
                default:
                    $resultStr="unknow msg type:".$RX_TYPE;
                    break;

            }
            //将响应的消息再次写入日志， 使用T标记响应的消息！
            $this->logger("T \n".$resultStr);
            //输出消息给微信
            echo $resultStr;
        }
        else{
            //如果没有消息则输出空，并退出
            echo "";
            exit;
        }

        
    }
    //关注/取消关注事件
    private function receiveEvent($object)
    {
        $event=$object->Event;
        switch ($event){
            case "subscribe":
                $content="感谢您关注欢乐豆的账号";
                //$content="欢迎关注欢乐豆".PHP_EOL."小i为您服务！";
                //扫描带参数二维码事件=============================================
                $openid=$object->FromUserName;
                /*
                 $scene=str_replace("qrscene_","",$object->EventKey);
                //移动分组
                $access_token=get_token();
                $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token={$access_token}";

                //参数post json
                $jsonstr = '{"openid":"'.$openid.'","to_groupid":'.$scene.'}';

                $result = https_request($url, $jsonstr);

                //如果用户传来EventKey事件， 则是扫描二维码的
                $content .= (!empty($object->EventKey))?"\n来自二维码场景 ".$object->EventKey:"";
                 * */
                if(!empty($object->EventKey)){
                    $groupid=str_replace("qrscene_","",$object->EventKey);
                    adduser($openid,$groupid);
                    //如果用户传来EventKey事件， 则是扫描二维码的
                    $content .= "\n来自二维码场景 ".$groupid;

                }else{
                    //扫描自带的二维码（不带参数）  未分组 0   黑名单 1  星标 2
                    adduser($openid,0);
                }

            break;
            case "unsubscribe":
                $content="取消关注";
            break;
            case "CLICK":
                $content=$object->EventKey;
                break;
            case "VIEW":
                $content=$object->EventKey;
                break;
            default:
                $content = "receive a new event: ".$event;
                break;
        }
        $result = $this->transmitText($object,$content);
        return  $result;
    }
    //小黄机器人自动回复
    private function receiveText1($object)
    {
        $keyword = trim($object->Content);
        include('simsimi.php');
        $contentStr = callSimsimi($keyword);
        $resultStr = $this->transmitText($object, $contentStr);
        return $resultStr;
    }
    //小i机器人 智能回复
    private function receiveText2($object)
    {
        $keyword = trim($object->Content);
        include("xiaoi.php");
        $content = getXiaoiInfo($object->FromUserName, $keyword);
        $result = $this->transmitText($object, $content);
        return $result;
    }
    /*
     1.接收消息并回复
     2.接收的消息类型有：文本、语音、图片、视频、位置、链接
     * */
    //接收文本消息
    private function receiveText($object)
    {
        include "getsn.php";
        //从接收到的消息中获取用户输入的文本内容， 作为一个查询的关键字， 使用trim()函数去两边的空格
        $keyword = trim($object->Content);
        /*中国天气网获取天气
        $keyarr=fci($keyword);
        $codearr=getCitycode($keyarr);
        if(is_array($codearr)){
            $content=[];
            foreach ($codearr as $v){
                $content=array_merge($content, getWeatherInfo($v,count($codearr)));
            }
            $result = $this->transmitNews($object, $content);
        }
         * */

        //自动回复模式
        if (strstr($keyword, "天气") || strstr($keyword, "成都")){
            $content =getWeatherInfo1("成都");
        }else if (strstr($keyword, "文本")){
            $content = "这是个文本消息";

        }else if (strstr($keyword, "单图文")){

            $content = array();
            $content[] = array("Title"=>"小规模低性能低流量网站设计原则",  "Description"=>"单图文内容", "PicUrl"=>"http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0", "Url" =>"http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd");

        }else if (strstr($keyword, "图文") || strstr($keyword, "多图文")){
            $content = array();
            $pdo=new PDO("mysql:host=w.rdc.sae.sina.com.cn;dbname=app_hld123;port=3306",'0wzonno3yw','mi5y5i2yki3i1xy3jl4iimki41jh0hhkki0zyx3i');
            $pdo->exec("set names utf8");
            $pdoS=$pdo->query("select Title,Description,PicUrl,Url from news");
            $content=$pdoS->fetchAll(PDO::FETCH_ASSOC);
            //$content[] = array("Title"=>"多图文1标题", "Description"=>"动手构建站点的时候，不要到处去问别人该用什么，什么熟悉用什么，如果用自己不擅长的技术手段来写网站，等你写完，黄花菜可能都凉了。", "PicUrl"=>"http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0", "Url" =>"http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd");
            //$content[] = array("Title"=>"多图文2标题", "Description"=>"动手构建站点的时候，不要到处去问别人该用什么，什么熟悉用什么，如果用自己不擅长的技术手段来写网站，等你写完，黄花菜可能都凉了。", "PicUrl"=>"http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0", "Url" =>"http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd");
            //$content[] = array("Title"=>"多图文3标题", "Description"=>"动手构建站点的时候，不要到处去问别人该用什么，什么熟悉用什么，如果用自己不擅长的技术手段来写网站，等你写完，黄花菜可能都凉了。", "PicUrl"=>"http://mmbiz.qpic.cn/mmbiz/2j8mJHm8CogqL5ZSDErOzeiaGyWIibNrwrVibuKUibkqMjicCmjTjNMYic8vwv3zMPNfichUwLQp35apGhiciatcv0j6xwA/0", "Url" =>"http://mp.weixin.qq.com/s?__biz=MjM5NDAxMDEyMg==&mid=201222165&idx=1&sn=68b6c2a79e1e33c5228fff3cb1761587#rd");
        }else if (strstr($keyword, "音乐")){
            $content = array();
            $content = array("Title"=>"小歌曲你听听", "Description"=>"歌手：欢乐豆", "MusicUrl"=>"http://hld123.applinzi.com/2.mp3", "HQMusicUrl"=>"http://hld123.applinzi.com/2.mp3");
        }else{
            $content = date("Y-m-d H:i:s",time())."\n技术支持 欢乐豆,您可以输入天气，音乐，图文等词进行测试";
        }
        //如果内容是数组格式，则转为化图文，否则转化为普通文本
        if(is_array($content)){
            if (isset($content[0]['PicUrl'])){
                $result = $this->transmitNews($object, $content);
            }else if (isset($content['MusicUrl'])){
                $result = $this->transmitMusic($object, $content);
            }
        }else{

            //将openid作为参数，使用getUserinfo()函数处理,获取用户数组信息
            $jsonarr=getUserinfo($object->FromUserName);
            //用户一回话，就将用户信息存入clientuser表中
            insertuser($jsonarr);
            //用户一会话，就将消息存入message消息列表中
            insertmessage($object->FromUserName,$keyword);
            $result = $this->transmitText($object, $content);
        }
        return $result;
    }
    //接收图片消息
    private function receiveImage($object)
    {
        $content = array("MediaId"=>$object->MediaId);
        $result = $this->transmitImage($object, $content);
        return $result;
    }

    //接收位置消息
    private function receiveLocation($object)
    {
        $content = "你发送的是位置，纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //接收语音消息
    private function receiveVoice($object)
    {

        /*

            //如果开启语言识别功能， 就可以使用这个
        if (isset($object->Recognition) && !empty($object->Recognition)){
            $content = "你刚才说的是：".$object->Recognition;
            $result = $this->transmitText($object, $content);
        }else{
            $content = "未开启语音识别功能或者识别内容为空";
             $result = $this->transmitText($object, $content);
        }


        */

        //如果开启语言识别功能， 就可以使用这个
        if (isset($object->Recognition) && !empty($object->Recognition)){
            //$content = "你刚才说的是：".$object->Recognition;
            //通过语言识别，返回的文件放到分词函数中 注意：这里识别后的文字要用trim去掉空格
            $text =trim($object->Recognition);
            //分词
            $carr=fci($text);
            //转为城市code
            $codearr=getCitycode($carr);
            if(empty($codearr)){
                $content="抱歉，没有找到你说的：".$text;
                $result = $this->transmitText($object, $content);
            }
            if(is_array($codearr)){
                $content=[];
                foreach ($codearr as $v){
                    $content=array_merge($content, getWeatherInfo($v,count($codearr)));
                }
                $result = $this->transmitNews($object, $content);
            }


        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }

        return $result;
    }
    //接收视频消息
    private function receiveVideo($object)
    {
        $content = array("MediaId"=>$object->MediaId, "Title"=>"this is a test", "Description"=>"pai pai");
        $result = $this->transmitVideo($object, $content);
        return $result;
    }

    //接收链接消息
    private function receiveLink($object)
    {
        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $result = $this->transmitText($object, $content);
        return $result;
    }
    /*
     1.将要回复的消息转化成xml格式
     2.接收的消息类型有：文本、语音、图片、视频、图文、音乐
     * */
    //回复文本消息
    private function transmitText($object,$content)
    {
        $xmlTpl=<<<XML
            <xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
            </xml>
XML;
        $result=sprintf($xmlTpl,$object->FromUserName,$object->ToUserName,time(),$content);
        return $result;
    }
    //回复图文消息：单图文和多图文
    public function transmitNews($object,$newsArray)
    {
        if(!is_array($newsArray)){
            return "";
        }
        $itemTpl=<<<ITEM
        <item>
        <Title><![CDATA[%s]]></Title> 
        <Description><![CDATA[%s]]></Description>
        <PicUrl><![CDATA[%s]]></PicUrl>
        <Url><![CDATA[%s]]></Url>
        </item>
ITEM;
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str.=sprintf($itemTpl,$item['Title'],$item['Description'],$item['PicUrl'],$item['Url']);
        }
        $xmlTpl=<<<MSG
        <xml>
        <ToUserName><![CDATA[%s]]></ToUserName>
        <FromUserName><![CDATA[%s]]></FromUserName>
        <CreateTime>%s</CreateTime>
        <MsgType><![CDATA[news]]></MsgType>
        <ArticleCount>%s</ArticleCount>
        <Articles>%s</Articles>
        </xml>
MSG;
        $result=sprintf($xmlTpl,$object->FromUserName,$object->ToUserName,time(),count($newsArray),$item_str);
        return $result;
    }
    //回复音乐消息
    private function transmitMusic($object, $musicArray)
    {
        $itemTpl = "<Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
</Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }
    //回复图片消息
    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
    <MediaId><![CDATA[%s]]></MediaId>
</Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复语音消息
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
    <MediaId><![CDATA[%s]]></MediaId>
</Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[voice]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //回复视频消息
    private function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
    <MediaId><![CDATA[%s]]></MediaId>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
</Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['Title'], $videoArray['Description']);

        $xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[video]]></MsgType>
$item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    //写日志
    private function logger($log_content){
       $max_size=10000;
       //$log_filename="log.xml";
        $log_filename="saestor://hxq/log.xml";
       if(file_exists($log_filename) && abs(filesize($log_filename))>$max_size ){
           unlink($log_filename);
       }else{
          // file_put_contents($log_filename,date("H:i:s")." ".$log_content.PHP_EOL,FILE_APPEND);
          //新浪云不支持追加，只能覆盖，日志可以通过 sae_debug函数写到errorlog里去
          //file_put_contents($log_filename,date("H:i:s")." ".$log_content.PHP_EOL);
          sae_debug(date("H:i:s")." ".$log_content.PHP_EOL);
       }
    }



}