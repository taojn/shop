<?php
namespace app\admin\controller;
use think\Controller;
use app\common\model\Admin as AdminModel;

class Login extends Controller
{
	//登录页面
	function login(){
		return $this->fetch();
	}

	//登录
	function dologin(){
		$Admin=new AdminModel;

		$name=input('name');
		$data=$Admin->whereget(['name'=>$name]);
		if($data){
			$pass=input('pass');
			if(md5($pass)==$data['pass']){
				session('admin',$data);
				return 1;

			}else{
				return 0;
			}
		}else{
			return 2;
		}
	}

	//退出
	function out(){
		session('admin',null);
		return $this->fetch('login/login');
	}

}