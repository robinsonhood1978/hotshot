<?php
header('Content-Type:text/json;charset=utf-8');
function get($url,$header) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //TRUE 将curl_exec()获取的信息以字符串返回，而不是直接输出。

    
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);

    //curl_setopt($ch, CURLOPT_HEADER, 1); //返回response头部信息
    curl_setopt($ch, CURLINFO_HEADER_OUT, true); //TRUE 时追踪句柄的请求字符串，从 PHP 5.1.3 开始可用。这个很关键，就是允许你查看请求header

    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);

    //echo curl_getinfo($ch, CURLINFO_HEADER_OUT); //官方文档描述是“发送请求的字符串”，其实就是请求的header。这个就是直接查看请求header，因为上面允许查看
	if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
		curl_close($ch);
        return $result;
    }
    else{
    	curl_close($ch);
    	return NULL;
    } 
}
//$lat = -41.286653;
//$lng = 174.774779;
$lat = $_GET["lat"];
$lng = $_GET["lng"];
//echo $lat."#".$lng;
$url = 'https://api.broadbandmap.nz/api/2.0/availability/'.$lat.'/'.$lng;
//echo $url;
$header = ['x-api-key:lCOFqNBLhh9aH4pp1ouAt3GKaXcnkeLN8obzK7Jf']; //设置一个你的浏览器agent的header
$result = get($url,$header);
echo $result;

//$aResData = json_decode($result);
//显示返回数据
//var_dump($aResData);
die;
