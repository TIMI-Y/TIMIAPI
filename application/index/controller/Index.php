<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
use think\Validate;
use think\Request; 
class Index extends Controller
{
    public function index(){
        
            //基础信息查询
            $info=Db::name('info')->find();
            //一句话公告查询
            $notice=Db::name('notice')->where("id=1")->find();
            //友情链接
            $link=Db::name('links')->Order("pid DESC")->select();
            //导航
            $header=Db::name('header')->Order("pid DESC")->select();
            //API
            $api=Db::name('apilist')->select();
            $apicount=Db::name('apilist')->count();
            return view('index', [
                'info'  => $info,
                'notice'=> $notice,
                'link'  => $link,
                'header'=> $header,
                'api'   => $api,
                'count' => $apicount
            ]);  
        
    	
    }
    public function apidetail(){
    	$id=(int)input('id');
    	//基础信息查询
    	$info=Db::name('info')->find();
    	//友情链接
    	$link=Db::name('links')->Order("pid DESC")->select();
    	//导航
    	$header=Db::name('header')->Order("pid DESC")->select();
    	//API详情
    	$api=Db::name('apilist')->where("id=$id")->find();
        if (empty($api)) {
            header("HTTP/1.0 404 Not Found"); 
            return $this->fetch(APP_PATH.'404.html');
        }
    	$view=$api['api_view']+1;
    	$data['api_view']=$view;
    	$re=Db::name('apilist')->where("id=$id")->update($data);
    	return view('api',[
    		'info'  => $info,
    		'link'	=> $link,
    		'header'=> $header,
    		'api'	=> $api
    	]);
	}
    public function serch(){
        $key=$_GET['key'];
        $data['api_name'] = array('like', "%$key%");
        $re=Db::name('apilist')->where($data)->select();
        $info=Db::name('info')->find();
            //一句话公告查询
            $notice=Db::name('notice')->where("id=1")->find();
            //友情链接
            $link=Db::name('links')->Order("pid DESC")->select();
            //导航
            $header=Db::name('header')->Order("pid DESC")->select();
            //API
            $api=Db::name('apilist')->select();
            $apicount=Db::name('apilist')->count();
            return view('index', [
                'info'  => $info,
                'notice'=> $notice,
                'link'  => $link,
                'header'=> $header,
                'api'   => $re,
                'count' => $apicount
            ]); 
    }
}
