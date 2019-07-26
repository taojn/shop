<?php
namespace app\admin\controller;
use think\Controller;
use app\common\model\Good as GoodModel;
use app\common\model\Goods;
use app\common\model\Goodc;
use app\common\model\Type;
use think\Request;
use think\Image;

class Good extends Controller
{

	//	判断是否登录
	public function _initialize(){
		if(empty(session('admin'))){
			//没登录,跳转登录页面
			$this->redirect("login/login");
		}
	}//---------------------------------------------------------------------------------------------

	//加载商品列表页
	public function good_list(){
		$Good=new GoodModel;
		$Goods=new Goods;
		$Goodc=new Goodc;
		$Type=new Type();

		$type=$Type->seach();

		$good=$Good->seach();
		$color=$Goodc->seach();
		$size=$Goods->seach();

		$this->assign('good',$good);
		$this->assign('color',$color);
		$this->assign('size',$size);
		$this->assign('type',$type);

		return $this->fetch();
	}//end 加载商品列表页-------------------------------------------------------------------------------

	//加载商品添加页
	public function good_add(){
		return $this->fetch();
	}//end 加载商品添加页--------------------------------------------------------------------------------

	//查询所有商品类型并排序
	public function good_type_seach(){
		$Type=new Type();

		$data=$Type->seach();

//		return $data;
		$arr=[];
		foreach ($data as $v){
			if($v['pid']==0){
				$aarr['id']=$v['id'];
				$aarr['label']=$v['name'];
				$aarr['rank']=$v['path'];
				$arr[]=$aarr;
			}elseif($v['path']==2){
				foreach ($arr as $kk=>$vv){
					if($vv['id']==$v['pid']){
						$aarr['id']=$v['id'];
						$aarr['label']=$v['name'];
						$aarr['rank']=$v['path'];
						$arr[$kk]['children'][]=$aarr;
					}
				}
			}elseif($v['path']==3){
				$pid=explode(',',$v['p'])[1];
				foreach ($arr as $kk=>$vv){
					if($vv['id']==$pid){
						foreach ($arr[$kk]['children'] as $kkk=>$vvv){
							if($vvv['id']==$v['pid']){
								$aarr['id']=$v['id'];
								$aarr['label']=$v['name'];
								$aarr['rank']=$v['path'];
								$arr[$kk]['children'][$kkk]['children'][]=$aarr;
							}
						}

					}
				}
			}

		}
		return $arr;
//		return [array(
//				'id'=>1,
//				'label'=>'安徽省',
//				'rank'=>1,
//				'children'=>[array(
//						'id'=>2,
//						'label'=>'马鞍上市',
//						'rank'=>2,
//						'children'=>[array(
//							'id'=>3,
//							'label'=>'和县',
//							'rank'=>3,
//						),
//						array(
//							'id'=>4,
//							'label'=>'花山区',
//							'rank'=>3,
//						)]
//					)]
//			),
//			array(
//			'id'=>1,
//			'label'=>'安徽省',
//			'rank'=>1,
//			'children'=>[array(
//				'id'=>2,
//				'label'=>'马鞍上市',
//				'rank'=>2,
//				'children'=>[array(
//					'id'=>3,
//					'label'=>'和县',
//					'rank'=>3,
//				),
//					array(
//						'id'=>4,
//						'label'=>'花山区',
//						'rank'=>3,
//					)]
//			)]
//		)];
	}//end 查询所有商品类型并排序----------------------------------------------------------------------------

	//执行商品添加操作
	public function good_toadd(){
		$Good=new GoodModel;
		$Goodc=new Goodc;
		$Goods=new Goods;

		$data=input('post.');

		//获取上传文件的数据
		$goodpicfile=request()->file('file');
		$detailpicfile=request()->file('detailfile');

		//初始化图片名
		$goodpicname="";
		$detailpicname="";
		foreach ($goodpicfile as $file) {
			// 移动到框架应用根目录/public/static/images/good 目录下
			$info = $file->rule("uniqid")->move(ROOT_PATH.'public/static/images/good');
			//判断是否上传成功
			if($info){
				$picname=$info->getFilename();
				$goodpicname.=$picname.",";
			}else{
				// 上传失败获取错误信息
				return $file->getError();
			}
		}
		foreach ($detailpicfile as $file) {
			// 移动到框架应用根目录/public/static/images/detailgood 目录下
			$info = $file->rule("uniqid")->move(ROOT_PATH.'public/static/images/detailgood');
			//判断是否上传成功
			if($info){
				$picname=$info->getFilename();
				$detailpicname.=$picname.",";
			}else{
				// 上传失败获取错误信息
				return $file->getError();
			}
		}


		$fData=$data['fData'];
		$addData=[];
		$i=0;
//		return $fData;
		$kucun=0;
		foreach ($fData as $v){
			$varr=explode(',',$v);
//			return $varr;
			foreach($varr as $k=>$z){
				if($k==0){
					$addData[$i]['size']=$z;
					$addData[$i]['color']='';
					$addData[$i]['num']='';
				}elseif($k==1){
					$addData[$i]['price']=$z;
				}elseif($k%2==0){
					$addData[$i]['color'].=$z.',';
				}elseif($k%2==1){
					$addData[$i]['num'].=$z.',';
					$kucun+=$z;
				}
			}
			$i++;
		}
//		return $addData;

		$typeid=$data['typeid'];
		$name=$data['name'];
		$content=$data['content'];

		//整理添加的数据
		$gooddata['typeid']=$typeid;
		$gooddata['name']=$name;
		$gooddata['num']=$kucun;
		$gooddata['goodpic']=$goodpicname;
		$gooddata['detailpic']=$detailpicname;
		$gooddata['content']=$content;
		$gooddata['state']=2;
		$gooddata['addtime']=time();

		$res=$Good->goodadd($gooddata);
		if(!$res){
			//循环删除上传的图片
			foreach (explode(",", $goodpicname) as $value) {
				@unlink(ROOT_PATH . 'public/static/images/good/'.$value);
			}
			foreach (explode(",", $detailpicname) as $value) {
				@unlink(ROOT_PATH . 'public/static/images/detailgood/'.$value);
			}
			return 0;
		}

		foreach ($addData as $aaa=>$arr){
				$sdata['gid']=$res;
				$sdata['name']=$arr['size'];
				$skucun=0;
				foreach (explode(',',rtrim($arr['num'],',')) as $v){
					$skucun+=$v;
				}
				$sdata['num']=$skucun;
				$sdata['price']=$arr['price'];

				$sid=$Goods->goodsadd($sdata);
				if($sid>0){

					//初始化批量添加的数据
					$pdata=[];
					foreach (explode(',',rtrim($arr['color'],',')) as $kk=>$vv){
						$cdata['gid']=$res;
						$cdata['sid']=$sid;
						$cdata['name']=$vv;
						foreach (explode(',',rtrim($arr['num'],',')) as $kkk=>$vvv){
							if($kk==$kkk){
								$cdata['num']=$vvv;
							}
						}
						$pdata[]=$cdata;
					}
					$res1=$Goodc->goodcalladd($pdata);
					if(!$res1){
						return 0;
					}
				}else{
					return 0;
				}
		}
		return 1;
	}//end 执行商品添加操作--------------------------------------------------------------------------------

	//加载商品图片列表页
	public function goodpic_list(){
		$Good=new GoodModel;

		$id=input('id');
		$data=$Good->goodget($id);

		$this->assign('data',$data);
		return $this->fetch();
	}//end 加载商品图片列表页---------------------------------------------------------------------------------

	//执行添加商品图操作
	public function goodpic_add(){
		$Good=new GoodModel;

		$id=input('id');
		$datas=$Good->goodget($id);
		$goodpic=implode(',',$datas['goodpic']).',';

		$newpic = request()->file('file');
		$info = $newpic->rule("uniqid")->move(ROOT_PATH.'public/static/images/good');
		//判断是否上传成功
		if($info){
			$pic=$info->getFilename();
			//整理添加数据
			$data['goodpic']=$goodpic.$pic.',';
			$where['id']=$id;
			$e=$Good->good_edit($data,$where);
			if ($e>0) {
				return 1;
			}else {
//				失败后删除已上传的图片
				unlink(ROOT_PATH . 'public/static/images/good/'.$pic);
				return 0;
			}
		}else{
			// 上传失败获取错误信息
			return $newpic->getError();
		}
	}//end 执行添加商品图操作----------------------------------------------------------------------------------

	//执行删除商品图操作
	public function goodpic_del(){
		$Good=new GoodModel;

		$id=input('id');
		$datas=$Good->goodget($id);
		$pic=input('pic');
		$data['goodpic']='';
		foreach ($datas['goodpic'] as $k=>$v){
			if($pic!=$v){
				$data['goodpic'].=$v.',';
			}
		}
		if(empty($data['goodpic'])){
			return 2;
		}

		//整理添加数据
		$where['id']=$id;
		$e=$Good->good_edit($data,$where);
		if($e>0){
			unlink(ROOT_PATH . 'public/static/images/good/'.$pic);
			return 1;
		}else{
			return 0;
		}
	}//end 执行删除商品图操作--------------------------------------------------------------------------------

	//加载商品详情图片列表页
	public function detailpic_list(){
		$Good=new GoodModel;

		$id=input('id');
		$data=$Good->goodget($id);
		$this->assign('data',$data);
		return $this->fetch();
	}//end 加载商品详情图片列表页-------------------------------------------------------------------------------

	//执行添加详情图操作
	public function detailpic_add(){
		$Good=new GoodModel;

		$id=input('id');
		$datas=$Good->goodget($id);
		$goodpic=implode(',',$datas['detailpic']).',';

		$newpic = request()->file('file');
		$info = $newpic->rule("uniqid")->move(ROOT_PATH.'public/static/images/detailgood');
		//判断是否上传成功
		if($info){
			$pic=$info->getFilename();
			//整理添加数据
			$data['detailpic']=$goodpic.$pic.',';
			$where['id']=$id;
			$e=$Good->good_edit($data,$where);
			if ($e>0) {
				return 1;
			}else {
//				失败后删除已上传的图片
				unlink(ROOT_PATH . 'public/static/images/detailgood/'.$pic);
				return 0;
			}
		}else{
			// 上传失败获取错误信息
			return $newpic->getError();
		}
	}//end 执行添加详情图操作----------------------------------------------------------------------------------

	//执行删除详情图操作
	public function detailpic_del(){
		$Good=new GoodModel;

		$id=input('id');
		$datas=$Good->goodget($id);
		$pic=input('pic');
		$data['detailpic']='';
		foreach ($datas['detailpic'] as $k=>$v){
			if($pic!=$v){
				$data['detailpic'].=$v.',';
			}
		}
		if(empty($data['detailpic'])){
			return 2;
		}

		//整理添加数据
		$where['id']=$id;
		$e=$Good->good_edit($data,$where);
		if($e>0){
			unlink(ROOT_PATH . 'public/static/images/detailgood/'.$pic);
			return 1;
		}else{
			return 0;
		}
	}//end 执行删除详情图操作----------------------------------------------------------------------------------

	//加载商品详情页
	public function good_details(){
		$Good=new GoodModel;
		$Goods=new Goods;
		$Goodc=new Goodc;

		$gid=input('id');
		$where['gid']=$gid;
		$size=$Goods->seach($where);
		$color=$Goodc->seach($where);

		$this->assign('color',$color);
		$this->assign('size',$size);
		$this->assign('gid',$gid);
		return $this->fetch();
	}//end 加载商品详情页-------------------------------------------------------------------------------------

	//加载商品详情添加页
	public function good_details_add(){
		$Goodc=new Goodc;
		$Goods=new Goods;

		$gid=input('gid');
		$this->assign('gid',$gid);
		return $this->fetch();
	}//end 加载商品详情添加页------------------------------------------------------------------------------------

	//执行商品添加操作
	public function good_details_toadd(){
		$Good=new GoodModel;
		$Goodc=new Goodc;
		$Goods=new Goods;

		$data=input('post.');
//		return $data;
		$gid=$data['gid'];
		$getgood=$Good->goodget($gid);
		$kucun=$getgood['num'];

		$fData=$data['fData'];
		$addData=[];
		$i=0;
//		return $fData;
		foreach ($fData as $v){
			$varr=explode(',',$v);
//			return $varr;
			foreach($varr as $k=>$z){
				if($k==0){
					$addData[$i]['size']=$z;
					$addData[$i]['color']='';
					$addData[$i]['num']='';
				}elseif($k==1){
					$addData[$i]['price']=$z;
				}elseif($k%2==0){
					$addData[$i]['color'].=$z.',';
				}elseif($k%2==1){
					$addData[$i]['num'].=$z.',';
					$kucun+=$z;
				}
			}
			$i++;
		}
//		return $addData;


		//整理添加的数据
		$gooddata['num']=$kucun;
		$where['id']=$gid;
		$res=$Good->good_edit($gooddata,$where);
		if(!$res){
			return 0;
		}

		foreach ($addData as $aaa=>$arr){
			$sdata['gid']=$gid;
			$sdata['name']=$arr['size'];
			$skucun=0;
			foreach (explode(',',rtrim($arr['num'],',')) as $v){
				$skucun+=$v;
			}
			$sdata['num']=$skucun;
			$sdata['price']=$arr['price'];

			$sid=$Goods->goodsadd($sdata);
			if($sid>0){
				//初始化批量添加的数据
				$pdata=[];
				foreach (explode(',',rtrim($arr['color'],',')) as $kk=>$vv){
					$cdata['gid']=$gid;
					$cdata['sid']=$sid;
					$cdata['name']=$vv;
					foreach (explode(',',rtrim($arr['num'],',')) as $kkk=>$vvv){
						if($kk==$kkk){
							$cdata['num']=$vvv;
						}
					}
					$pdata[]=$cdata;
				}
				$res1=$Goodc->goodcalladd($pdata);
				if(!$res1){
					return 0;
				}
			}else{
				return 0;
			}
		}
		return 1;
	}//end 执行商品添加操作----------------------------------------------------------------------------------

	//加载商品详情修改页
	public function good_details_edit(){
		$Goodc=new Goodc;
		$Goods=new Goods;

		$id=input('id');
		$datas=$Goods->goodsget($id);
		$datac=$Goodc->seach(['sid'=>$id]);
		$this->assign('id',$id);
		$this->assign('datas',$datas);
		$this->assign('datac',$datac);
		return $this->fetch();
	}//end 加载商品详情修改页-----------------------------------------------------------------------------------

	//执行商品详情修改操作
	public function good_details_toedit(){
		$Good=new GoodModel;
		$Goodc=new Goodc;
		$Goods=new Goods;

		//接受数据
		$data=input('post.');
		$sid=$data['sid'];
		$size=$data['size'];
		$price=$data['price'];
		$color=$data['color'];
		$num=$data['num'];

		$cdata=$Goodc->seach(['sid'=>$sid]);

		$alldata=[];
		$znum=0;
		foreach($num as $k=>$v){
			$lins['num']=$v;
			$lins['name']=$color[$k];
			$lins['sid']=$sid;
			$lins['gid']=$cdata[0]['gid'];

			$znum+=$v;
			$alldata[]=$lins;

		}
		$Goodc->delwhere(['sid'=>$sid]);
		$Goodc->goodcalladd($alldata);

		$sdata=$Goods->goodsget($sid);
		if($size==$sdata['name'] && $price==$sdata['price'] && $znum==$sdata['num']){
			return 1;
		}else{
			$edit_s_data['name']=$size;
			$edit_s_data['price']=$price;
			$edit_s_data['num']=$znum;
			$res=$Goods->goods_edit($edit_s_data,['id'=>$sid]);
			if($res === false){
				return 0;
			}else{
				$addnum=$znum-$sdata['num'];
				$gdata=$Good->goodget($sdata['gid']);
				$gres=$Good->good_edit(['num'=>$gdata['num']+$addnum],['id'=>$sdata['gid']]);
				if($gdata===false){
					return 0;
				}else{
					return 1;
				}
			}
		}


		$fData=$data['fData'];
		$addData=[];
		$i=0;
		$kucun=0;
		foreach ($fData as $v){
			$varr=explode(',',$v);
			foreach($varr as $k=>$z){
				if($k==0){
					$addData[$i]['size']=$z;
					$addData[$i]['color']='';
					$addData[$i]['num']='';
				}elseif($k==1){
					$addData[$i]['price']=$z;
				}elseif($k%2==0){
					$addData[$i]['color'].=$z.',';
				}elseif($k%2==1){
					$addData[$i]['num'].=$z.',';
					$kucun+=$z;
				}
			}
			$i++;
		}


		//整理添加的数据
		$gooddata['num']=$kucun;

		$res=$Good->goodadd($gooddata);
		if(!$res){
			return 0;
		}

		foreach ($addData as $aaa=>$arr){
			$sdata['gid']=$res;
			$sdata['name']=$arr['size'];
			$skucun=0;
			foreach (explode(',',rtrim($arr['num'],',')) as $v){
				$skucun+=$v;
			}
			$sdata['num']=$skucun;
			$sdata['price']=$arr['price'];

			$sid=$Goods->goodsadd($sdata);
			if($sid>0){

				//初始化批量添加的数据
				$pdata=[];
				foreach (explode(',',rtrim($arr['color'],',')) as $kk=>$vv){
					$cdata['gid']=$res;
					$cdata['sid']=$sid;
					$cdata['name']=$vv;
					foreach (explode(',',rtrim($arr['num'],',')) as $kkk=>$vvv){
						if($kk==$kkk){
							$cdata['num']=$vvv;
						}
					}
					$pdata[]=$cdata;
				}
				$res1=$Goodc->goodcalladd($pdata);
				if(!$res1){
					return 0;
				}
			}else{
				return 0;
			}
		}
		return 1;
	}//end 执行商品详情修改操作----------------------------------------------------------------------------------

	//执行商品详情删除操作
	public function good_details_del(){
		$Good=new GoodModel;
		$Goodc=new Goodc;
		$Goods=new Goods;

		$id=input('id');
		$sdata=$Goods->goodsget($id);
		$gid=$sdata['gid'];
		$skucun=$sdata['num'];
		$gooddata=$Good->goodget($gid);
		$edit_num=$gooddata['num']-$skucun;
		$Good->good_edit(['num'=>$edit_num],['id'=>$gid]);
		$Goods->delwhere(['id'=>$id]);
		$Goodc->delwhere(['sid'=>$id]);

		return 1;
	}//end 执行商品详情删除操作-----------------------------------------------------------------------------------

	//加载商品修改页
	public function good_edit(){
		$Good=new GoodModel;
		$Type=new Type;


		$id=input('id');
		$data=$Good->goodget($id);
		$type=$Type->typeget($data['typeid']);
		$this->assign('type',$type['name']);
		$this->assign('data',$data);

		return $this->fetch();
	}//end 加载商品修改页--------------------------------------------------------------------------------------

	//执行商品修改操作
	public function good_toedit(){
		$Good=new GoodModel;
		$Goods=new Goods;
		$Goodc=new Goodc;

		$data=input('post.');
		$edit_data['name']=$data['name'];
		$edit_data['typeid']=$data['typeid'];
		$edit_data['content']=$data['desc'];
		$where['id']=$data['id'];
		$res=$Good->good_edit($edit_data,$where);
		if($res!==false){
			return 1;
		}else{
			return 0;
		}

	}//end 执行商品修改操作--------------------------------------------------------------------------------------

	//执行商品状态修改操作
	public function good_state_edit(){
		$Good=new GoodModel;
		$state=input('state');
		$id=input('id');
//		return input('id');
		$where=['id'=>$id];
		$data=['state'=>$state];
		$res=$Good->good_edit($data,$where);
		if($res){
			return 1;
		}else{
			return 0;
		}
	}//end 执行商品状态修改操作-------------------------------------------------------------------------------------

	//执行删除商品操作
	public function good_del(){
		$Good=new GoodModel;
//		$Goods=new Goods;
//		$Goodc=new Goodc;

		$id=input('id');
		$data=$Good->goodget($id);
		$goodpicname=$data['goodpic'];
		$detailpicname=$data['detailpic'];

		//循环删除上传的图片
		foreach ($goodpicname as $value) {
			@unlink(ROOT_PATH . 'public/static/images/good/'.$value);
		}
		foreach ($detailpicname as $value) {
			@unlink(ROOT_PATH . 'public/static/images/detailgood/'.$value);
		}

		$res=$Good->delwhere(['id'=>$id]);
		if($res){
			return 1;
		}else{
			return 0;
		}

	}//end 执行删除商品操作-----------------------------------------------------------------------------------

}