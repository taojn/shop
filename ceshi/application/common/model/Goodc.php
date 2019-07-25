<?php
namespace app\common\model;
use think\Model;

class Goodc extends Model
{
	protected $table='goodc';
	protected $all_arr=[];

	//分页查询
	function seach($where=[]){
//		$data=$this->where($where)->order('id desc')->paginate(15,false,$url);
		$data=$this->all($where);
		return $data;
	}

	//单条查询
	function goodcget($id){
		return $this->get($id);
	}

	//单条条件查询
	function whereget($where){
		$this->all_arr=$where;
		return $this->get(function($query){
			$query->where($this->all_arr);
		});
	}

	//查询总条数
	function num($where){
		$num=$this->where($where)->count();
		return $num;
	}

	//添加
	function goodcadd($data){
		$this->data($data);
		$this->save();
		return $this->id;
	}

	//批量添加
	function goodcalladd($data){
		return $this->saveAll($data);
	}

	//修改
	function goodc_edit($data,$where){
		return $this->save($data,$where);
	}

	//删除(where)
	function delwhere($where){
		return $this->destroy($where);
	}

}

