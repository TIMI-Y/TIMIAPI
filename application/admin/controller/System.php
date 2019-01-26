<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
use think\Validate;
use think\Session;
use think\Request; 
class system extends Controller
{
	//判断是否登录
	public function _initialize()
	{
		if(empty(Session::get('USER_INFO_ID'))){
            $this->redirect('Login/index');
        }
    }
	public function index(){
		if(!Request::instance()->isAjax()){
			$notice=Db::name('notice')->where('id',1)->find();
			$info=Db::name('info')->find();
			return view('index',[
				'notice'	=> $notice,
				'info'		=> $info,
			]);
		}else{
			$data=[
				'notice'	=> $_POST['gg'],
				'url'		=> $_POST['url']
			];
			$res=Db::name('notice')->where('id',1)->update($data);
			if (!empty($res)) {
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
		
	}
	public function upinfo(){
		if(!Request::instance()->isAjax()){
			$uid=Session::get('USER_INFO_ID');
			$user=Db::name('admin')->where('id',$uid)->find();
			$link=Db::name('links')->select();
			$head=Db::name('header')->select();
			return view('upinfo',[
				'user'	=> $user,
				'link'	=> $link,
				'head'	=> $head,
			]);
		}
	}
	public function findpwd(){
		return view('findpwd');
	}
	public function ajaxupinfo(){
		if(!Request::instance()->isAjax()){
			header("HTTP/1.0 404 Not Found"); 
            return $this->fetch(APP_PATH.'404.html');
		}else{
			$data =[
				'title' 		=> $_POST['tt'],
				'description'	=> $_POST['ms'],
				'keywords'		=> $_POST['kw'],
				'logo'			=> $_POST['lo'],
				'about'			=> $_POST['ab'],
				'copyright'		=> $_POST['bq'],
				'beian'			=> $_POST['ba'],
			];
			$res=Db::name('info')->where('id',1)->update($data);
			if (!empty($res)) {
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	}
	//加密方式
    function jmpwd($p){
    	$password = $p;
		$salt = "msapi";
		$crypt=crypt($password, $salt);
		$md5crypt=md5($crypt);
		return $md5crypt;
    }
	public function uppwd(){
		if(!Request::instance()->isAjax()){
			header("HTTP/1.0 404 Not Found"); 
            return $this->fetch(APP_PATH.'404.html');
		}else{
			if (empty(trim($_POST['pass']))) {
				$data =[
					'user' 		=> $_POST['user'],
				];
				//dump($_POST);die;
			}else{
				$npwd=$this->jmpwd($_POST['pass']);
				$data =[
					'user' 		=> $_POST['user'],
					'pass' 		=> $npwd,
				];
			}
			$res=Db::name('admin')->where('id',1)->update($data);
			if (!empty($res)) {
				$this->success("修改成功！");
			}else{
				$this->error("修改失败！");
			}
		}
	}
	public function addlink(){
		if(!Request::instance()->isAjax()){
			header("HTTP/1.0 404 Not Found"); 
            return $this->fetch(APP_PATH.'404.html');
		}else{
			$data = [
				'title'	=> $_POST['lt'],
				'url'	=> $_POST['lu'],
				'pid'	=> $_POST['lp'],
			];
			$res=Db::name('links')->insert($data);
			if (!empty($res)) {
				$this->success("新增成功！");
			}else{
				$this->error("新增失败！");
			}
		}
		echo "球都没带"; die;
	}
	public function addhead(){
		if(!Request::instance()->isAjax()){
			header("HTTP/1.0 404 Not Found"); 
            return $this->fetch(APP_PATH.'404.html');
		}else{
			$data = [
				'title'	=> $_POST['ht'],
				'url'	=> $_POST['hu'],
				'pid'	=> $_POST['hp'],
			];
			$res=Db::name('header')->insert($data);
			if (!empty($res)) {
				$this->success("新增成功！");
			}else{
				$this->error("新增失败！");
			}
		}
		echo "球都没带"; die;	
	}
	public function hddel(){
		if(!Request::instance()->isAjax()){
			header("HTTP/1.0 404 Not Found"); 
            return $this->fetch(APP_PATH.'404.html');
		}else{
			$id=(int)$_POST['id'];

			$res=Db::name('header')->where('id',$id)->delete();
			if (!empty($res)) {
				$this->success("删除成功！");
			}else{
				$this->error("删除失败！");
			}
		}
	}
	public function lkdel(){
		if(!Request::instance()->isAjax()){
			header("HTTP/1.0 404 Not Found"); 
            return $this->fetch(APP_PATH.'404.html');
		}else{
			$id=(int)$_POST['id'];
			$res=Db::name('links')->where('id',$id)->delete();
			if (!empty($res)) {
				$this->success("删除成功！");
			}else{
				$this->error("删除失败！");
			}
		}
	}
}