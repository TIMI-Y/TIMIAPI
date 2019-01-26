<?php
namespace app\api\controller;
use think\Db;
use think\Controller;
use think\Validate;
use think\Session;
use think\Request; 
class Api extends Controller
{
	public function wether(){
		$ip = $_GET["ip"];
		$url='http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
		$result = file_get_contents($url);
		$result = json_decode($result,true);
		if($result['code']!==0 || !is_array($result['data'])) return false;

		$city=$result['data']['city'];
		$curl = curl_init();
		$url="http://wthrcdn.etouch.cn/WeatherApi?city=郑州";
		//设置抓取的url
		curl_setopt($curl, CURLOPT_URL, $url);
		//设置头文件的信息作为数据流输出
		//curl_setopt($curl, CURLOPT_HEADER, 1);
		//设置获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_ENCODING, "");
		//执行命令
		$data = curl_exec($curl);
		//关闭URL请求
		curl_close($curl);
		//显示获得的数据
		libxml_disable_entity_loader(true);

		$d=json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
		echo json_encode($d); 
	}
}