<?php
namespace app\admin\controller;
use think\Controller;

class Unicode extends Controller
{
	//	判断是否登录
	public function _initialize(){
		if (empty(session('admin'))) {
			//没登录,跳转登录页面
			$this->redirect("login/login");
		}
	}

	function unicode (){
		return $this->fetch();
	}
}