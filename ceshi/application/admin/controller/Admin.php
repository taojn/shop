<?php
namespace app\admin\controller;
use think\Controller;
use app\common\model\Admin as AdminModel;
use app\common\model\Rule;
use app\common\model\Manage;
use app\common\model\Role;

class Admin extends Controller
{
//	判断是否登录
	public function _initialize(){
		if (empty(session('admin'))) {
			//没登录,跳转登录页面
			$this->redirect("login/login");
		}
	}

//	管理员页面
	function admin_list(){
		$Role=new Role;
		$Admin=new AdminModel;



		$where_name=[];
		$where_time=[];
		if(empty(input('start'))){
			$start=0;
		}else{
			$start=strtotime(input('start'));
		}
		if(empty(input('end'))){
			$end=0;
		}else{
			$end=strtotime(input('end'));
		}
		if(!empty(input('name'))){
			$name=input('name');
		}




		$data=$Admin->seach();
		$role=$Role->seach();
		if(!empty($role)){
			foreach($role as $v){
				$roleArr[$v['id']]=$v['role'];
			}
			$this->assign('roleArr',$roleArr);
		}else{
			$this->assign('roleArr',[]);
		}

		$this->assign('data',$data);
		return $this->fetch();
	}

//	添加管理员页面
	function admin_add(){
		$Role=new Role;

		$roles=$Role->seach();

		$this->assign('roles',$roles);
		return $this->fetch();
	}

//	添加管理员操作
	function admin_toadd(){
//		print_r(input('post.'));
		$Admin=new AdminModel;
		$data['name']=input('name');
		$data['create_time']=time();
		$data['pass']=md5(input('pass'));
		$data['roleid']=input('roleid');
		$res=$Admin->adminadd($data);
		if($res>0){
			return 1;
		}else{
			return 0;
		}
	}

//	管理员删除操作
	function admin_del(){
		$Admin=new AdminModel;

		$where=['id'=>input('id')];
		$res=$Admin->delwhere($where);
		if($res){
			return 1;
		}else{
			return 0;
		}

	}

//	管理员修改页面
	function admin_edit(){
		$Admin=new AdminModel;
		$Role=new Role;

		$admin=$Admin->adminget(input('id'));
		$roles=$Role->seach();

		$this->assign('admin',$admin);
		$this->assign('roles',$roles);
		return $this->fetch();
	}

//	管理员修改操作
	function admin_toedit(){
		$Admin=new AdminModel;

		if(empty(input('pass'))){
			if(empty(input('repass'))){
				$data['roleid']=input('roleid');
			}else{
				return 2;
			}
		}else{
			$data['pass']=md5(input('pass'));
		}

		$where=['id'=>input('id')];
		$res=$Admin->admin_edit($data,$where);
		if($res){
			return 1;
		}else{
			return 0;
		}
	}

//	管理员角色页面
	function admin_role(){
		$Role=new Role;
		$Manage=new Manage;

		$roleArr=$Role->seach();
		$manage=$Manage->seach();
		if(!empty($manage)){
			foreach($manage as $v){
				$manageArr[$v['id']]=$v['manage'];
			}
			$this->assign('manageArr',$manageArr);
		}else{
			$this->assign('manageArr',[]);
		}

		$this->assign('roleArr',$roleArr);

		return $this->fetch();
	}

//	添加角色页面
	function admin_role_add(){
		$Rule=new Rule;
		$Manage=new Manage;

		$data=$Rule->seach(['switch'=>'on']);
		$datas=$Manage->seach();

		$this->assign('data',$data);
		$this->assign('datas',$datas);
		return $this->fetch();
	}

//	添加角色操作
	function admin_role_toadd(){
		$Role=new Role;

		$form=input('post.');
		if(empty($form['manage'])){
			return 2;
		}
		$rule=$form['rule'];
		$manage=$form['manage'];

		$role=$form['role'];
		$manageid_str=implode($manage,',');
		$ruleid_str=implode($rule,',');
		$data['role']=$role;
		$data['manageids']=$manageid_str;
		$data['ruleids']=$ruleid_str;
		$res=$Role->roleadd($data);
		if($res>0){
			return 1;
		}else{
			return 0;
		}
	}

//	修改角色页面
	function admin_role_edit(){
		$Role=new Role;
		$Rule=new Rule;
		$Manage=new Manage;

		$id=input('id');
		$data=$Role->roleget($id);
		$manage=$Manage->seach();
		$rule=$Rule->seach(['switch'=>'on']);


		$this->assign('id',$id);
		$this->assign('data',$data);
		$this->assign('manage',$manage);
		$this->assign('rule',$rule);
		return $this->fetch();
	}

//	修改角色操作
	function admin_role_toedit(){
		$Role=new Role;

		$form=input('post.');

		if(isset($form['rule'])){
			$rule=$form['rule'];
			$ruleid_str=implode($rule,',');
		}else{
			$ruleid_str='';
		}

		$manage=$form['manage'];
		$role=$form['role'];
		$manageid_str=implode($manage,',');
		$data['role']=$role;
		$data['manageids']=$manageid_str;
		$data['ruleids']=$ruleid_str;

		$where=['id'=>input('id')];
		$res=$Role->role_edit($data,$where);
		if($res>0){
			return 1;
		}else{
			return 0;
		}
	}

//	删除角色操作
	function admin_role_del(){
		$Admin=new AdminModel;
		$Role=new Role;
		$admin=$Admin->num(['roleid'=>input('id')]);
//		halt($admin);
		if($admin>0){
			return 2;
		}else{
			$where=['id'=>input('id')];
			$res=$Role->delwhere($where);
			if($res){
				return 1;
			}else{
				return 0;
			}
		}
	}

//	管理员权限页面
	function admin_rule(){

		$Rule=new Rule;
		$Manage=new Manage;

		$data=$Rule->seach(['switch'=>'on']);
		$datas=$Manage->seach();

		$this->assign('data',$data);
		$this->assign('datas',$datas);

		return $this->fetch();
	}

//	修改权限页面
	function admin_rule_add(){
		$Rule=new Rule;
		$manageid=input('manageid');
		$where['manageid']=$manageid;
		$data=$Rule->seach($where);
		$this->assign('data',$data);
		$this->assign('manageid',$manageid);

		return $this->fetch();
	}

//	添加模块方法
	function admin_ruleN_toadd(){
		$Rule=new Rule;
		$Manage=new Manage;
		$data=input('post.');
		$res=$Manage->manageadd($data);
		if($res>0){
			$inarr=[
				'time_sea'=>'根据时间搜索',
				'name_sea'=>'根据姓名搜索',
				'phone_sea'=>'根据电话搜索',
				'code_sea'=>'根据订单编号搜索',
				'status_sea'=>'根据订单状态搜索',
				'pay_sea'=>'根据支付状态搜索',
				'shipments_sea'=>'根据发货状态搜索',
				'paypart_sea'=>'根据支付方式搜索',
				'shipmentspart_sea'=>'根据配送方式搜索',
				'name_edit'=>'修改姓名',
				'phone_edit'=>'修改电话',
				'pass_edit'=>'修改密码',
				'price_edit'=>'修改金额(单)',
				'total_edit'=>'修改金额(总)',
				'status_edit'=>'修改状态',
				'add'=>'添加',
				'delete'=>'删除'
			];
			foreach($inarr as $k=>$v){
				$addarr[]=['slipsrule'=>$k,'rule'=>$v,'switch'=>'off','manageid'=>$res];
//				$Rule->ruleadd(['slipsrule'=>$k,'rule'=>$v,'switch'=>'off','manageid'=>$res]);
			}
			$Rule->ruleaddall($addarr);
			return 1;
		}else{
			return 0;
		}
	}

//	添加权限操作
	function admin_ruleC_toadd(){
		$Rule=new Rule;
		$manageid=input('post.manageid');
		$Rule->rule_edit(['switch'=>'off'],['manageid'=>$manageid]);
		$rule=input('post.')['rule'];
		if(!empty($rule)){
			foreach($rule as $k=>$v){
				$arr=explode('-',$v);
				$dataArr[]=['id'=>$arr[1],'switch'=>'on'];
			}
			$res=$Rule->rule_alledit($dataArr);
			if($res){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 1;
		}

	}

//	删除模板
	function admin_rule_del(){
		$Manage=new Manage;
		$id=input('id');
		$res=$Manage->del($id);
		if($res){
			return 1;
		}else{
			return 0;
		}


	}
}