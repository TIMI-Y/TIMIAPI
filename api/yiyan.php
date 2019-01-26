<?php

header('X-Powered-By:Xiaojun API (api.xjdog.cn)');
header('access-control-allow-origin:*');
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
if ($_GET['charset']=='GBK' ||$_GET['charset']=='gbk' || $_GET['charset']=='gb2312'){
  $array=file('hitokoto.txt');
  $rand=rand(0,3388);
 
  function utf8_to_gbk($str){
    return mb_convert_encoding($str, 'gbk', 'utf-8');
}
    $string=$array[$rand];
	header('Content-Type: text/html; charset=GBK');
    if ($_GET['code']==='js' || $_GET['code']==='javascript' || $_GET['code']==='JavaScript') {
		header('Content-type: application/x-javascript; charset=GBK');
          echo "function xjhitokoto(){document.write(\"";
          echo trim(utf8_to_gbk($string)) . "\");}";
		 } elseif ($_GET['code']==='array' || $_GET['code']==='Array' || $_GET['code']==='arr' || $_GET['code']==='Arr') {
			$arr = array(
			'code' => 200 ,
			'msg' => trim(utf8_to_gbk($string))
			);
			var_dump($arr);
	    }else{
          echo trim(utf8_to_gbk($string));
		}
    }else{ 

  $array=file('hitokoto.txt');
  $rand=rand(0,3388);
  $string=$array[$rand];
  function arrayToXml($arr,$dom=null,$node=null,$root='xml',$cdata=false){  
    if (!$dom){  
        $dom = new DOMDocument('1.0','utf-8');  
    }  
    if(!$node){  
        $node = $dom->createElement($root);  
        $dom->appendChild($node);  
    }  
    foreach ($arr as $key=>$value){  
        $child_node = $dom->createElement(is_string($key) ? $key : 'node');  
        $node->appendChild($child_node);  
        if (!is_array($value)){  
            if (!$cdata) {  
                $data = $dom->createTextNode($value);  
            }else{  
                $data = $dom->createCDATASection($value);  
            }  
            $child_node->appendChild($data);  
        }else {  
            arrayToXml($value,$dom,$child_node,$root,$cdata);  
        }  
    }  
    return $dom->saveXML();  
}  
header('Content-Type: text/html; charset=UTF-8');
    if ($_GET['code']==='js' || $_GET['code']==='javascript' || $_GET['code']==='JavaScript') {
		 header('Content-type: application/x-javascript; charset=UTF-8');
          echo "function xjhitokoto(){document.write(\"";
          echo trim($string);
          echo "\");}";
		 } elseif ($_GET['code']==='json' || $_GET['code']==='JSON') {
			header('Content-type: application/json; charset=UTF-8');
			$json = json_encode(array(
			'code' => 200 ,
			'msg' => trim($string)
			));
			echo $json;
		} elseif ($_GET['code']==='xml' || $_GET['code']==='XML') {
		$xml = arrayToXml(array(
			'msg' => trim($string)
			));	
			echo $xml;
		 } elseif ($_GET['code']==='array' || $_GET['code']==='Array' || $_GET['code']==='arr' || $_GET['code']==='Arr') {
			$arr = array(
			'code' => 200 ,
			'msg' => trim($string)
			);
		var_dump($arr);
        }else{
          echo trim($string);
          }
	}
?>
