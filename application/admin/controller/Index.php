<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use think\Validate;
use think\Session;
use think\Request; 
class Index extends Controller
{
	//判断是否登录
	public function _initialize()
	{
		if(empty(Session::get('USER_INFO_ID'))){
            $this->redirect('Login/index');
        }
    }
    //退出登录
    public function loginout()
    {
        session_destroy();
        $this->success('退出成功',url('Login/index'));
    }
	public function index()
	{
		return view('index');
	}
	public function addapi()
	{
		if(!Request::instance()->isAjax()){
			return view('addapi');
		}else{
			$data = [
				'api_name' 		=> trim($_POST['apim']),
				'api_introduce'	=> trim($_POST['apijj']),
				'api_keywords'	=> trim($_POST['apigjc']),
				'api_adress'	=> trim($_POST['apiqqdz']),
				'return_format'	=> trim($_POST['apifhgs']),
				'request_mode'	=> trim($_POST['apiqqfs']),
				'request_demo'	=> trim($_POST['apiqqsl']),
				'request_parameter'	=> $_POST['qqcssm'],
				'return_parameter'	=> $_POST['fhcssm'],
				'return_demo'	=> $_POST['fhsl'],
				'error_code'	=> $_POST['fwjczm'],
				'error_code_demo'	=> $_POST['xtjczm'],
				'error_code_explain'	=> $_POST['cwmgs'],
				'php_demo'		=> $_POST['php'],
			];
			$res=Db::name('apilist')->data($data)->insert();
			if (!empty($res)) {
				$this->success("新增成功",url('index/addapi'));
			}else{
				$this->error("新增失败，可能是数据库链接异常，请重试！");
			}
		}
		echo "非法操作！";die;
	}
	public function apilist(){
		$list=Db::name('apilist')->select();
		return view('apilist',[
    		'list'  => $list,
		]);
	}
	public function apidel(){
		if(!Request::instance()->isAjax()){
			header("HTTP/1.0 404 Not Found"); 
            return $this->fetch(APP_PATH.'404.html');
		}else{
			$id=(int)$_POST['id'];
			$res=Db::name('apilist')->where('id',$id)->delete();
			if (!empty($res)) {
				$this->success("删除成功！");
			}else{
				$this->error("删除失败！");
			}
		}
	}
	public function apiedit(){
		$id=(int)input('id');
		$info=Db::name('apilist')->where('id',$id)->find();
		if (empty($info)) {
			header("HTTP/1.0 404 Not Found"); 
            return $this->fetch(APP_PATH.'404.html');
		}else{
			if(!Request::instance()->isAjax()){
				return view('apiedit',[
	    		'info'  => $info,
				]);
			}else{
				$id=(int)trim($_POST['id']);
				
				$data = [
				'api_name' 		=> trim($_POST['apim']),
				'api_introduce'	=> trim($_POST['apijj']),
				'api_keywords'	=> trim($_POST['apigjc']),
				'api_adress'	=> trim($_POST['apiqqdz']),
				'return_format'	=> trim($_POST['apifhgs']),
				'request_mode'	=> trim($_POST['apiqqfs']),
				'request_demo'	=> trim($_POST['apiqqsl']),
				'request_parameter'	=> $_POST['qqcssm'],
				'return_parameter'	=> $_POST['fhcssm'],
				'return_demo'	=> $_POST['fhsl'],
				'error_code'	=> $_POST['fwjczm'],
				'error_code_demo'	=> $_POST['xtjczm'],
				'error_code_explain'	=> $_POST['cwmgs'],
				'php_demo'		=> $_POST['php'],
				];
				$res=Db::name('apilist')->where('id',$id)->update($data);
				if (!empty($res)) {
					$this->success("编辑成功！",url('index/apilist'));
				}else{
					$this->error("编辑失败！有可能是数据库连接异常！");
				}
			}
		}
		echo "非法操作";die;
	}
	public function madeapi(){
		return view('madeapi');
	}
}
?>