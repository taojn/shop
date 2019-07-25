<?php
namespace app\admin\controller;
use think\Controller;
use app\common\model\User as UserModel;

class User extends Controller
{
	//判断是否登录
	public function _initialize(){
		if (empty(session('admin'))) {
			//没登录,跳转登录页面
			$this->redirect("login/login");
		}
	}//end 判断是否登录-----------------------------------------------------------------------------

	//加载用户列表页
	function user_list(){
		$User=new UserModel;

		$data=$User->seach();

		$this->assign('data',$data);
		return $this->fetch();
	}//end 加载用户列表页-----------------------------------------------------------------------------

	//执行用户正常/封号操作
	function user_state_edit(){
		$User=new UserModel;

		$data=['state'=>input('state')];
		$where=['id'=>input('id')];
		$res=$User->user_edit($data,$where);
		if($res){
			return 1;
		}else{
			return 0;
		}
	}//end 执行用户正常/封号操作---------------------------------------------------------------------




	//加载用户密码修改页
	function user_edit(){
		$id=input('id');
		$this->assign('id',$id);
		return $this->fetch();
	}//end 加载用户密码修改页-------------------------------------------------------------------------

	//执行用户密码修改操作
	function user_toedit(){
		$User=new UserModel;
//		return input('pass');
		$data['pass']=md5(input('pass'));
		$where['id']=input('id');

		$res=$User->user_edit($data,$where);
		if($res){
			return 1;
		}else{
			return 0;
		}
	}//end 执行用户密码修改操作------------------------------------------------------------------------
}