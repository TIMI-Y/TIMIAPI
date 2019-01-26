<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use think\Validate;
use think\Session;
class Login extends Controller
{
	//判断是否登录
	public function _initialize(){
        if(!empty(Session::get('USER_INFO_ID'))){
            $this->redirect('index/index');
        }
    }
    //首页
    public function index(){

       return view('index');
    }
    public function ajaxLogin(){
    	$username=$this->remove_xss($_POST['name']);;
		$password=$this->jmppwd($_POST['pass']);
		//dump($password);die;
		$re=Db::name('admin')->where(array('user' =>$username ,'pass'=>$password ))->find();
		// echo Db::name('admin')->getLastsql();die;
    	if (!empty($re)) {
    		$data=$re['id'];
    		Session::set('USER_INFO_ID',$data);
    		
    		$this->success("登入成功",url('index/index'));
    	}else{
    		$this->error("登入失败!");
    	}
    }	
    //加密方式
    function jmppwd($p){
    	$password = $p;
		$salt = "msapi";
		$crypt=crypt($password, $salt);
		$md5crypt=md5($crypt);
		return $md5crypt;
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
?>