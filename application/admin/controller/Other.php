<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use think\Validate;
use think\Session;
use think\Request; 
class Other extends Controller
{
	public function apineed()
	{
		if(!Request::instance()->isAjax()){
			return view('apineed');
		}else{
			//echo "<meta http-equiv='Content-Type' content='text/Html; charset=utf-8'>";
			$mail=$this->remove_xss($_POST['mail']);
			$name=$this->remove_xss($_POST['name']);
			$adress=$this->remove_xss($_POST['adress']);
			$servername=$_SERVER["SERVER_NAME"];
			$url="http://api.tecms.net/index/index/need?mail=".$mail."&name=".$name."&adress=".$adress."&servername=".$servername;
			$curl = curl_init();	
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_ENCODING, "");
			$data = curl_exec($curl);
			curl_close($curl);

			$data= json_decode($data,true);
			if ($data['code']==1) {
				$this->success("提交成功！请注意邮箱反馈~！");
			}
			if ($data['code']==2) {
				$this->success("远程接口关闭！");
			}
		}
		
	}
	public function tiwen()
	{
		return view('index');
	}
	//防止XSS
    function remove_xss($val) {
		$val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
		$search = 'abcdefghijklmnopqrstuvwxyz';
		$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$search .= '1234567890!@#$%^&*()';
		$search .= '~`";:?+/={}[]-_|\'\\';
		for ($i = 0; $i < strlen($search); $i++) {
	    	$val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
	      	$val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
		}
		$ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
		$ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
		$ra = array_merge($ra1, $ra2);
		$found = true; // keep replacing as long as the previous round replaced something
		while ($found == true) {
	    	$val_before = $val;
	    	for ($i = 0; $i < sizeof($ra); $i++) {
	        	$pattern = '/';
	        	for ($j = 0; $j < strlen($ra[$i]); $j++) {
	            	if ($j > 0) {
		               $pattern .= '(';
		               $pattern .= '(&#[xX]0{0,8}([9ab]);)';
		               $pattern .= '|';
		               $pattern .= '|(&#0{0,8}([9|10|13]);)';
		               $pattern .= ')*';
	            	}
	            $pattern .= $ra[$i][$j];
	        }
	        $pattern .= '/i';
	        $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
	        $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
		        if ($val_before == $val) {
		            // no replacements were made, so exit the loop
		        	$found = false;
		        }
	    	}
		}
	   return $val;
	}
}