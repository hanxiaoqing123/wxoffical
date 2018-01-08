<?php
        /*
         百度天气接口
         echo getsn("深圳");
         结果：81a2fd6a6c181bab3f54519d82eae56e
         * */
        function getsn($cityName){
            //$ak = 'xaXQvU7Fe80ktd4rt2qn3otUyCFfLQu9';
            //$sk = '2FFO8U8cY1MPtWuFXFCHaop4A8KMSgt9';
            $ak = 'WT7idirGGBgA6BNdGM36f3kZ';
            $sk = 'uqBuEvbvnLKC8QbNVB26dQYpMmGcSEHM';
            $url = 'http://api.map.baidu.com/telematics/v3/weather?ak=%s&location=%s&output=%s&sn=%s';
            $uri = '/telematics/v3/weather';
            $location = $cityName;
            $output = 'json';
            $querystring_arrays = array(
                'ak' => $ak,
                'location' => $location,
                'output' => $output
            );
            $querystring = http_build_query($querystring_arrays);
            $sn = md5(urlencode($uri.'?'.$querystring.$sk));
            return $sn;
        }
        /*
        1.百度地图提供的天气预报查询接口
         * */
        function getWeatherInfo1($cityName)
        {
            //$ak = 'xaXQvU7Fe80ktd4rt2qn3otUyCFfLQu9';
            //$sk = '2FFO8U8cY1MPtWuFXFCHaop4A8KMSgt9';
            $ak = 'WT7idirGGBgA6BNdGM36f3kZ';
            $sk = 'uqBuEvbvnLKC8QbNVB26dQYpMmGcSEHM';
            $url = 'http://api.map.baidu.com/telematics/v3/weather?ak=%s&location=%s&output=%s&sn=%s';
            $uri = '/telematics/v3/weather';
            $location = $cityName;
            $output = 'json';
            $querystring_arrays = array(
                'ak' => $ak,
                'location' => $location,
                'output' => $output
            );
            $querystring = http_build_query($querystring_arrays);
            $sn = md5(urlencode($uri.'?'.$querystring.$sk));
            $targetUrl = sprintf($url, $ak, urlencode($location), $output, $sn);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $targetUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($result, true);
            if ($result["error"] != 0){
                return $result["status"];
            }
            $curHour = (int)date('H',time());
            $weather = $result["results"][0];
            $weatherArray[] = array("Title" =>$weather['currentCity']."天气预报", "Description" =>"", "PicUrl" =>"", "Url" =>"");
            //如果穿衣建议存在,就给用户
            if (!empty($weather['index'][0]['title'])){
                $wear=$weather['index'][0]['title'].PHP_EOL.$weather['index'][0]['zs']." ".$weather['index'][0]['tipt']." ".$weather['index'][0]['des'];
                $weatherArray[] = array("Title" =>$wear, "Description" =>"", "PicUrl" =>"", "Url" =>"");
            }
            for ($i = 0; $i < count($weather["weather_data"]); $i++) {
                $weatherArray[] = array("Title"=>
                    $weather["weather_data"][$i]["date"]."\n".
                    $weather["weather_data"][$i]["weather"]." ".
                    $weather["weather_data"][$i]["wind"]." ".
                    $weather["weather_data"][$i]["temperature"],
                    "Description"=>"",
                    "PicUrl"=>(($curHour >= 6) && ($curHour < 18))?$weather["weather_data"][$i]["dayPictureUrl"]:$weather["weather_data"][$i]["nightPictureUrl"], "Url"=>"");
            }
            return $weatherArray;
        }