<?php
namespace app\common\model;
use think\Model;

class Rule extends Model
{
	protected $table='rule';
	protected $all_arr=[];

	//查询
	function seach($where=[]){
		$this->all_arr=$where;
		$data=$this->all(function($query){
			$query->where($this->all_arr);
		});
		return $data;
	}

	//添加
	function ruleadd($data){
		$this->data($data);
		$this->save();
		return $this->id;
	}

	//添加多条
	function ruleaddall($data){
		return $this->saveAll($data);
	}

	//删除(where)
	function delwhere($where){
		return $this->destroy($where);
	}

	//修改
	function rule_edit($data,$where){
		return $this->save($data,$where);
	}

	//批量修改
	function rule_alledit($data){
		return $this->saveAll($data);
	}

}

