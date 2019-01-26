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
$ip = $_GET["ip"];
if(empty($ip)){
  $atr = array(

      "code"=>array("102"),
	"msg"=>array("ip不能为空"),

);    
  echo json_encode($atr); die;
}
function isAjax() {
  return @$_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ? true : false;
}
$url='http://ip.taobao.com/service/getIpInfo.php?ip='.$ip;
$result = file_get_contents($url);
$result = json_decode($result,true);
if($result['code']!==0 || !is_array($result['data'])) return false;

$city=$result['data']['city'];
//var_dump($city);die;
if ($city=='XX') {
	 $atr = array(
	"code"=>array("106"),
	"msg"=>array("城市无效！"),
	);
	echo json_encode($atr); die;   
}
$curl = curl_init();
$url="http://wthrcdn.etouch.cn/WeatherApi?city=".$city;
curl_setopt($curl, CURLOPT_URL, $url);
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
return json_encode($d); 
?>