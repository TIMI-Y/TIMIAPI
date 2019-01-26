<?php

header("Access-Control-Allow-Origin:*");
header('Content-type: application/json');
$countfile = "num.txt";

if (($fp = fopen($countfile, "r+")) == false) { //用读写模式打开文件，若不能打开就退出
    printf("打开文件 %s 失败!", $countfile);
    exit;
} else {
    //如果文件能够正常打开，就读入文件中的数据，假设是1
    $count = fread($fp, 10);
    //读取10位数据
    $count = $count + 1;
    fclose($fp);
    //关闭当前文件
    $fp = fopen($countfile, "w+");
    //以覆盖模式打开文件
    fwrite($fp, $count);
    //写入加1后的新数据
    fclose($fp);
    //并关闭文件
    //echo $count;
}
if(!isset($_GET['url'])||empty($_GET['url'])||$_GET['url']==''){
	echo json_encode(array('code'=>'201','msg'=>'请填写请求参数'));
	exit();
}
// 请求地址www.baidu.com
$url = $_GET['url'];
// 百度搜索地址http://www.baidu.com/s?wd=site:www.youngxj.cn
$baidu='http://www.baidu.com/s?wd=site:'.$url;
 
$curl=curl_init();
curl_setopt($curl,CURLOPT_URL,$baidu);
curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,false);curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
$rs=curl_exec($curl);
curl_close($curl);
 
$str = preg_match_all('/<b>找到相关结果数约(.*?)个<\/b>/',$rs,$baidu);
 
if(!empty($str)){
	// 没有站点信息
	echo json_encode(array('code'=>'200','num'=>$baidu['1']['0']));
}else{
	// 有站点信息
	$str = preg_match_all('/<b style="color:#333">(.*?)<\/b>/',$rs,$baidu);
	if($str){
		echo json_encode(array('code'=>'200','num'=>$baidu['1']['0']));
	}else{
		echo json_encode(array('code'=>'202','msg'=>'该域名暂时未收录'));
	}
	
}