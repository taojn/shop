<?php
namespace app\common\model;
use think\Model;

class Type extends Model
{
	protected $table='type';
	protected $all_arr=[];

	//分页查询
	function seach($where=[]){
		$this->all_arr=$where;
//		$data=$this->where($where)->field('pid,id,name,path,concat(path,id) p')->order('p')->paginate(50,false,$url);
		$data=$this->all(function($query){
			$query->where($this->all_arr)->field('pid,id,name,path,concat(path,id) p')->order('p');
		});
		return $data;
	}

	//单条查询
	function typeget($id){
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
	function type_add($data){
		$this->data($data);
		$this->save();
		return $this->id;
	}

	//修改
	function type_edit($data,$where){
		return $this->save($data,$where);
	}

	//删除(where)
	function delwhere($where){
		return $this->destroy($where);
	}

	//获取器
	function getAddtimeAttr($time){
		return date('Y-m-d H:i:s',$time);
	}
	function getPathAttr($count){
		return substr_count($count,',');
	}
}

