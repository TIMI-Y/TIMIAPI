<?php
$qq = $_GET['qq'];
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
$src = 'https://q1.qlogo.cn/g?b=qq&nk='.$qq.'&s=100&t='.time();  
header('Content-type: image/png');  
$res = imagecreatefromstring(file_get_contents($src)); 
imagepng($res); 
imagedestroy($res); 
?>