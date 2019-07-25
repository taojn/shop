<?php
namespace app\admin\controller;
use think\Controller;
use think\Image;
use app\common\model\Banner as BannerModel;

class Banner extends Controller
{
	//判断是否登录
	public function _initialize(){
		if (empty(session('admin'))) {
			//没登录,跳转登录页面
			$this->redirect("login/login");
		}
	}

	//加载轮播图管理页
	function banner(){
		$Banner=new BannerModel;
		$data=$Banner->seach();

		$this->assign('data',$data);
		return $this->fetch();
	}

	//加载轮播图添加页
	function banner_add(){
		return $this->fetch();
	}

	//执行轮播图添加操作
	function banner_toadd(){
		$Banner=new BannerModel;

		// 获取表单上传文件
		$file = request()->file('pic');
		// 移动到框架应用根目录/public/static/banner 目录下
		$info = $file->rule("uniqid")->move(ROOT_PATH.'public/static/banner');
		//判断是否上传成功
		if($info){
			$pic=$info->getFilename();
			//上传成功后要执行图片的缩放
			$image=Image::open(ROOT_PATH . 'public/static/banner/'.$pic);
//			$image->thumb(200,200)->save(ROOT_PATH . 'public/static/banner/s_'.$pic);
			//整理添加数据
			$data['name']=input('name');
			$data['pic']=$pic;
			$data['addtime']=time();
			$e=$Banner->banner_add($data);
			if ($e>0) {
				return 1;
			}else {
//				失败后删除已上传的图片
				unlink(ROOT_PATH . 'public/static/banner/'.$pic);
//				unlink(ROOT_PATH . 'public/static/banner/s_'.$pic);
				return 0;
			}
		}else{
			// 上传失败获取错误信息
			return $file->getError();
		}
	}

	//执行轮播图显示/隐藏操作
	function banner_switch_edit(){
		$Banner=new BannerModel;
		$data=['switch'=>input('switch')];
		$where=['id'=>input('id')];
		$res=$Banner->banner_edit($data,$where);
		if($res){
			return 1;
		}else{
			return 0;
		}
	}

	//执行轮播图修操作
	function banner_pic_edit(){
		$Banner=new BannerModel;

		$id=input('id');
		$oldpic=input('pic');
		$newpic = request()->file('file');
		$info = $newpic->rule("uniqid")->move(ROOT_PATH.'public/static/banner');
		//判断是否上传成功
		if($info){
			$pic=$info->getFilename();
			//上传成功后要执行图片的缩放
			$image=Image::open(ROOT_PATH . 'public/static/banner/'.$pic);
			//整理添加数据
			$data['pic']=$pic;
			$where['id']=$id;
			$e=$Banner->banner_edit($data,$where);
			if ($e>0) {
				unlink(ROOT_PATH . 'public/static/banner/'.$oldpic);
				return 1;
			}else {
//				失败后删除已上传的图片
				unlink(ROOT_PATH . 'public/static/banner/'.$pic);
				return 0;
			}
		}else{
			// 上传失败获取错误信息
			return $newpic->getError();
		}
	}

	//加载轮播图修改页
	function banner_edit(){
		$Banner=new BannerModel;

		$data=$Banner->whereget(['id'=>input('id')]);
		$this->assign('data',$data);
		return $this->fetch();
	}

	//执行修改banner名操作
	function banner_toedit(){
		$Banner=new BannerModel;
		$data['name']=input('name');
		$where['id']=input('id');

		$res=$Banner->banner_edit($data,$where);
		if($res){
			return 1;
		}else{
			return 0;
		}
	}




	//删除轮播图
	function banner_del(){
		$Banner=new BannerModel;

		$pic=input('pic');
		$id=input('id');
		$res=$Banner->delwhere(['id'=>$id]);
		if($res){
			unlink(ROOT_PATH . 'public/static/banner/'.$pic);
			return 1;
		}else{
			return 0;
		}
	}
}