<?php
namespace app\common\model;
use think\Model;

class Comment extends Model
{
	protected $table='comment';
	protected $all_arr=[];

	//分页查询
	function seach($where=[],$url=[]){
		$data=$this->where($where)->order('id desc')->paginate(15,false,$url);
//		$data=$this->all($where);
		return $data;
	}

	//单条查询
	function commentget($id){
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
	function commentadd($data){
		$this->data($data);
		$this->save();
		return $this->id;
	}

	//修改
	function comment_edit($data,$where){
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
	function getContentAttr($content){
		$str="/(操)|(泥马)|(草泥马)|(傻逼)|(麻痹)|(妈逼)|(操蛋)|(屎)|(粪)|(啪啪啪)|(滚)|(妈B)|(J8)|(JB)|(jb)|(虐)|(妓)|(淫)|(骚)|(弱智)|(畜生)|(下流)|(人渣)|(XX)|(老子)|(变态)/";
		return preg_replace($str,'**',$content);
	}

}

