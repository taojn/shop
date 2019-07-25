<?php
namespace app\common\model;
use think\Model;

class Role extends Model
{
	protected $table='role';
	protected $all_arr=[];

	//查询
	function seach($where=[]){
		$this->all_arr=$where;
		$data=$this->all(function($query){
			$query->where($this->all_arr);
		});
		return $data;
	}

	//单条查询
	function roleget($id){
		return $this->get($id);
	}

	//添加
	function roleadd($data){
		$this->data($data);
		$this->save();
		return $this->id;
	}

	//添加多条
	function roleaddall($data){
		return $this->saveAll($data);
	}

	//删除(where)
	function delwhere($where){
		return $this->destroy($where);
	}

	//修改
	function role_edit($data,$where){
		return $this->save($data,$where);
	}

	//批量修改
	function role_alledit($data){
		return $this->saveAll($data);
	}

	//获取器
	function getManageidsAttr($manageids){
		return explode(',',$manageids);
	}

	function getRuleidsAttr($ruleids){
		return explode(',',$ruleids);
	}

}

