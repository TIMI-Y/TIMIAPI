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
$res = @$_GET['name'];
if(strstr($res,"."))
{
$json = file_get_contents('http://icp.chinaz.com/'.$res); //调用的站长工具
function GetBetween($content,$start,$end){
$r = explode($start, $content);
if (isset($r[1])){
$r = explode($end, $r[1]);
return $r[0];
}
return '';
}
// 获取主办单位名称
$b ='主办单位名称</span><p>';
$c ='<';
$name = GetBetween($json,$b,$c);
// 获取性质
$b ='主办单位性质</span><p><strong class="fl fwnone">';
$c ='<';
$nature = GetBetween($json,$b,$c);
// 获取备案号
$b ='网站备案/许可证号</span><p><font>';
$c ='<';
$icp = GetBetween($json,$b,$c);
// 获取网站名称
$b ='网站名称</span><p>';
$c ='<';
$sitename = GetBetween($json,$b,$c);
// 获取网站首页地址
$b ='网站首页网址</span><p class="Wzno">';
$c ='<';
$index = GetBetween($json,$b,$c);
if(strstr($index,"."))
{ }else{

$atr = array(

    "code"=>array("104"),
	"msg"=>array("未查到此域名的备案信息！"),
	
); 
echo json_encode($atr); die;
}
// 获取审核时间
$b ='审核时间</span><p>';
$c ='<';
$time = GetBetween($json,$b,$c);

$atr = array(

    "code"=>array("101"),
	"msg"=>array("获取成功"),
	"name"=>array($name),//主办单位名称
	"nature"=>array($nature),//主办单位性质
	"icp"=>array($icp),//备案号
	"sitename"=>array($sitename),
	"index"=>array($index),
	"time"=>array($time),
);  
echo json_encode($atr); die;
}else{
$atr = array(

    "code"=>array("102"),
	"msg"=>array("获取失败！地址不能为空"),
	
);  
echo json_encode($atr); die;
exit();
}
?>