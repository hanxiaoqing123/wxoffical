<?php
header("Content-Type:text/html;charset=utf-8");
//获取access_token
$access_token = get_token();
//公共函数===================================
    //新浪云分词
    function fci( $str){
        //过滤英文标点符号
        $str=preg_replace("/[[:punct:]\s]/",' ',$str);
        //过滤中文标点符号
        $str=urlencode($str);
        $str=preg_replace("/(%7E|%60|%21|%40|%23|%24|%25|%5E|%26|%27|%2A|%28|%29|%2B|%7C|%5C|%3D|\-|_|%5B|%5D|%7D|%7B|%3B|%22|%3A|%3F|%3E|%3C|%2C|\.|%2F|%A3%BF|%A1%B7|%A1%B6|%A1%A2|%A1%A3|%A3%AC|%7D|%A1%B0|%A3%BA|%A3%BB|%A1%AE|%A1%AF|%A1%B1|%A3%FC|%A3%BD|%A1%AA|%A3%A9|%A3%A8|%A1%AD|%A3%A4|%A1%A4|%A3%A1|%E3%80%82|%EF%BC%81|%EF%BC%8C|%EF%BC%9B|%EF%BC%9F|%EF%BC%9A|%E3%80%81|%E2%80%A6%E2%80%A6|%E2%80%9D|%E2%80%9C|%E2%80%98|%E2%80%99|%EF%BD%9E|%EF%BC%8E|%EF%BC%88)+/",' ',$str);
        $str=urldecode($str);

        $seg = new SaeSegment();
        $ret = $seg->segment($str, 1);
        /*
            echo "<pre>";
            print_r($ret);
            echo "</pre>";
        输出结果：Array
        (
            [0] => Array
                (
                    [word] => 新浪
                    [word_tag] => 100
                    [index] => 0
                )

            [1] => Array
                (
                    [word] => 云
                    [word_tag] => 170
                    [index] => 1
                )
        )
       * */
       // 失败时输出错误码和错误信息
        if ($ret === false){
            var_dump($seg->errno(), $seg->errmsg());
            return false;
        }else{
            foreach ($ret as $v){
                $arr[]=$v['word'];
            }
          return $arr;
        }

    }
    //获取城市编码
    function getCitycode($carr){
        $pdo=new PDO("mysql:host=w.rdc.sae.sina.com.cn;dbname=app_hld123;port=3306",'0wzonno3yw','mi5y5i2yki3i1xy3jl4iimki41jh0hhkki0zyx3i');
        $pdo->exec("set names utf8");
        $str="";
        foreach ($carr as $v){
            $str.="'".$v."',";
            $mulcity=rtrim($str,",");
        }
        $sql="select cityCode from weather where  cityName in({$mulcity})";
        $pdos=$pdo->query($sql);
        while ($row=$pdos->fetch(PDO::FETCH_ASSOC)){
           $arr[]=$row['cityCode'];
        }
        return $arr;
    }
    /*
     1.根据中国天气网获取天气
     2.获取实时天气和未来3日天气，并将返回结果封装成图文信息的数组
     * */
    function getWeatherInfo($cityCode,$num)
    {
        if($num>1){
        //
            //多个城市的话：获取实时天气
            $url = "http://www.weather.com.cn/data/sk/".$cityCode.".html";
            $output = httpRequest($url);
            $weather = json_decode($output, true);
            $info = $weather['weatherinfo'];

            $weatherArray = array();
            //形成的图文数组
            $weatherArray[] = array("Title"=>$info['city']."现在的天气预报", "Description"=>"", "PicUrl"=>"", "Url" =>"");
            if ((int)$cityCode < 101340000){
                $result = "实况 温度：".$info['temp']."℃ 湿度：".$info['SD']." 风速：".$info['WD'].$info['WSE']."级";
                $weatherArray[] = array("Title"=>str_replace("%", "﹪", $result), "Description"=>"", "PicUrl"=>"", "Url" =>"");
            }

        }else{
            //单个城市的话：获取近三日信息
            //获取六日天气
            $url = "http://m.weather.com.cn/data/".$cityCode.".html";
            $output = httpRequest($url);
            $weather = json_decode($output, true);
            $info = $weather['weatherinfo'];
            //标题
            $weatherArray[] = array("Title"=>$info['city']."近三天的天气预报", "Description"=>"", "PicUrl"=>"", "Url" =>"");
            //如果穿衣建议存在,就给用户
            if (!empty($info['index_d'])){
                $weatherArray[] = array("Title" =>$info['index_d'], "Description" =>"", "PicUrl" =>"", "Url" =>"");
            }

            $weekArray = array("日","一","二","三","四","五","六");
            $maxlength = 3;
            for ($i = 1; $i <= $maxlength; $i++) {
                $offset = strtotime("+".($i-1)." day");
                $subTitle = date("m月d日",$offset)." 周".$weekArray[date('w',$offset)]." ".$info['temp'.$i]." ".$info['weather'.$i]." ".$info['wind'.$i];
                //图文形式0
                $weatherArray[] = array("Title" =>$subTitle, "Description" =>"", "PicUrl" =>"http://discuz.comli.com/weixin/weather/"."d".sprintf("%02u",$info['img'.(($i *2)-1)]).".jpg", "Url" =>"");
            }
        }
        return $weatherArray;
    }
    /*
     http请求
     * */
    function httpRequest($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        if ($output === FALSE){
            return "cURL Error: ". curl_error($ch);
        }
        return $output;
    }

    /*
     https请求
     通过https 中的get 或 post
     * */
    function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
    /*
     获取access_token
     * */
    function get_token() {
        /*
         微信测试号
         * */
         $appid="wx1fde37e102ef04cb";
         $secret="2a74697a6f3154d33fad8d821c7469b0";
        //个人订阅号
        //$appid="wxcdc9c81a659678d1";
        //$secret="dfe293908bd81e0e69a74d6b20867525";
        $json=https_request("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}");
        $arr= json_decode($json, true);
        $access_token = $arr["access_token"];
        return $access_token;
    }
    /*
    获取用户信息，将用户信息写入数据库
    @param $openid string  用户的标识, 通过openid访问获取用户的接口获取用户的全部信息
    * */
    function getUserinfo($openid){
        //$access_token=get_token();
        global $access_token;
        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$openid}&lang=zh_CN";
        //http请求 GET方式
        $json=https_request($url);
        $arr=json_decode($json,true);
        return $arr;
    }
    /*
      用户一回话，就将用户信息存入clientuser表中
      @param $jsonarr string 用户数组信息
     * */
    function insertuser($jsonarr){
        $pdo=new PDO("mysql:host=w.rdc.sae.sina.com.cn;dbname=app_hld123;port=3306",'0wzonno3yw','mi5y5i2yki3i1xy3jl4iimki41jh0hhkki0zyx3i');
        $pdo->exec("set names utf8");
        $sql="insert into  clientuser(openid,nickname,sex,city,province,headimgurl,utime) values('{$jsonarr["openid"]}','{$jsonarr["nickname"]}',
        '{$jsonarr["sex"]}','{$jsonarr["city"]}','{$jsonarr["province"]}','{$jsonarr["headimgurl"]}','{$jsonarr["subscribe_time"]}')";
        $pdo->exec($sql);
    }
    /*
    用户一回话，就将消息存入message消息列表中
    @param $openid string  普通用户的标识
    @param $text   string  发送的消息
    @param $who    integer 1 公众号 0 用户（默认）
    @param $mtype  string  消息类型
    * */
    function insertmessage($openid,$text,$who="0",$mtype="text"){
        $pdo=new PDO("mysql:host=w.rdc.sae.sina.com.cn;dbname=app_hld123;port=3306",'0wzonno3yw','mi5y5i2yki3i1xy3jl4iimki41jh0hhkki0zyx3i');
        $pdo->exec("set names utf8");
        $sql="insert into message(openid,msg,who,utime,mtype) values ('{$openid}','{$text}','$who','".time()."','{$mtype}')";
        $pdo->exec($sql);
        //更新clientuser中的时间  status 1未查看 0 已查看
        $sql="update clientuser set utime=".time().",status='1' where openid='{$openid}'";
        $pdo->exec($sql);
    }
    /*
     将数组转成json：主要针对转化json过程中中文会被转化为unicode编码,但是微信服务器不识别
     * */
    function my_json_encode($p, $type="text") {
        if (PHP_VERSION >= '5.4') {
            $str = json_encode($p, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } else {
            switch ($type)
            {
                case 'text':
                    isset($p['text']['content']) && ($p['text']['content'] = urlencode($p['text']['content']));
                    break;
            }
            $str = urldecode(json_encode($p));
        }
        return $str;
    }
    //客服向用户发送文本消息
    function sendText($openid,$keyword){
       $access_token=get_token();
       $url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
       /*
        {
            "touser":"OPENID",
            "msgtype":"text",
            "text":
            {
                 "content":"Hello World"
            }
        }
        * */
       $msgarr=[
           "touser" => $openid,
            "msgtype"=>"text",
            "text"=>["content"=>$keyword],
       ];
       $jsonarr=my_json_encode($msgarr,"text");
       $result=https_request($url,$jsonarr);
    }
//客服向用户发送图片消息
function sendImage($openid,$media_id){
    $access_token=get_token();
    $url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
    /*
    {
        "touser":"OPENID",
        "msgtype":"image",
        "image":
        {
            "media_id":"MEDIA_ID"
        }
    }
     * */
    $imagearr=[
        "touser" => $openid,
        "msgtype"=>"image",
        "image"=>["media_id"=>$media_id],
    ];
    $jsonarr=json_encode($imagearr);
    $result=https_request($url,$jsonarr);
}
//客服向用户发送语音消息
function sendVoice($openid,$media_id){
    $access_token=get_token();
    $url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
    /*
    {
        "touser":"OPENID",
        "msgtype":"voice",
        "voice":
        {
            "media_id":"MEDIA_ID"
        }
    }
     * */
    $voicearr=[
        "touser" => $openid,
        "msgtype"=>"voice",
        "voice"=>["media_id"=>$media_id],
    ];
    $jsonarr=json_encode($voicearr);
    $result=https_request($url,$jsonarr);
}
//客服向用户发送视频消息
function sendVideo($openid,$media_id,$title="",$description=""){
    $access_token=get_token();
    $url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
    /*
    {
        "touser":"OPENID",
        "msgtype":"video",
        "video":
        {
          "media_id":"MEDIA_ID",
          "thumb_media_id":"MEDIA_ID",
          "title":"TITLE",
          "description":"DESCRIPTION"
        }
    }
     * */
    $videoarr=[
        "touser" => $openid,
        "msgtype"=>"video",
        "video"=>[
            "media_id"=>$media_id,
            "title"=>$title,
            "description"=>$description,
        ],
    ];
    $jsonarr=json_encode($videoarr);
    $result=https_request($url,$jsonarr);
}

/*
 1.用户分组管理
 2.生成带参数的二维码功能
 * */
//通过扫描二维码添加用户进入指定的组， 能数为一个组的ID, 和这个关注用户的openid
function adduser( $openid, $groupid=0, $subscribe=true) {
    //连接数据库
    $pdo=new PDO("mysql:host=w.rdc.sae.sina.com.cn;dbname=app_hld123;port=3306",'0wzonno3yw','mi5y5i2yki3i1xy3jl4iimki41jh0hhkki0zyx3i');
    $pdo->exec("set names utf8");
    //使用全局的access_token
    global $access_token;
    //如果参数subscribe=true就移到分组，否则只在本数地加个用户
    if($subscribe) {
        //接口是移动组的接口， 如果关注时，有指定组的参数，直接将用户分到指定的组中
        $url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token={$access_token}";

        //参数post json
        $jsonstr = '{"openid":"'.$openid.'","to_groupid":'.$groupid.'}';

        $result = https_request($url, $jsonstr);
    }

    //通过自己写的一个函数getUserInfo()获取用户的详细信息
    $user = getUserinfo($openid);
    $user['groupid'] = $groupid;


    //如果已经是关注过又取消的，则已经有记录了， 有记录了更新关注字段、组和时间即可
    $sql = "select count(*) as num from wuser where openid='{$openid}'";
    $pdoS=$pdo->query($sql);
    $arr=$pdoS->fetch(PDO::FETCH_ASSOC);
    //如果根据openid在表中查到有记录，就不要再插入数据
    if($arr['num']  > 0) {
        $sql = "update wuser set subscribe='1', groupid='{$groupid}', subscribe_time='{$user['subscribe_time']}' where openid='{$openid}'";
        $pdo->exec($sql);
    }else{
        //第一次关注时加一条记录

        $sql = "insert into wuser(openid, groupid, subscribe, nickname, sex, city, country, province, headimgurl, subscribe_time) values('{$user['openid']}','{$user['groupid']}','{$user['subscribe']}','{$user['nickname']}','{$user['sex']}','{$user['city']}','{$user['country']}','{$user['province']}','{$user['headimgurl']}','{$user['subscribe_time']}')";
        $result=$pdo->exec($sql);
    }
}



//通过指定openid取消用户表的关注
function deluser($openid) {
    //连接数据库
    $pdo=new PDO("mysql:host=w.rdc.sae.sina.com.cn;dbname=app_hld123;port=3306",'0wzonno3yw','mi5y5i2yki3i1xy3jl4iimki41jh0hhkki0zyx3i');
    $pdo->exec("set names utf8");
    $sql = "update wuser set subscribe='0' where openid='{$openid}'";
    $pdo->exec($sql);
}


//移动用户分组，同步本地数据库
function modgroup($openid, $groupid) {
    //连接数据库
    $pdo=new PDO("mysql:host=w.rdc.sae.sina.com.cn;dbname=app_hld123;port=3306",'0wzonno3yw','mi5y5i2yki3i1xy3jl4iimki41jh0hhkki0zyx3i');
    $pdo->exec("set names utf8");
    $sql = "update wuser set groupid='{$groupid}' where openid='{$openid}'";
    $pdo->exec($sql);
}

