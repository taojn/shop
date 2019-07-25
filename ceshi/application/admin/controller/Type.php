<?php
namespace app\admin\controller;
use think\Controller;
use app\common\model\Type as TypeModel;

class Type extends Controller
{
	//	判断是否登录
	public function _initialize(){
		if (empty(session('admin'))) {
			//没登录,跳转登录页面
			$this->redirect("login/login");
		}
	}//---------------------------------------------------------------------------------------------

	//加载分类列表页面
	function type_list(){
		$Type=new TypeModel;

		$data=$Type->seach();
		$this->assign('data',$data);
		return $this->fetch();
	}//----------------------------------------------------------------------------------------------

	//执行添加根分类操作
	function type_p_toadd(){
		$Type= new TypeModel;

		$name=input('name');
		$data['pid']=0;
		$data['name']=$name;
		$data['path']='0,';
		$res=$Type->type_add($data);
		if($res>0){
			return 1;
		}else{
			return 0;
		}
	}//----------------------------------------------------------------------------------------------

	//加载添加子分类页面
	function type_add(){
		$id=input('id');
		$pid=input('pid');
		$path=input('path');
		if($path==1){
			$path_str='0,'.$id.',';
		}elseif($path==2){
			$path_str='0,'.$pid.','.$id.',';
		}

		$this->assign('path',$path_str);
		$this->assign('pid',$id);
		return $this->fetch();
	}//----------------------------------------------------------------------------------------------

	//执行添加分类操作
	function type_toadd(){
		$Type=new TypeModel;

		$pid=input('pid');
		$path=input('path');
		$name=input('name');
		$data['pid']=$pid;
		$data['path']=$path;
		$data['name']=$name;
		$res=$Type->type_add($data);
		if($res>0){
			return 1;
		}else{
			return 0;
		}
	}//----------------------------------------------------------------------------------------------

	//加载修改分类名页面
	function type_edit(){
		$Type=new TypeModel;

		$id=input('id');
		$data=$Type->typeget($id);
		$this->assign('data',$data);
		return $this->fetch();
	}//----------------------------------------------------------------------------------------------

	//执行修改分类名操作
	function type_toedit(){
		$Type=new TypeModel;

		$id=input('id');
		$name=input('name');
		$data['name']=$name;
		$where['id']=$id;
		$res=$Type->type_edit($data,$where);
		if($res){
			return 1;
		}else{
			return 0;
		}
	}//----------------------------------------------------------------------------------------------

	//执行删除分类操作
	function type_del(){
		$Type=new TypeModel;

		$id=input('id');
		$data=$Type->typeget($id);
		if($data->path==3){
			//三级 判断此分类下是否有商品

		}
		$res=$Type->num(['pid'=>$data->id]);
		if($res>0){
			//此分类下有子分类
			return 2;
		}else{
			$err=$Type->delwhere(['id'=>$id]);
			if($err){
				return 1;
			}else{
				return 0;
			}
		}
	}//----------------------------------------------------------------------------------------------


}//end---------------