<?php
namespace app\common\model;
use think\Model;

class Manage extends Model
{
	protected $table='manage';
	protected $all_arr=[];

	//查询
	function seach($where=[]){
		$this->all_arr=$where;
		$data=$this->all(function($query){
			$query->where($this->all_arr)->order('id desc');
		});
		return $data;
	}

	//添加
	function manageadd($data){
		$this->data($data);
		$this->allowField(true)->save();
		return $this->id;
	}

	//删除(id)
	function del($id){
		return $this->destroy($id);
	}
}

