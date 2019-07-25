<?php
namespace app\admin\controller;
use think\Controller;
use app\common\model\Comment as CommentModel;
use app\common\model\User;

class Comment extends Controller
{
	//判断是否登录
	public function _initialize(){
		if (empty(session('admin'))) {
			//没登录,跳转登录页面
			$this->redirect("login/login");
		}
	}//end 判断是否登录-----------------------------------------------------------------------------

	//加载评论列表页
	function comment_list(){
		$User=new User;
		$Comment=new CommentModel;

		$data=$Comment->seach();
		$user=$User->seach();

		$this->assign('data',$data);
		$this->assign('user',$user);
		return $this->fetch();
	}//end 加载用户列表页-----------------------------------------------------------------------------

	//执行评论显示/隐藏操作
	function comment_state_edit(){
		$Comment=new CommentModel;

		$data=['state'=>input('state')];
		$where=['id'=>input('id')];
		$res=$Comment->comment_edit($data,$where);
		if($res){
			return 1;
		}else{
			return 0;
		}
	}//end 执行用户正常/封号操作---------------------------------------------------------------------

	//加载评论回复添加页
	function comment_f_add(){
		$id=input('id');
		$this->assign('id',$id);
		return $this->fetch();
	}//end 加载用户密码修改页-------------------------------------------------------------------------

	//执行评论回复添加操作
	function comment_f_toadd(){
		$Comment=new CommentModel;

		$id=input('id');
		$fcontent=input('fcontent');
		$where['id']=$id;
		$data['fcontent']=$fcontent;
		$res=$Comment->comment_edit($data,$where);
		if($res===false){
			return 0;
		}else{
			return 1;
		}
	}//end 执行用户密码修改操作-------------------------------------------------------------------------

}