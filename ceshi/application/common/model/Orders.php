<?php
namespace app\common\model;
use think\Model;

class Orders extends Model
{
	protected $table='orders';
	protected $all_arr=[];

	//分页查询
	function seach($where=[],$url=[]){
		$data=$this->where($where)->order('id desc')->paginate(15,false,$url);
//		$data=$this->all($where);
		return $data;
	}

	//单条查询
	function ordersget($id){
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
	function ordersadd($data){
		$this->data($data);
		$this->save();
		return $this->id;
	}

	//修改
	function orders_edit($data,$where){
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
	function getAddressAttr($address){
		$arr=explode('@',$address);
		$rearr['address']=explode(',',$arr[0]);
		$rearr['detail']=$arr[1];
		return $rearr;
	}

}

