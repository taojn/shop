<?php
namespace app\admin\controller;
use think\Controller;
use app\common\model\Admin;

class Index extends Controller
{
	//	判断是否登录
	public function _initialize(){
		if (empty(session('admin'))) {
			//没登录,跳转登录页面
			$this->redirect("login/login");
		}
	}

	function index (){
		return $this->fetch();
	}

	function welcome(){
		$time=time();
		$this->assign('time',$time);
		return $this->fetch();
	}
}
